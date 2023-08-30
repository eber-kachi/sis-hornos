import {
  AfterViewInit,
  Component,
  OnInit,
  ViewChild,
  OnDestroy,
} from '@angular/core';
import { MatPaginator, PageEvent } from '@angular/material/paginator';
import { ActivatedRoute, Params, Router } from '@angular/router';
import { PedidoService } from '@core/service/api/pedido.service';
import { Observable, Subject, of } from 'rxjs';
import { catchError, map, switchMap, takeUntil } from 'rxjs/operators';
import { MatDialog } from '@angular/material/dialog';
import { AlertDialogComponent } from '@app/shared/alert-dialog/alert-dialog.component';
import { CreatePedidoComponent } from './create-pedido/create-pedido.component';

@Component({
  selector: 'app-list-pedido',
  templateUrl: './list-pedido.component.html',
  styleUrls: ['./list-pedido.component.scss'],
})
export class ListPedidoComponent implements OnInit, AfterViewInit, OnDestroy {
  @ViewChild(MatPaginator) paginator: MatPaginator;
  // displayedColumns = ['id', 'name', 'status', 'gender', 'species'];
  displayedColumns = [
    'id',
    'fecha_pedido',
    'estado',
    'total_precio',
    'cliente',
    'detalle',
    'actions',
  ];
  dataSource$ = new Observable<any[]>();
  pageTotal: number;
  pageSize: number = 3;
  private unsubscribe$ = new Subject<void>();

  constructor(
    private characterService: PedidoService,
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
      this.router.navigate(['/lista-pedidos'], {
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
          // name: params.name || '',
          page: params.page || 1,
        };

        return this.characterService.getAll(filters).pipe(
          map((res) => {
            console.log('paginacion=>', res);
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
    const dialogRef = this.dialog.open(CreatePedidoComponent, {
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
    const dialogRef = this.dialog.open(CreatePedidoComponent, {
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
        this.characterService.delete(id as string).subscribe((res) => {
          console.log(res);
          this.getDataFromApi();
        });
      }
    });
  }

  UpdateEstado(id: number) { 
    // Llama al método del servicio para actualizar el estado 
    console.log('Pedido actualizado correctamente');
    this.characterService.updateEstado(id, 'Entregado').subscribe( (res) => {
       // Muestra un mensaje de éxito 
       console.log('Pedido actualizado correctamente');
        // Recarga los datos de la tabla 
        this.getDataFromApi(); }, (err) => { 
          // Muestra un mensaje de error 
          console.error('Error al actualizar el pedido', err); } ); 
        }
}
