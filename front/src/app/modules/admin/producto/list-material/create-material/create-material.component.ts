import {Component, Inject} from '@angular/core';
import {FormArray, FormBuilder, FormGroup, Validators} from '@angular/forms';
import {
    MAT_DIALOG_DATA,
    MatDialog,
    MatDialogRef,
} from '@angular/material/dialog';
import {TipoGrupoService} from '@core/service/api/tipo-grupo.service';
import {PersonalService} from '@core/service/api/personal.service';
import {DepartamentoService} from '@core/service/departamento.service';
import {MaterialService} from '@core/service/api/material.service';
import {MedidaService} from "@core/service/api/medida.service";

@Component({
    selector: 'app-create-material',
    templateUrl: './create-material.component.html',
    styleUrls: ['./create-material.component.scss'],
})
export class CreateMaterialComponent {
    public formGroup: FormGroup;
    editing;

    medidas: any[] = [];
    constructor(
        private fb: FormBuilder,
        public dialog: MatDialogRef<CreateMaterialComponent>,
        @Inject(MAT_DIALOG_DATA) private dataEdit: any,
        private materialService: MaterialService,
        private medidaService: MedidaService,
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
            medida_id:['',[Validators.required]],
            caracteristica: ['', [Validators.required]],
            // largo: ['', [Validators.required]],
            // ancho: ['', [Validators.required]],
            // cm: ['', [Validators.required]],
            // cm2: ['', [Validators.required]],
            // username: [{value: '', disabled: true}, [Validators.required]],
            // password: ['megahornoroja', [Validators.required]],
        });


        this.listMedidas();
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

    formChanged() {
    }

    listMedidas() {
        this.medidaService.getAll().subscribe(res => {
            console.log(res);
            this.medidas=res.data;

            if (this.editing != null) {
            }else{
                this.formGroup.patchValue({medida_id: 1 })

            }
        })
    }
}
