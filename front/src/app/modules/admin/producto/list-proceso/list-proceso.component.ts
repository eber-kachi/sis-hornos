import { Component, ViewChild } from '@angular/core';
import { MatDialog } from '@angular/material/dialog';
import { MatSelectionList, MatSelectionListChange } from '@angular/material/list';
import { ProcesoService } from '@app/core/service/api/proceso.service';


@Component({
  selector: 'app-list-proceso',
  templateUrl: './list-proceso.component.html',
  styleUrls: ['./list-proceso.component.scss']
})
export class ListProcesoComponent {


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
