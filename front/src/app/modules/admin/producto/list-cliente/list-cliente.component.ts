import {AfterViewInit, Component, OnDestroy, OnInit, ViewChild} from '@angular/core';
import {MaterialService} from "@core/service/api/material.service";
import {MatDialog} from "@angular/material/dialog";
import {AlertDialogComponent} from "@app/shared/alert-dialog/alert-dialog.component";
import {CreateClienteComponent} from "@app/modules/admin/producto/list-cliente/create-cliente/create-cliente.component";
import {ClienteService} from "@core/service/api/cliente.service";
import {catchError, debounceTime, distinctUntilChanged, map, switchMap, takeUntil} from "rxjs/operators";
import {ActivatedRoute, Params, Router} from "@angular/router";
import {Observable, Subject, of} from 'rxjs';
import {MatPaginator} from "@angular/material/paginator";

@Component({
  selector: 'app-list-cliente',
  templateUrl: './list-cliente.component.html',
  styleUrls: ['./list-cliente.component.scss']
})
export class ListClienteComponent implements OnInit, AfterViewInit, OnDestroy {
  @ViewChild(MatPaginator) paginator: MatPaginator;

  displayedColumns: string[] = [
    'id',
    'nombre',
    'carnet_identidad',
    'celular',
    'provincia',
    'actions',
  ];
  dataSource$ = new Observable<any[]>();
  pageTotal: number;
  pageSize: number = 3;
  private unsubscribe$ = new Subject<void>();
  searchTerm$ = new Subject<string>();
  textSearh: string;

  constructor(
    private clienteService: ClienteService,
    public dialog: MatDialog,
    private route: ActivatedRoute,
    private router: Router,
  ) {
  }

  ngOnInit(): void {

    // this.list();
    this.getDataFromApi();
    this.filterList();
  }

  ngOnDestroy(): void {
    this.unsubscribe$.next();
    this.unsubscribe$.complete();
  }

  ngAfterViewInit(): void {
    this.paginator.page.subscribe(() => {
      this.router.navigate(['/lista-cliente'], {
        relativeTo: this.route,
        queryParams: {page: this.paginator.pageIndex + 1},
        queryParamsHandling: 'merge',
      });
    });
  }


  filterList(): void {
    this.searchTerm$
      .pipe(
        debounceTime(400),
        distinctUntilChanged(),
        // map(term => {
        //   return this.listDeliciousDishes
        //     .filter(item => item.toLowerCase().indexOf(term.toLowerCase()) >= 0);
        // })
      ).subscribe(text => {
      this.router.navigate(['/lista-cliente'], {
        relativeTo: this.route,
        queryParams: {q: text},
        queryParamsHandling: 'merge',
      });
    });
  }

  getDataFromApi() {
    this.dataSource$ = this.route.queryParams.pipe(
      takeUntil(this.unsubscribe$),
      switchMap((params: Params) => {
        console.log(params)
        this.textSearh = params.q || null;
        const filters = {
          // status: params.status || '',
          // gender: params.gender || '',
          q: params.q || '',
          page: params.page || 1,
        };

        return this.clienteService.getAll(filters).pipe(
          map((res) => {
            console.log('paginacion=>', res.data);
            this.pageSize = res.meta.per_page;
            this.pageTotal = res.meta.total;
            return res.data;
          }),
          catchError(() => {
            this.pageTotal = 0;
            return of(null);
          })
        );
      })
    );
  }


  edit(id: string | number | ArrayBufferView | ArrayBuffer) {
    console.log(id);
    const dialogRef = this.dialog.open(CreateClienteComponent, {
      width: '640px',
      disableClose: true,
      data: {id: id},
    });
    dialogRef.afterClosed().subscribe((res) => {
      console.log('edit list', res);
      if (res) {
        this.getDataFromApi();
      }
    });
  }

  delete(id: string | number | ArrayBufferView | ArrayBuffer) {
    const dialogRef = this.dialog.open(AlertDialogComponent, {
      data: {
        message: 'Estas seguro que deseas eliminar?',
        buttonText: {
          ok: 'Si',
          cancel: 'Cancelar',
        },
      },
    });
    dialogRef.afterClosed().subscribe((confirmed: boolean) => {
      if (confirmed) {
        console.log('borrando');
        this.clienteService.delete(id as string).subscribe((res) => {
          console.log(res);
          this.getDataFromApi();
        });
      }
    });

    console.log(id);
  }

  createNew() {
    const dialogRef = this.dialog.open(CreateClienteComponent, {
      width: '640px',
      disableClose: true,
    });
    dialogRef.afterClosed().subscribe((res) => {
      console.log('edit list', res);
      if (res) {
        this.getDataFromApi();
      }
    });
  }
}
