import { Component, Inject, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { MAT_DIALOG_DATA, MatDialog } from '@angular/material/dialog';
import { GrupoTrabajoService } from '@core/service/api/grupo-trabajo.service';
import { PersonalService } from '@core/service/api/personal.service';
import { TipoGrupoService } from '@core/service/api/tipo-grupo.service';
interface Website {
    value: string;
    viewValue: string;
}

@Component({
    selector: 'app-create-grupo',
    templateUrl: './create-grupo.component.html',
    styleUrls: ['./create-grupo.component.scss'],
})
export class CreateGrupoComponent implements OnInit {
    public formGroup: FormGroup;

    personales: any[] = [
        { id: '1', nombre: 'ItSolutionStuff.com' },
        { id: '2', nombre: 'HDTuto.com' },
        { id: '3', nombre: 'Nicesnippets.com' },
        { id: '4', nombre: 'laravel.com' },
        { id: '5', nombre: 'npm.com' },
        { id: '6', nombre: 'google.com' },
    ];
    tipoGrupos: any[] = [];

    constructor(
        private fb: FormBuilder,
        public dialog: MatDialog,
        @Inject(MAT_DIALOG_DATA) dataEdit: any,
        private grupoTrabajoService: GrupoTrabajoService,
        private tipoGrupoService: TipoGrupoService,
        private personalService: PersonalService
    ) {
        console.log('data editing', dataEdit);
        if (dataEdit != null) {
            this.grupoTrabajoService.getById(dataEdit.id).subscribe((res) => {
                console.log(res.data);
                this.formGroup.patchValue(res.data);
            });
        }
    }

    public ngOnInit(): void {
        this.formGroup = this.fb.group({
            nombre: ['', [Validators.required]],
            tipo_grupo_id: ['', [Validators.required]],
            website: '',
            personales: '',
        });

        this.formGroup.valueChanges.subscribe((res) => {
            console.log(res);
        });

        // this.listPersonals();

        this.listTipoGrupos();
    }

    closeDialog() {
        this.dialog.closeAll();
    }

    onSave() {
        if (this.formGroup.invalid) {
            return;
        }
        this.formGroup.disable();

        this.grupoTrabajoService
            .create(this.formGroup.value)
            .subscribe((res) => {
                console.log(res);
                this.formGroup.enable();
            });
    }

    formChanged() {}

    listPersonals() {
        this.personalService.getAll().subscribe((res) => {
            console.log(res);
            this.personales = [];
        });
    }

    listTipoGrupos() {
        this.tipoGrupoService.getAll().subscribe((res) => {
            console.log(res);
            this.tipoGrupos = res.data;
            // this.personales=[];
        });
    }
}
