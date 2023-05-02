import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import {ListPersonalComponent} from "@app/modules/admin/user/list-personal/list-personal.component";
import {ListRolComponent} from "@app/modules/admin/user/list-rol/list-rol.component";

const routes: Routes = [
    {
        path:'lista-personal',
        component:ListPersonalComponent
    },
    {
        path:'lista-rol',
        component:ListRolComponent
    }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class UserRoutingModule { }
