import {AfterViewInit, Component, OnDestroy, OnInit, ViewChild} from '@angular/core';
import {MatPaginator} from "@angular/material/paginator";
import {MaterialProductoService} from "@core/service/api/material-producto.service";
import {ActivatedRoute, Params, Router} from "@angular/router";
import {MatDialog} from "@angular/material/dialog";
import {environment} from "@env/environment";
import {catchError, map, switchMap, takeUntil} from "rxjs/operators";
import { Observable, Subject, of } from 'rxjs';
@Component({
  selector: 'app-show-material',
  templateUrl: './show-material.component.html',
  styleUrls: ['./show-material.component.scss']
})
export class ShowMaterialComponent implements OnInit, AfterViewInit, OnDestroy {
  @ViewChild(MatPaginator) paginator: MatPaginator;
  // displayedColumns = ['id', 'name', 'status', 'gender', 'species'];
  displayedColumns = ['id', 'nombre', 'cantidad', 'medida', 'descripcion'];
  dataSource$ = new Observable<any[]>();
  pageTotal: number;
  pageSize: number = 3;
  private unsubscribe$ = new Subject<void>();
  url: any = '';
  productoId: string;

  constructor(
    private characterService: MaterialProductoService,
    private route: ActivatedRoute,
    private router: Router,
    public dialog: MatDialog
  ) {

    this.url= environment.serverUrl+this.characterService.baseUrl+'/lista';
    this.productoId= this.route.snapshot.paramMap.get('id');
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

        return this.characterService.getMaterialByIdProducto(this.productoId).pipe(
          map((res) => {
            console.log('response =>', res);
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
