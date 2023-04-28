import {Component, Inject} from '@angular/core';
import {FormBuilder, FormGroup, Validators} from "@angular/forms";
import {MAT_DIALOG_DATA, MatDialogRef} from "@angular/material/dialog";
import {MaterialService} from "@core/service/api/material.service";
import {ProductoService} from "@core/service/api/producto.service";

@Component({
  selector: 'app-create-producto',
  templateUrl: './create-producto.component.html',
  styleUrls: ['./create-producto.component.scss']
})
export class CreateProductoComponent {
    public formGroup: FormGroup;
    editing;
    constructor(
        private fb: FormBuilder,
        public dialog: MatDialogRef<CreateProductoComponent>,
        @Inject(MAT_DIALOG_DATA) private dataEdit: any,
        private materialService: ProductoService,
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
    // 'caracteristicas',
    // 'precio_unitario',
    // 'costo',
    public ngOnInit(): void {
        this.formGroup = this.fb.group({
            nombre: ['', [Validators.required]],
            caracteristicas: ['', []],
            precio_unitario: ['', [Validators.required]],
            costo: ['', [Validators.required]],
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
