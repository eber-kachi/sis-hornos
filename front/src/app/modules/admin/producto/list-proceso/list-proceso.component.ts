import { Component } from '@angular/core';

@Component({
  selector: 'app-list-proceso',
  templateUrl: './list-proceso.component.html',
  styleUrls: ['./list-proceso.component.scss']
})
export class ListProcesoComponent {

  displayedColumns = [
        'marcado_planchas',
        'cortado_planchas',
        'plegado_planchas',
        'soldadura',
        'prueba_conductos',
        'armado_cuerpo',
        'pintado',
        'armado_accesorios',
  ];

 

}
