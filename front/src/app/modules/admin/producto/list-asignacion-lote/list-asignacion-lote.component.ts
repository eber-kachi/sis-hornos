import { Component } from '@angular/core';
import { MatDialog } from '@angular/material/dialog';
import { ProcesoService } from '@app/core/service/api/proceso.service';

@Component({
  selector: 'app-list-asignacion-lote',
  templateUrl: './list-asignacion-lote.component.html',
  styleUrls: ['./list-asignacion-lote.component.scss']
})
export class ListAsignacionLoteComponent {

  displayedColumns: string[] = [
    'id',
    'marcado_planchas','cortado_planchas','plegado_planchas',
    'soldadura','prueba_conductos','armado_cuerpo','pintado','armado_accesorios'
  ];
  dataSource = [];
 
  constructor(
        private procesoService: ProcesoService,
        public dialog: MatDialog
    ) {}
    ngOnInit(): void {
        // this.usuario$ = this.usuarioService.getAll();

        this.list();
    }
 
  list() {
    this.procesoService.getAll().subscribe((res) => {
      console.log(res);
        this.dataSource = res.data;
    });
}


}
