import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { ListTipoGrupoComponent } from '@app/modules/admin/grupo/list-tipo-grupo/list-tipo-grupo.component';
import { ListGrupoComponent } from '@app/modules/admin/grupo/list-grupo/list-grupo.component';

const routes: Routes = [
    {
        path: 'lista-tipo-grupo',
        component: ListTipoGrupoComponent,
    },
    {
        path: 'lista-grupo',
        component: ListGrupoComponent,
    },
];

@NgModule({
    imports: [RouterModule.forChild(routes)],
    exports: [RouterModule],
})
export class GrupoRoutingModule {}
