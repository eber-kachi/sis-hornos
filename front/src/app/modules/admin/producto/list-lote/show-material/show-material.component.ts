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
import {MaterialProductoService} from "@core/service/api/material-producto.service";
import {environment} from "@env/environment";
@Component({
  selector: 'app-show-material',
  templateUrl: './show-material.component.html',
  styleUrls: ['./show-material.component.scss'],
})
export class ShowMaterialComponent implements OnInit, AfterViewInit, OnDestroy {
  @ViewChild(MatPaginator) paginator: MatPaginator;
  // displayedColumns = ['id', 'name', 'status', 'gender', 'species'];
  displayedColumns = ['id', 'nombre', 'cantidad', 'cantidad_total'];
  dataSource$ = new Observable<any[]>();
  pageTotal: number;
  pageSize: number = 3;
  private unsubscribe$ = new Subject<void>();
  url: any = '';
   loteId: string;

  constructor(
    private characterService: MaterialProductoService,
    private route: ActivatedRoute,
    private router: Router,
    public dialog: MatDialog
  ) {

    this.url= environment.serverUrl+this.characterService.baseUrl+'/lote';
    this.loteId= this.route.snapshot.paramMap.get('id');
    // console.log( 'Aqui',   this.route.snapshot.paramMap.get('id'))
  }

  ngOnInit() {
    this.getDataFromApi();
  }

  ngOnDestroy(): void {
    this.unsubscribe$.next();
    this.unsubscribe$.complete();
  }

  ngAfterViewInit(): void {
    // this.paginator.page.subscribe(() => {
    //   this.router.navigate(['/lista-pedidos'], {
    //     relativeTo: this.route,
    //     queryParams: { page: this.paginator.pageIndex + 1 },
    //     queryParamsHandling: 'merge',
    //   });
    // });
  }

  getDataFromApi() {
    this.dataSource$ = this.route.queryParams.pipe(
      takeUntil(this.unsubscribe$),
      switchMap((params: Params) => {
        // console.log(params)
        const filters = {
          // status: params.status || '',
          // gender: params.gender || '',
          // name: params.name || '',
          // page: params.page || 1,
        };

        return this.characterService.getMaterialByIdLote(this.loteId).pipe(
          map((res) => {
            // console.log('paginacion=>', res);
            // this.pageSize = res.meta.per_page;
            // this.pageTotal = res.meta.total;
            return res.data;
          }),
          catchError(() => {
            // this.pageTotal = 0;
            return of(null);
          })
        );
      })
    );
  }

  download() {

  }
}
