import { Component, Inject } from '@angular/core';
import { FormArray, FormBuilder, FormGroup, Validators } from '@angular/forms';
import {
  MAT_DIALOG_DATA,
  MatDialog,
  MatDialogRef,
} from '@angular/material/dialog';
import { ClienteService } from '@app/core/service/api/cliente.service';
import { PedidoService } from '@app/core/service/api/pedido.service';
import { ProductoService } from '@app/core/service/api/producto.service';
import { MaterialService } from '@core/service/api/material.service';
import { MedidaService } from '@core/service/api/medida.service';

@Component({
  selector: 'app-create-pedido',
  templateUrl: './create-pedido.component.html',
  styleUrls: ['./create-pedido.component.scss'],
})
export class CreatePedidoComponent {
  public formGroup: FormGroup;
  editing;

  medidas: any[] = [];
  clientes: any[] = [];
  productos: any[] = [];

  constructor(
    private fb: FormBuilder,
    public dialog: MatDialogRef<CreatePedidoComponent>,
    @Inject(MAT_DIALOG_DATA) private dataEdit: any,
    private materialService: PedidoService,
    private medidaService: MedidaService,
    private clienteService: ClienteService,
    private productoService: ProductoService
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
      cantidad: ['', [Validators.required]],
      producto_id: ['', [Validators.required]],
      cliente_id: ['', [Validators.required]],
    });

    this.listMedidas();

    //listar productos y clientes
    this.listClientes();
    this.listProductos();
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

  listMedidas() {
    this.medidaService.getAll().subscribe((res) => {
      console.log(res);
      this.medidas = res.data;

      if (this.editing != null) {
      } else {
        this.formGroup.patchValue({ medida_id: 1 });
      }
    });
  }

  listClientes() {
    this.clienteService.getAll().subscribe((res) => {
      console.log(res);
      this.clientes = res.data;
    });
  }

  listProductos() {
    this.productoService.getAll().subscribe((res) => {
      console.log(res);
      this.productos = res.data;
    });
  }
}
