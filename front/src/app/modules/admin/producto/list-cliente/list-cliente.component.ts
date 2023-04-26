import { Component } from '@angular/core';
import {MaterialService} from "@core/service/api/material.service";
import {MatDialog} from "@angular/material/dialog";
import {AlertDialogComponent} from "@app/shared/alert-dialog/alert-dialog.component";
import {CreateClienteComponent} from "@app/modules/admin/producto/list-cliente/create-cliente/create-cliente.component";

@Component({
  selector: 'app-list-cliente',
  templateUrl: './list-cliente.component.html',
  styleUrls: ['./list-cliente.component.scss']
})
export class ListClienteComponent {
    displayedColumns: string[] = [
        'id',
        'nombre',
        'carnet_identidad',
        'celular',
        'provincia',
        'actions',
    ];
    dataSource = [];
    constructor(
        private materialService: MaterialService,
        public dialog: MatDialog
    ) {}
    ngOnInit(): void {

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
        const dialogRef = this.dialog.open(CreateClienteComponent, {
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
        const dialogRef = this.dialog.open(CreateClienteComponent, {
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
