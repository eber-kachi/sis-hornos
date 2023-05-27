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
  ayudantes: Personal[] = [];
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
      this.grupoTrabajoService.getById(dataEdit.id)
        .subscribe((res) => {

          console.log(res.data);
          console.log(res.data.nombre);

          let personales: Personal[] = res.data.ayudantes.map(p => {
              return {id: p.id, nombre_completo: `${p.nombres} ${p.apellidos}`}
          });


          personales.forEach(p => {
              this.ayudantes.push(p);
          });

          // let jefes: Personal[] = res.data.jefe.map(p => {
          //   return {id: p.id, nombre_completo: `${p.nombres} ${p.apellidos}`}
          // });
          //
          // debugger
          // jefes.forEach(p => {
          this.jefes.push(res.data.jefe);
          // });


          // personales.forEach(p => {
          //   this.personales.push(p);
          // });
          const ayudantes = res.data.ayudantes.map(a => a.id);

          this.formGroup.patchValue({
            nombre: res.data.nombre,
            jefe_id: res.data.jefe.id,
            ayudantes: ayudantes,
            productos_id: res.data.producto.id,
            produccion_diarias: ''
          });

          // this.formGroup.patchValue({'personales': personales.map(p => p.id)});
        });
    }
  }

  /**
   * {
    "id": 2,
    "jefe": [
        {
            "id": 2,
            "nombres": "Carlos Ferbando",
            "apellidos": "Calle Rocha",
            "carnet_identidad": "12461-23",
            "fecha_nacimiento": "1987-12-12",
            "direccion": "San Martin 342",
            "fecha_registro": "2023-05-12",
            "id_grupo_trabajo": 2,
            "user_id": 2,
            "created_at": null,
            "updated_at": null,
            "name": "Carlos",
            "username": "12461-23",
            "email": "carlos@gmail.com",
            "email_verified_at": null,
            "password": "$2y$10$TeZWv98xnted1eNyBelsROT/J/I5.YudZ9cHSadx5kIXjLVW934ku",
            "enabled": 1,
            "rol_id": 6,
            "remember_token": "b5V356brOz"
        }
    ],
    "ayudantes": [
        {
            "id": 5,
            "nombres": "Franco",
            "apellidos": "Mamani Rocha",
            "carnet_identidad": "12461-23",
            "fecha_nacimiento": "1987-12-12",
            "direccion": "San Martin 342",
            "fecha_registro": "2023-05-12",
            "id_grupo_trabajo": 2,
            "user_id": 5,
            "created_at": null,
            "updated_at": null,
            "name": "Fernando",
            "username": "12461-23",
            "email": "fernando@gmail.com",
            "email_verified_at": null,
            "password": "$2y$10$qIxGpQaDzvCAryCLYiHAyuOgMQFAur8hYNjwBQ1G6d.vrtTKQ6ci6",
            "enabled": 1,
            "rol_id": 8,
            "remember_token": "JH5nF99GNq"
        }
    ],
    "nombre": "grupo bueno",
    "cantidad_integrantes": 2,
    "tipo_grupo_id": 2,
    "tipo_grupo_nombre": "Grupo2Horno Grande"
}
   *
   */

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
        this.ayudantes.push({id: personal.id, nombre_completo: `${personal.nombres} ${personal.apellidos}`});
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
      res.data.forEach(jefe => {
        this.jefes.push(jefe)
      })
      // this.jefes = res.data;
    });
  }
}
