import {
  AfterViewInit,
  Component,
  OnDestroy,
  OnInit,
  ViewChild,
} from '@angular/core';
import { MatPaginator } from '@angular/material/paginator';
import { PedidoService } from '@core/service/api/pedido.service';
import { ActivatedRoute, Params, Router } from '@angular/router';
import { MatDialog } from '@angular/material/dialog';
import { catchError, map, switchMap, takeUntil } from 'rxjs/operators';
import { CreatePedidoComponent } from '@app/modules/admin/producto/list-pedido/create-pedido/create-pedido.component';
import { AlertDialogComponent } from '@app/shared/alert-dialog/alert-dialog.component';
import { Observable, Subject, of } from 'rxjs';
import { CreateLoteComponent } from '@app/modules/admin/producto/list-lote/create-lote/create-lote.component';
import { LoteProduccionService } from '@core/service/api/lote-produccion.service';

@Component({
  selector: 'app-list-lote',
  templateUrl: './list-lote.component.html',
  styleUrls: ['./list-lote.component.scss'],
})
export class ListLoteComponent implements OnInit, AfterViewInit, OnDestroy {
  @ViewChild(MatPaginator) paginator: MatPaginator;
  // displayedColumns = ['id', 'name', 'status', 'gender', 'species'];
  displayedColumns = ['id', 'fecha', 'estado', 'grupo', 'pedido', 'actions'];
  dataSource$ = new Observable<any[]>();
  pageTotal: number;
  pageSize: number = 3;
  private unsubscribe$ = new Subject<void>();

  constructor(
    private loteProduccionService: LoteProduccionService,
    private route: ActivatedRoute,
    private router: Router,
    public dialog: MatDialog
  ) {}

  ngOnInit() {
    this.getDataFromApi();
  }

  ngOnDestroy(): void {
    this.unsubscribe$.next();
    this.unsubscribe$.complete();
  }

  ngAfterViewInit(): void {
    this.paginator.page.subscribe(() => {
      this.router.navigate(['/lista-lote'], {
        relativeTo: this.route,
        queryParams: { page: this.paginator.pageIndex + 1 },
        queryParamsHandling: 'merge',
      });
    });
  }

  getDataFromApi() {
    this.dataSource$ = this.route.queryParams.pipe(
      takeUntil(this.unsubscribe$),
      switchMap((params: Params) => {
        const filters = {
          // status: params.status || '',
          // gender: params.gender || '',
          q: params.q || '',
          page: params.page || 1,
        };

        return this.loteProduccionService.getAll(filters).pipe(
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

  createNew() {
    const dialogRef = this.dialog.open(CreateLoteComponent, {
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

  edit(id: string | number | ArrayBufferView | ArrayBuffer) {
    console.log(id);
    const dialogRef = this.dialog.open(CreateLoteComponent, {
      width: '640px',
      disableClose: true,
      data: { id: id },
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
        this.loteProduccionService.delete(id as string).subscribe((res) => {
          console.log(res);
          this.getDataFromApi();
        });
      }
    });
  }
}
