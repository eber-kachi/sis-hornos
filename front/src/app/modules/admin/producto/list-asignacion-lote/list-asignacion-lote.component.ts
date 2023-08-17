import { Component, ViewChild } from '@angular/core';
import { MatDialog } from '@angular/material/dialog';
import { MatSelectionList, MatSelectionListChange } from '@angular/material/list';
import { ProcesoService } from '@app/core/service/api/proceso.service';

@Component({
  selector: 'app-list-asignacion-lote',
  templateUrl: './list-asignacion-lote.component.html',
  styleUrls: ['./list-asignacion-lote.component.scss']
})

export class ListAsignacionLoteComponent {

  @ViewChild('shoes') shoesSelectionList: MatSelectionList;
  displayedColumns: string[] = [
    'id',
    'marcado_planchas','cortado_planchas','plegado_planchas',
    'soldadura','prueba_conductos','armado_cuerpo','pintado','armado_accesorios'
  ];
  typesOfShoes: string[] = [    'marcado_planchas','cortado_planchas','plegado_planchas',
  'soldadura','prueba_conductos','armado_cuerpo','pintado','armado_accesorios'
];

selectedRights: string[] = ['marcado_planchas','cortado_planchas','plegado_planchas',];
  
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


selectionChange (change: MatSelectionListChange) {
  console.log(this.getSelected());
 // console.log(change.option.value, change.option.selected);
}

getSelected() {
  return this.shoesSelectionList.selectedOptions.selected.map(s => s.value);
}


}
