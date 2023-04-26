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


@NgModule({
    declarations: [
        ListProductoComponent,
        ListMaterialComponent,
        CreateMaterialComponent,
        CreateProductoComponent
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
    ]
})
export class ProductoModule {
}
