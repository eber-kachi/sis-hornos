import {Component, Inject} from '@angular/core';
import {FormBuilder, FormGroup, Validators} from "@angular/forms";
import {MAT_DIALOG_DATA, MatDialogRef} from "@angular/material/dialog";
import {DepartamentoService} from "@core/service/api/departamento.service";
import {RolService} from "@core/service/rol.service";

@Component({
  selector: 'app-create-rol',
  templateUrl: './create-rol.component.html',
  styleUrls: ['./create-rol.component.scss']
})
export class CreateRolComponent {
    public formGroup: FormGroup;
    editing;
    constructor(
        private fb: FormBuilder,
        public dialog: MatDialogRef<CreateRolComponent>,
        @Inject(MAT_DIALOG_DATA) private dataEdit: any,
        private materialService: RolService,
    ) {
        this.editing = dataEdit;
        if (dataEdit != null) {
            this.materialService.getById(dataEdit.id).subscribe((res) => {
                this.formGroup.patchValue(res.data);
            });
        }
    }

    public ngOnInit(): void {
        this.formGroup = this.fb.group({
            display_name: ['', [Validators.required]],
            // name: ['', [Validators.required]],

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
