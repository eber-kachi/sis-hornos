import { Component, Inject } from '@angular/core';
import { AbstractControl, FormBuilder, FormControl, FormGroup, Validators } from '@angular/forms';
import {
    MAT_DIALOG_DATA,
    MatDialog,
    MatDialogRef,
} from '@angular/material/dialog';
import { TipoGrupoService } from '@core/service/api/tipo-grupo.service';
import { PersonalService } from '@core/service/api/personal.service';
import { DepartamentoService } from '@core/service/departamento.service';
import { RolService } from '@core/service/rol.service';

@Component({
    selector: 'app-create-personal',
    templateUrl: './create-personal.component.html',
    styleUrls: ['./create-personal.component.scss'],
})
export class CreatePersonalComponent {
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

    roles: any[] = [];
    editing;
    constructor(
        private fb: FormBuilder,
        public dialog: MatDialogRef<CreatePersonalComponent>,
        @Inject(MAT_DIALOG_DATA) dataEdit: any,
        private tipoGrupoService: TipoGrupoService,
        private personalService: PersonalService,
        private rolService: RolService
    ) {
        this.listRols();
        console.log('data editing', dataEdit);
        this.editing = dataEdit;
        if (dataEdit != null) {
            this.personalService.getById(dataEdit.id).subscribe((res) => {
                console.log('editando =>',res.data);
                this.formGroup.patchValue({
                    'nombres': res.data.nombres,
                    'apellidos': res.data.apellidos,
                    'carnet_identidad': res.data.carnet_identidad,
                    'fecha_nacimiento': res.data.fecha_nacimiento,
                    'direccion': res.data.direccion,
                    'username': res.data.user.username,
                    'password': res.data?.password,
                    'rol_id': ''+res.data?.user.rol_id,
                });
            });
        }
    }

    public ngOnInit(): void {
        this.formGroup = this.fb.group({
            nombres: ['', [Validators.required , this.textValidator]],
            apellidos: ['', [Validators.required , this.textValidator]],
            carnet_identidad: ['', [Validators.required]],
            fecha_nacimiento: ['', [Validators.required, this.mayorDeEdad]],
            direccion: ['', [Validators.required]],
            rol_id: ['', [Validators.required]],
            username: [{ value: '', disabled: true }, [Validators.required]],
            password: ['megahornorojas'],
        });

        // this.formGroup.valueChanges.subscribe((res) => {
        //     console.log(res);
        // });

        // this.listPersonals();

        this.listTipoGrupos();


        this.formGroup
            .get('carnet_identidad')
            .valueChanges.subscribe((username) => {
                this.formGroup.get('username').patchValue(username);
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

    formChanged() {}

    listPersonals() {
        this.personalService.getAll().subscribe((res) => {
            // console.log(res);
            this.personales = res.data;
        });
    }

    listTipoGrupos() {
        this.tipoGrupoService.getAll().subscribe((res) => {
            // console.log(res);
            this.tipoGrupos = res.data;
            // this.personales=[];
        });
    }

    setDate(value: any) {
        // console.log(value);
    }

    listRols() {
        this.rolService.getAll().subscribe((res) => {
            // console.log(res);
            this.roles = res.data;
        });
    }

    // Esta función valida que el input solo contenga letras y espacios export 
 textValidator(control: FormControl) {
    let value = control.value; 
    let regex = /^[a-zA-Z\s]*$/; 
    // Expresión regular para letras y espacios 
    if (regex.test(value)) { return null;
        // El valor es válido 
       } else { return { text: true };
        // El valor no es válido 
       } }

// Esta función transforma el input en formato oración export function 
capitalize(value: string) { if (value) 
   { return value.replace(/\w\S*/g, (txt) => { return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase(); }); }
    return value; }

    mayorDeEdad (control: AbstractControl): {[key: string]: any} | null {
         // Obtener el valor del control como una fecha 
         let fecha = new Date (control.value); 
         // Obtener la fecha actual 
         let hoy = new Date (); 
         // Calcular la diferencia en milisegundos 
         let diferencia = hoy.getTime () - fecha.getTime (); 
         // Convertir la diferencia a años 
         let edad = Math.floor (diferencia / (1000 * 60 * 60 * 24 * 365));
          // Si la edad es menor de 18, devolver el objeto con el error 
          if (edad < 18) { return {menor: true}; }  
         {return null;}}
}
