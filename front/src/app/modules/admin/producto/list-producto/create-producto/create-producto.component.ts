import { Component, Inject } from '@angular/core';
import {
  FormArray,
  FormBuilder,
  FormControl,
  FormGroup,
  Validators,
} from '@angular/forms';
import { MAT_DIALOG_DATA, MatDialogRef } from '@angular/material/dialog';
import { MaterialService } from '@core/service/api/material.service';
import { ProductoService } from '@core/service/api/producto.service';

@Component({
  selector: 'app-create-producto',
  templateUrl: './create-producto.component.html',
  styleUrls: ['./create-producto.component.scss'],
})
export class CreateProductoComponent {
  public formGroup: FormGroup;
  editing;

  formArray: FormArray;

  displayedColumns: string[] = [
    // 'id',
    'material_id',
    'cantidad',
    'descripcion',
    'actions',
  ];

  materialesData: any[] = [];

  constructor(
    private fb: FormBuilder,
    public dialog: MatDialogRef<CreateProductoComponent>,
    @Inject(MAT_DIALOG_DATA) private dataEdit: any,
    private productoService: ProductoService,
    private materialService: MaterialService
  ) {
    this.editing = dataEdit;
    console.log('data editing', this.editing);
    if (dataEdit != null) {
      this.productoService.getById(dataEdit.id).subscribe((res) => {
        console.log(res.data);
        this.formGroup.patchValue(res.data);
        this.materiales.clear();
        const ma = res.data.materiales.map((m) => {
          console.log(m);
          this.AddMaterial();
          return {
            material_id: m.id,
            cantidad: m.cantidad,
            descripcion: m.descripcion,
          };
        });
        this.formGroup.get('materiales').patchValue(ma);
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
      materiales: this.fb.array([]),
    });

    this.listMateriales();
    this.AddMaterial();

    this.materiales.valueChanges.subscribe((res) => {
      console.log('materiales =>', res);
    });
  }

  get materiales(): FormArray {
    return this.formGroup.get('materiales') as FormArray;
  }

  // On user change I clear the title of that album
  onUserChange(event, album: FormGroup) {
    // const title = album.get('title');
    //
    // title.setValue(null);
    // title.markAsUntouched();
    // Notice the ngIf at the title cell definition. The user with id 3 can't set the title of the albums
  }

  newMaterial() {
    return this.fb.group({
      material_id: ['', [Validators.required]],
      cantidad: ['', [Validators.required]],
      descripcion: [''],
    });
    // return material as FormControl;
  }

  AddMaterial() {
    console.log('add');
    this.materiales.push(this.newMaterial());
    this.formArray = this.materiales;
  }

  public removeMaterial(index) {
    console.log('delete');

    this.materiales.removeAt(index);
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
      this.productoService
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
      this.productoService.create(this.formGroup.getRawValue()).subscribe(
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

  listMateriales() {
    this.materialService.getAll().subscribe((res) => {
      this.materialesData = res.data;
    });
  }

  // Esta función transforma el input en formato oración export function 
capitalize(value: string) { if (value) 
  { return value.replace(/\w\S*/g, (txt) => { return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase(); }); }
   return value; }

}
