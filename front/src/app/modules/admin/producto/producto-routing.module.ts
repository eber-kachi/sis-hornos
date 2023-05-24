import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import {ListTipoGrupoComponent} from "@app/modules/admin/grupo/list-tipo-grupo/list-tipo-grupo.component";
import {ListGrupoComponent} from "@app/modules/admin/grupo/list-grupo/list-grupo.component";
import {ListMaterialComponent} from "@app/modules/admin/producto/list-material/list-material.component";
import {ListProductoComponent} from "@app/modules/admin/producto/list-producto/list-producto.component";
import {ListDepartamentoComponent} from "@app/modules/admin/producto/list-departamento/list-departamento.component";
import {ListClienteComponent} from "@app/modules/admin/producto/list-cliente/list-cliente.component";
import {ListPedidoComponent} from "@app/modules/admin/producto/list-pedido/list-pedido.component";
import {ListLoteComponent} from "@app/modules/admin/producto/list-lote/list-lote.component";
import {ShowMaterialComponent} from "@app/modules/admin/producto/list-lote/show-material/show-material.component";
import {ShowMaterialComponent as ShowMaterialProductoComponent} from "@app/modules/admin/producto/list-producto/show-material/show-material.component";

const routes: Routes = [
    {
        path: 'lista-material',
        component: ListMaterialComponent,
    },
    {
        path: 'lista-producto',
        component: ListProductoComponent,
    },
    {
      path: 'lista-producto/:id/materiales',
      component: ShowMaterialProductoComponent,
    },
    {
        path: 'lista-departamento',
        component: ListDepartamentoComponent,
    },
    {
        path: 'lista-cliente',
        component: ListClienteComponent,
    },
    {
        path: 'lista-pedidos',
        component: ListPedidoComponent,
    },
    {
      path: 'lista-lote',
      component: ListLoteComponent,
    },
    {
      path: 'lista-lote/:id/materiales',
      component: ShowMaterialComponent,
    },
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class ProductoRoutingModule { }
