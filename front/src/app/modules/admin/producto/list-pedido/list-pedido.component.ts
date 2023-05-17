import { AfterViewInit, Component, OnInit, ViewChild } from '@angular/core';
import { MatPaginator } from '@angular/material/paginator';
import { ActivatedRoute, Params, Router } from '@angular/router';
import { PedidoService } from '@core/service/api/pedido.service';
import { Observable, of } from 'rxjs';
import { catchError, map, switchMap } from 'rxjs/operators';

@Component({
    selector: 'app-list-pedido',
    templateUrl: './list-pedido.component.html',
    styleUrls: ['./list-pedido.component.scss'],
})
export class ListPedidoComponent implements OnInit, AfterViewInit {
    @ViewChild(MatPaginator) paginator: MatPaginator;
    // displayedColumns = ['id', 'name', 'status', 'gender', 'species'];
    displayedColumns = [
        'id',
        'fecha_pedido',
        'total_precio',
        'cliente',
        'detalle',
    ];
    dataSource$ = new Observable<any[]>();
    pageTotal: number;
    pageSize: number = 3;

    constructor(
        private characterService: PedidoService,
        private route: ActivatedRoute,
        private router: Router
    ) {}

    ngOnInit() {
        this.getDataFromApi();
    }

    ngAfterViewInit(): void {
        this.paginator.page.subscribe(() => {
            console.log(this.route.url);

            this.router.navigate(['/'], {
                relativeTo: this.route,
                queryParams: { page: this.paginator.pageIndex + 1 },
                queryParamsHandling: 'merge',
            });
        });
    }

    getDataFromApi() {
        this.dataSource$ = this.route.queryParams.pipe(
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

    createNew() {}
}
