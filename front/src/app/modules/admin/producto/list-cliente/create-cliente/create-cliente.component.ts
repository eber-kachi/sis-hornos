import {Component, Inject} from '@angular/core';
import {FormBuilder, FormGroup, Validators} from "@angular/forms";
import {MAT_DIALOG_DATA, MatDialog, MatDialogRef} from "@angular/material/dialog";
import {TipoGrupoService} from "@core/service/api/tipo-grupo.service";
import {PersonalService} from "@core/service/api/personal.service";
import {DepartamentoService} from "@core/service/departamento.service";
import {ClienteService} from "@core/service/api/cliente.service";

@Component({
  selector: 'app-create-cliente',
  templateUrl: './create-cliente.component.html',
  styleUrls: ['./create-cliente.component.scss']
})
export class CreateClienteComponent {
    public formGroup: FormGroup;

    tipoGrupos: any[] = [];

    departamento:any[]=[];
    editing;
    constructor(
        private fb: FormBuilder,
        public dialog: MatDialogRef<CreateClienteComponent>,
        @Inject(MAT_DIALOG_DATA) dataEdit: any,
        private tipoGrupoService: TipoGrupoService,
        private clienteService: ClienteService,
        private departamentoService: DepartamentoService,
    ) {
        console.log('data editing', dataEdit);
        this.editing = dataEdit;
        if (dataEdit != null) {
            this.clienteService.getById(dataEdit.id).subscribe((res) => {
                console.log(res.data);
                this.formGroup.patchValue(res.data);
            });
        }
    }

    public ngOnInit(): void {
        this.formGroup = this.fb.group({
            nombres: ['', [Validators.required]],
            apellidos: ['', [Validators.required]],
            carnet_identidad: ['', [Validators.required]],
            fecha_nacimiento: ['', [Validators.required]],
            celular: ['', [Validators.required]],
            provincia: ['', [Validators.required]],
            departamento_id: ['', [Validators.required]],

        });

        this.formGroup.valueChanges.subscribe((res) => {
            console.log(res);
        });

        this.listDepartamneto();


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
            this.clienteService
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
            this.clienteService.create(this.formGroup.getRawValue()).subscribe(
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

    // listPersonals() {
    //     this.clienteService.getAll().subscribe((res) => {
    //         console.log(res);
    //         this.personales = [];
    //     });
    // }

    setDate(value: any) {
        console.log(value)
    }

    listDepartamneto(){
        this.departamentoService.getAll().subscribe(res=>{
            console.log('departamentos',res)

            this.departamento=res.data;
        })
    }
}
