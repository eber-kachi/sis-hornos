import {Component, OnInit} from '@angular/core';
import {MaterialService} from "@core/service/api/material.service";
import {MatDialog} from "@angular/material/dialog";
import {CreateMaterialComponent} from "@app/modules/admin/producto/list-material/create-material/create-material.component";
import {AlertDialogComponent} from "@app/shared/alert-dialog/alert-dialog.component";
import {CreateProductoComponent} from "@app/modules/admin/producto/list-producto/create-producto/create-producto.component";
import {ProductoService} from "@core/service/api/producto.service";

@Component({
  selector: 'app-list-producto',
  templateUrl: './list-producto.component.html',
  styleUrls: ['./list-producto.component.scss']
})
export class ListProductoComponent implements OnInit {
    displayedColumns: string[] = [
        'id',
        'nombre',
        'caracteristicas',
        'precio_unitario',
        'costo',
        'actions',
    ];
    dataSource = [];
    constructor(
        private materialService: ProductoService,
        public dialog: MatDialog
    ) {}
    ngOnInit(): void {
        // this.usuario$ = this.usuarioService.getAll();

        this.list();
    }

    list() {
        this.materialService.getAll().subscribe((res) => {
            console.log(res);
            this.dataSource = res.data;
        });
    }

    edit(id: string | number | ArrayBufferView | ArrayBuffer) {
        console.log(id);
        const dialogRef = this.dialog.open(CreateProductoComponent, {
            width: '640px',
            disableClose: true,
            data: { id: id },
        });
        dialogRef.afterClosed().subscribe((res) => {
            console.log('edit list', res);
            if (res) {
                this.list();
            }
        });
    }

    delete(id: string | number | ArrayBufferView | ArrayBuffer) {
        const dialogRef = this.dialog.open(AlertDialogComponent, {
            data: {
                message: 'Estas seguro que seas eliminar?',
                buttonText: {
                    ok: 'Si',
                    cancel: 'Cancelar',
                },
            },
        });
        dialogRef.afterClosed().subscribe((confirmed: boolean) => {
            if (confirmed) {
                console.log('borrando');
                this.materialService.delete(id as string).subscribe((res) => {
                    console.log(res);
                    this.list();
                });
            }
        });

        console.log(id);
    }

    createNew() {
        const dialogRef = this.dialog.open(CreateProductoComponent, {
            width: '640px',
            disableClose: true,
        });
        dialogRef.afterClosed().subscribe((res) => {
            console.log('edit list', res);
            if (res) {
                this.list();
            }
        });
    }

}
