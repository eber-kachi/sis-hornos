import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { GrupoRoutingModule } from './grupo-routing.module';
import { ListTipoGrupoComponent } from './list-tipo-grupo/list-tipo-grupo.component';
import { MatTableModule } from '@angular/material/table';
import { MatButtonModule } from '@angular/material/button';
import { ListGrupoComponent } from './list-grupo/list-grupo.component';
import { MatIconModule } from '@angular/material/icon';
import { CreateGrupoComponent } from './list-grupo/create-grupo/create-grupo.component';
import { MatDialogModule } from '@angular/material/dialog';
import { MatCardModule } from '@angular/material/card';
import {
    MatFormFieldControl,
    MatFormFieldModule,
} from '@angular/material/form-field';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { MatSelectModule } from '@angular/material/select';
import { MatRadioModule } from '@angular/material/radio';
import { MatGridListModule } from '@angular/material/grid-list';
import { MatInputModule } from '@angular/material/input';
// import {MatFormFieldModule} from '@angular/material/form-field';

@NgModule({
    declarations: [
        ListTipoGrupoComponent,
        ListGrupoComponent,
        CreateGrupoComponent,
    ],
    imports: [
        CommonModule,
        GrupoRoutingModule,
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
    ],
    entryComponents: [CreateGrupoComponent],
})
export class GrupoModule {}
