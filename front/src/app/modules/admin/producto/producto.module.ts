import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';

import {ProductoRoutingModule} from './producto-routing.module';
import {ListProductoComponent} from './list-producto/list-producto.component';
import {ListMaterialComponent} from './list-material/list-material.component';
import {CreateMaterialComponent} from './list-material/create-material/create-material.component';
import {CreateProductoComponent} from './list-producto/create-producto/create-producto.component';
import {MatTableModule} from "@angular/material/table";
import {MatButtonModule} from "@angular/material/button";
import {MatIconModule} from "@angular/material/icon";
import {MatDialogModule} from "@angular/material/dialog";
import {MatCardModule} from "@angular/material/card";
import {MatFormFieldModule} from "@angular/material/form-field";
import {FormsModule, ReactiveFormsModule} from "@angular/forms";
import {MatSelectModule} from "@angular/material/select";
import {MatRadioModule} from "@angular/material/radio";
import {MatGridListModule} from "@angular/material/grid-list";
import {MatInputModule} from "@angular/material/input";
import { ListDepartamentoComponent } from './list-departamento/list-departamento.component';
import { CreateDepartamentoComponent } from './list-departamento/create-departamento/create-departamento.component';
import { ListClienteComponent } from './list-cliente/list-cliente.component';
import { CreateClienteComponent } from './list-cliente/create-cliente/create-cliente.component';
import { ListPedidoComponent } from './list-pedido/list-pedido.component';
import {MatPaginatorModule} from "@angular/material/paginator";
import { CreatePedidoComponent } from './list-pedido/create-pedido/create-pedido.component';
import { ListLoteComponent } from './list-lote/list-lote.component';
import { CreateLoteComponent } from './list-lote/create-lote/create-lote.component';
import { ShowMaterialComponent } from './list-lote/show-material/show-material.component';
import {ShowMaterialComponent as ShowMaterialProductoComponent} from "@app/modules/admin/producto/list-producto/show-material/show-material.component";
import { ListProcesoComponent } from './list-proceso/list-proceso.component';
import { ListAsignacionComponent } from './list-asignacion/list-asignacion.component';
import { ListAsignacionLoteComponent } from './list-asignacion-lote/list-asignacion-lote.component';


@NgModule({
    declarations: [
        ListProductoComponent,
        ListMaterialComponent,
        CreateMaterialComponent,
        CreateProductoComponent,
        ListDepartamentoComponent,
        CreateDepartamentoComponent,
        ListClienteComponent,
        CreateClienteComponent,
        ListPedidoComponent,
        CreatePedidoComponent,
        ListLoteComponent,
        CreateLoteComponent,
        ShowMaterialComponent,
        ShowMaterialProductoComponent,
        ListProcesoComponent,
        ListAsignacionComponent,
        ListAsignacionLoteComponent
    ],
    imports: [
        CommonModule,
        ProductoRoutingModule,

        MatTableModule,
        MatButtonModule,
        MatIconModule,
        MatDialogModule,
        MatCardModule,
        MatFormFieldModule,
        FormsModule,
        ReactiveFormsModule,
        MatSelectModule,
        MatRadioModule,
        MatGridListModule,
        // MatFormFieldControl,
        MatInputModule,
        MatPaginatorModule
    ]
})
export class ProductoModule {
}
