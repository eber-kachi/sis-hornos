import {Component, Inject} from '@angular/core';
import {FormBuilder, FormGroup, Validators} from "@angular/forms";
import {MAT_DIALOG_DATA, MatDialogRef} from "@angular/material/dialog";
import {PedidoService} from "@core/service/api/pedido.service";
import {MedidaService} from "@core/service/api/medida.service";
import {ClienteService} from "@core/service/api/cliente.service";
import {ProductoService} from "@core/service/api/producto.service";
import {switchMap, tap} from "rxjs/operators";
import {of} from "rxjs";
import {LoteProduccionService} from "@core/service/api/lote-produccion.service";

@Component({
  selector: 'app-create-lote',
  templateUrl: './create-lote.component.html',
  styleUrls: ['./create-lote.component.scss']
})
export class CreateLoteComponent {
  public formGroup: FormGroup;
  editing;

  medidas: any[] = [];
  clientes: any[] = [];
  productos: any[] = [];
  pedidos: any[] = [];

  constructor(
    private fb: FormBuilder,
    public dialog: MatDialogRef<CreateLoteComponent>,
    @Inject(MAT_DIALOG_DATA) private dataEdit: any,
    private pedidoService: PedidoService,
    private medidaService: MedidaService,
    private loteProduccionService: LoteProduccionService,
    private productoService: ProductoService
  ) {
    this.editing = dataEdit;
    console.log('data editing', this.editing);
    if (dataEdit != null) {
      this.pedidoService.getById(dataEdit.id).subscribe((res) => {
        console.log(res.data);
        this.formGroup.patchValue(res.data);
      });
    }
  }

  public ngOnInit(): void {
    this.formGroup = this.fb.group({
      // cantidad: ['', [Validators.required]],
      producto_id: ['', [Validators.required]],
      pedidos: ['', [Validators.required]],
    });

    this.listMedidas();

    //listar productos y clientes
    this.listClientes();
    this.listProductos();

    this.formGroup.get('producto_id')
      .valueChanges
      .pipe(
        tap(value=>{
        console.log('producto id ',value)

      }),
    switchMap((producto_id: any) => {
      console.log('switch', producto_id)
      return this.pedidoService.getAllActive(producto_id);
    }))
      .subscribe((pedido)=>{
        console.log('asas', pedido.data)
        this.pedidos= pedido.data;
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
    // debugger;
    // console.log(this.formGroup.getRawValue());
    const pedidos:any[] =this.formGroup.get('pedidos').value;
   const sendPedidos= pedidos.map(p => {
      return this.pedidos.find(pe=> pe.id==p )
    });
    // console.log(sendPedidos)

    if (this.editing != null) {
      this.loteProduccionService
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
      this.loteProduccionService.create({...this.formGroup.getRawValue(), pedidos: sendPedidos})
        .subscribe((res) => {
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
    this.loteProduccionService.getAll().subscribe((res) => {
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
