import { Component } from '@angular/core';
import { AsignacionService } from '@app/core/service/api/asignacion.service';

@Component({
  selector: 'app-list-asignacion',
  templateUrl: './list-asignacion.component.html',
  styleUrls: ['./list-asignacion.component.scss']
})
export class ListAsignacionComponent {
  displayedColumns: string[] = [
    'lote_produccion_id',
    'grupos_trabajo_id',
    'cantidad_asignada',
    'porcentaje_avance',
    '$lote',
    'procesos',
  ];
  dataSource = [];
 
  constructor(
        private asignacionService: AsignacionService,
   //     public dialog: MatDialog
    ) {}
    ngOnInit(): void {
        // this.usuario$ = this.usuarioService.getAll();

        this.list();
    }
 
  list() {
    this.asignacionService.getAll().subscribe((res) => {
        console.log(res);
        this.dataSource = res.data;
    });
}

}
