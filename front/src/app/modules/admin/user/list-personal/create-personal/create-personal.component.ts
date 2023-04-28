import {Component, Inject} from '@angular/core';
import {FormBuilder, FormGroup, Validators} from "@angular/forms";
import {MAT_DIALOG_DATA, MatDialog, MatDialogRef} from "@angular/material/dialog";
import {TipoGrupoService} from "@core/service/api/tipo-grupo.service";
import {PersonalService} from "@core/service/api/personal.service";
import {DepartamentoService} from "@core/service/departamento.service";
import {RolService} from "@core/service/rol.service";

@Component({
    selector: 'app-create-personal',
    templateUrl: './create-personal.component.html',
    styleUrls: ['./create-personal.component.scss']
})
export class CreatePersonalComponent {
    public formGroup: FormGroup;

    personales: any[] = [
        {id: '1', nombre: 'ItSolutionStuff.com'},
        {id: '2', nombre: 'HDTuto.com'},
        {id: '3', nombre: 'Nicesnippets.com'},
        {id: '4', nombre: 'laravel.com'},
        {id: '5', nombre: 'npm.com'},
        {id: '6', nombre: 'google.com'},
    ];
    tipoGrupos: any[] = [];

    roles:any[]=[];
    editing;
    constructor(
        private fb: FormBuilder,
        public dialog: MatDialogRef<CreatePersonalComponent>,
        @Inject(MAT_DIALOG_DATA) dataEdit: any,
        private tipoGrupoService: TipoGrupoService,
        private personalService: PersonalService,
        private rolService: RolService,
    ) {
        console.log('data editing', dataEdit);
        this.editing = dataEdit;
        if (dataEdit != null) {
            this.personalService.getById(dataEdit.id).subscribe((res) => {
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
            direccion: ['', [Validators.required]],
            rol_id: ['', [Validators.required]],
            username: [{value: '', disabled: true}, [Validators.required]],
            password: ['megahornoroja', [Validators.required]],
        });

        this.formGroup.valueChanges.subscribe((res) => {
            console.log(res);
        });

        // this.listPersonals();

        this.listTipoGrupos();
        this.listRols();

        this.formGroup.get('carnet_identidad').valueChanges.subscribe(username => {
            this.formGroup.get('username').patchValue(username);
        })
    }

    closeDialog() {
        this.dialog.close(false);
    }

    onSave() {
        if (this.formGroup.invalid) {
            return;
        }
        this.formGroup.disable();
        console.log(this.formGroup.getRawValue())

        if (this.editing != null) {
            this.personalService
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
            this.personalService.create(this.formGroup.getRawValue()).subscribe(
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
    //     this.personalService.getAll().subscribe((res) => {
    //         console.log(res);
    //         this.personales = [];
    //     });
    // }

    listTipoGrupos() {
        this.tipoGrupoService.getAll().subscribe((res) => {
            console.log(res);
            this.tipoGrupos = res.data;
            // this.personales=[];
        });
    }

    setDate(value: any) {
        console.log(value)
    }

    listRols(){
        this.rolService.getAll().subscribe(res=>{
            console.log(res)

            this.roles=res.data;
        })
    }
}
