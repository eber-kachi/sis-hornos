import { Component, ViewChild } from '@angular/core';
import { MatSelectionList, MatSelectionListChange } from '@angular/material/list';
import { ActivatedRoute } from '@angular/router';
import { ProcesoService } from '@app/core/service/api/proceso.service';

@Component({
  selector: 'app-show-procesos',
  templateUrl: './show-procesos.component.html',
  styleUrls: ['./show-procesos.component.scss']
})
export class ShowProcesosComponent {
  

  @ViewChild('shoes') shoesSelectionList: MatSelectionList;
  AsignacionId: string;
  typesOfShoes: string[] = [    'marcado_planchas','cortado_planchas','plegado_planchas',
  'soldadura','prueba_conductos','armado_cuerpo','pintado','armado_accesorios'
];

selectedRights: string[] = [];
   
constructor( private procesoService: ProcesoService,    private route: ActivatedRoute,){
  this.AsignacionId= this.route.snapshot.paramMap.get('id');
  // Llamar al método getById del servicio de Proceso
  this.procesoService.getById(this.AsignacionId).subscribe(
    // Manejar la respuesta exitosa
    response => {
      console.log(response);
      // Asignar el valor de selectedRights del modelo Proceso al array selectedRights del componente
      this.selectedRights = response.data.selectedRights;
    },
    // Manejar el error
    error => {
      console.error(error);
      // Mostrar un mensaje de error al usuario
    }
  );
}

selectionChange (change: MatSelectionListChange) {
  // Obtener el array de opciones seleccionadas
  let options = change.options;
  // Recorrer el array con el método map
  let values = options.map(option => option.value);
  // Llamar al método update del servicio de Proceso con el array de valores
  this.procesoService.update(this.AsignacionId, values).subscribe(
    // Manejar la respuesta exitosa
    response => {
      console.log(response);
      // Hacer lo que quieras con la respuesta
    },
    // Manejar el error
    error => {
      console.error(error);
      // Mostrar un mensaje de error al usuario
    }
  );
}


getSelected() {


  return this.shoesSelectionList.selectedOptions.selected.map(s => s.value);
 
}

onSave(){
  
  this.procesoService.getById(this.AsignacionId);



} 

}