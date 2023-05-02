import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { UserRoutingModule } from './user-routing.module';
import { ListUserComponent } from './list-user/list-user.component';
import { CreateUserComponent } from './list-user/create-user/create-user.component';
import { ListPersonalComponent } from './list-personal/list-personal.component';
import { CreatePersonalComponent } from './list-personal/create-personal/create-personal.component';
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
import {MatDatepickerModule} from "@angular/material/datepicker";
import {MatNativeDateModule} from "@angular/material/core";
import { ListRolComponent } from './list-rol/list-rol.component';
import { CreateRolComponent } from './list-rol/create-rol/create-rol.component';


@NgModule({
  declarations: [
    ListUserComponent,
    CreateUserComponent,
    ListPersonalComponent,
    CreatePersonalComponent,
    ListRolComponent,
    CreateRolComponent
  ],
  imports: [
    CommonModule,
    UserRoutingModule,
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
      MatDatepickerModule,
      MatNativeDateModule,
  ]
})
export class UserModule { }
