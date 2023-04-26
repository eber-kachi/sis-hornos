import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import {ListTipoGrupoComponent} from "@app/modules/admin/grupo/list-tipo-grupo/list-tipo-grupo.component";
import {ListGrupoComponent} from "@app/modules/admin/grupo/list-grupo/list-grupo.component";
import {ListMaterialComponent} from "@app/modules/admin/producto/list-material/list-material.component";
import {ListProductoComponent} from "@app/modules/admin/producto/list-producto/list-producto.component";

const routes: Routes = [
    {
        path: 'lista-material',
        component: ListMaterialComponent,
    },
    {
        path: 'lista-producto',
        component: ListProductoComponent,
    },
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class ProductoRoutingModule { }
