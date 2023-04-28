import { Component, Inject } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import {
    MAT_DIALOG_DATA,
    MatDialog,
    MatDialogRef,
} from '@angular/material/dialog';
import { TipoGrupoService } from '@core/service/api/tipo-grupo.service';
import { PersonalService } from '@core/service/api/personal.service';
import { DepartamentoService } from '@core/service/departamento.service';
import { MaterialService } from '@core/service/api/material.service';

@Component({
    selector: 'app-create-material',
    templateUrl: './create-material.component.html',
    styleUrls: ['./create-material.component.scss'],
})
export class CreateMaterialComponent {
    public formGroup: FormGroup;
    editing;
    constructor(
        private fb: FormBuilder,
        public dialog: MatDialogRef<CreateMaterialComponent>,
        @Inject(MAT_DIALOG_DATA) private dataEdit: any,
        private materialService: MaterialService,
    ) {
        this.editing = dataEdit;
        console.log('data editing', this.editing);
        if (dataEdit != null) {
            this.materialService.getById(dataEdit.id).subscribe((res) => {
                console.log(res.data);
                this.formGroup.patchValue(res.data);
            });
        }
    }

    public ngOnInit(): void {
        this.formGroup = this.fb.group({
            nombre: ['', [Validators.required]],
            kg: ['', [Validators.required]],
            largo: ['', [Validators.required]],
            ancho: ['', [Validators.required]],
            cm: ['', [Validators.required]],
            cm2: ['', [Validators.required]],
            // username: [{value: '', disabled: true}, [Validators.required]],
            // password: ['megahornoroja', [Validators.required]],
        });
    }

    closeDialog() {
        this.dialog.close(false);
    }

    onSave() {
        if (this.formGroup.invalid) {
            return;
        }
        this.formGroup.disable();
        console.log(this.formGroup.getRawValue());

        if (this.editing != null) {
            this.materialService
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
            this.materialService.create(this.formGroup.getRawValue()).subscribe(
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
}
