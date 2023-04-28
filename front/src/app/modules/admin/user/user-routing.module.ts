import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import {ListPersonalComponent} from "@app/modules/admin/user/list-personal/list-personal.component";

const routes: Routes = [
    {
        path:'lista-personal',
        component:ListPersonalComponent
    }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class UserRoutingModule { }
