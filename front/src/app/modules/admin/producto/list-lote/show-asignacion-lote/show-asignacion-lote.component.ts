import { Component, OnDestroy, OnInit } from '@angular/core';
import { ActivatedRoute, Params } from '@angular/router';
import { AsignacionService } from '@app/core/service/api/asignacion.service';
import { environment } from '@env/environment';
import { Observable, Subject, catchError, map, of, switchMap, takeUntil } from 'rxjs';

@Component({
  selector: 'app-show-asignacion-lote',
  templateUrl: './show-asignacion-lote.component.html',
  styleUrls: ['./show-asignacion-lote.component.scss']
})
export class ShowAsignacionLoteComponent implements OnInit, OnDestroy {
  
  displayedColumns: string[] = [
    'id',
    'lote_produccion_id',
    'grupos_trabajo_id',
    'cantidad_asignada',
    //'porcentaje_avance',
    //'$lote',
    'procesos',
  ];
  dataSource$ = new Observable<any[]>();
  url: any = '';
   loteId: string;
   private unsubscribe$ = new Subject<void>();
  constructor(
        private asignacionService: AsignacionService,
        private route: ActivatedRoute,
      //  private router: Router,
       
    ) {
     this.url= environment.serverUrl+this.asignacionService.baseUrl+'/lote';
    this.loteId= this.route.snapshot.paramMap.get('id');

    }  
    
    ngOnInit() {
      this.list();
    }
    ngOnDestroy(): void {
      this.unsubscribe$.next();
      this.unsubscribe$.complete();
    }
 
  list() {
    
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

        return this.asignacionService.getAsignacionByIdLote(this.loteId).pipe(
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


}
