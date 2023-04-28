import { Component, Inject, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import {MAT_DIALOG_DATA, MatDialog, MatDialogRef} from '@angular/material/dialog';
import { GrupoTrabajoService } from '@core/service/api/grupo-trabajo.service';
import { PersonalService } from '@core/service/api/personal.service';
import { TipoGrupoService } from '@core/service/api/tipo-grupo.service';
interface Website {
    value: string;
    viewValue: string;
}

interface Personal {
    id:string|number;
    nombre_completo:string,
}

@Component({
    selector: 'app-create-grupo',
    templateUrl: './create-grupo.component.html',
    styleUrls: ['./create-grupo.component.scss'],
})
export class CreateGrupoComponent implements OnInit {
    public formGroup: FormGroup;

    personales: Personal[] = [];
    tipoGrupos: any[] = [];
    editing;
    constructor(
        private fb: FormBuilder,
        public dialog: MatDialogRef<CreateGrupoComponent>,
        @Inject(MAT_DIALOG_DATA) dataEdit: any,
        private grupoTrabajoService: GrupoTrabajoService,
        private tipoGrupoService: TipoGrupoService,
        private personalService: PersonalService
    ) {
        console.log('data editing', dataEdit);
        this.editing = dataEdit;
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

        this.listPersonals();

        this.listTipoGrupos();
    }

    closeDialog() {
        this.dialog.close(false);
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


        if (this.editing != null) {
            this.grupoTrabajoService
                .update(this.editing.id, this.formGroup.getRawValue())
                .subscribe(
                    (res) => {
                        console.log(res);
                        this.formGroup.enable();
                        this.dialog.close(true);
                    },
                    (error) => {
                        this.formGroup.enable();
                    }
                );
        } else {
            this.grupoTrabajoService.create(this.formGroup.getRawValue()).subscribe(
                (res) => {
                    console.log(res);
                    this.formGroup.enable();
                    this.dialog.close(true);
                },
                (error) => {
                    this.formGroup.enable();
                }
            );
        }
    }

    formChanged() {}

    listPersonals() {
        this.personalService.getAll().subscribe((res) => {
            console.log(res);
            this.personales = res.data.map(personal=>{
                return {id:personal.id, nombre_completo: `${personal.nombres} ${personal.apellidos}`};
            });
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
