import {Component, Inject, OnInit} from '@angular/core';
import {FormArray, FormBuilder, FormGroup, Validators} from '@angular/forms';
import {MAT_DIALOG_DATA, MatDialog, MatDialogRef} from '@angular/material/dialog';
import {GrupoTrabajoService} from '@core/service/api/grupo-trabajo.service';
import {PersonalService} from '@core/service/api/personal.service';
import {TipoGrupoService} from '@core/service/api/tipo-grupo.service';
import {ProductoService} from "@core/service/api/producto.service";

interface Website {
    value: string;
    viewValue: string;
}

interface Personal {
    id: string | number;
    nombre_completo: string,
}

@Component({
    selector: 'app-create-grupo',
    templateUrl: './create-grupo.component.html',
    styleUrls: ['./create-grupo.component.scss'],
})
export class CreateGrupoComponent implements OnInit {
    public formGroup: FormGroup;

    personales: Personal[] = [];
    productos: any[] = [];
    tipoGrupos: any[] = [];
    editing;
    total_produccion_diarias = 0;
    jefes: any[] = [];

    constructor(
        private fb: FormBuilder,
        public dialog: MatDialogRef<CreateGrupoComponent>,
        @Inject(MAT_DIALOG_DATA) dataEdit: any,
        private grupoTrabajoService: GrupoTrabajoService,
        private tipoGrupoService: TipoGrupoService,
        private personalService: PersonalService,
        private productoService: ProductoService,
    ) {
        console.log('data editing', dataEdit);
        this.editing = dataEdit;
        if (dataEdit != null) {
            this.grupoTrabajoService.getById(dataEdit.id).subscribe((res) => {
                console.log(res.data);
                let personales: Personal[] = res.data.personales.map(p => {
                    return {id: p.id, nombre_completo: `${p.nombres} ${p.apellidos}`}
                });

                personales.forEach(p => {
                    this.personales.push(p);
                });

                this.formGroup.patchValue(res.data.grupo_trabajo);
                this.formGroup.patchValue({'personales': personales.map(p => p.id)});
            });
        }
    }

    public ngOnInit(): void {
        this.formGroup = this.fb.group({
            nombre: ['', [Validators.required]],
            // tipo_grupo_id: ['', [Validators.required]],
            ayudantes: ['', [Validators.required]],
            jefe_id: ['', [Validators.required]],
            productos_id: ['', [Validators.required]],
            produccion_diarias: this.fb.array([])
        });

        this.formGroup.valueChanges.subscribe((res) => {
            console.log(res);
        });
        if (this.editing != null) {
            this.listPersonals();
        } else {
            this.listPersonals();
        }
        this.AddMaterial();
        // this.listTipoGrupos();
        this.listProductos();
        this.listjefes();
    }

    get materiales(): FormArray {
        return this.formGroup.get('produccion_diarias') as FormArray;
    }

    newMaterial() {
        return this.fb.group({
            nombre: ['Muestra', [Validators.required]],
            cantidad: ['', [Validators.required]],
        });
    }

    AddMaterial() {
        this.materiales.push(this.newMaterial());
    }

    public removeMaterial(index) {
        console.log("delete");

        this.materiales.removeAt(index)
    }

    closeDialog() {
        this.dialog.close(false);
    }

    onSave() {
        if (this.formGroup.invalid) {
            return;
        }
        this.formGroup.disable();

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
            const producion: any[] = this.materiales.value;
            const va = producion.map((p, index) => {
                return {...p, nombre: p.nombre + index};
            });

            this.grupoTrabajoService.create({...this.formGroup.getRawValue(), produccion_diarias: va}).subscribe(
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

    listPersonals() {
        this.personalService.getAllNoJefes().subscribe((res) => {
            console.log(res);
            res.data.forEach(personal => {
                this.personales.push({id: personal.id, nombre_completo: `${personal.nombres} ${personal.apellidos}`});
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

    private listProductos() {
        this.productoService.getAll().subscribe((res) => {
            this.productos = res.data;
        });
    }

    private listjefes() {
        this.personalService.getAllJefes().subscribe((res) => {
            this.jefes = res.data;
        });
    }
}
