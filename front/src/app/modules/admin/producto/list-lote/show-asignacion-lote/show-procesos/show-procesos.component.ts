import { Component, ViewChild } from '@angular/core';
import { MatSelectionList, MatSelectionListChange } from '@angular/material/list';

@Component({
  selector: 'app-show-procesos',
  templateUrl: './show-procesos.component.html',
  styleUrls: ['./show-procesos.component.scss']
})
export class ShowProcesosComponent {
  

  @ViewChild('shoes') shoesSelectionList: MatSelectionList;
  typesOfShoes: string[] = [    'marcado_planchas','cortado_planchas','plegado_planchas',
  'soldadura','prueba_conductos','armado_cuerpo','pintado','armado_accesorios'
];

selectedRights: string[] = ['marcado_planchas','cortado_planchas','plegado_planchas',];
   



selectionChange (change: MatSelectionListChange) {
  console.log(this.getSelected());
 // console.log(change.option.value, change.option.selected);
}

getSelected() {
  return this.shoesSelectionList.selectedOptions.selected.map(s => s.value);
}





}
