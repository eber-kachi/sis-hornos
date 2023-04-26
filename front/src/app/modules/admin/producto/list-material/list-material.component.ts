import { Component, OnInit } from '@angular/core';
import { MatDialog } from '@angular/material/dialog';
import { AlertDialogComponent } from '@app/shared/alert-dialog/alert-dialog.component';
import { CreateMaterialComponent } from '@app/modules/admin/producto/list-material/create-material/create-material.component';
import { MaterialService } from '@core/service/api/material.service';

@Component({
    selector: 'app-list-material',
    templateUrl: './list-material.component.html',
    styleUrls: ['./list-material.component.scss'],
})
export class ListMaterialComponent implements OnInit {
    displayedColumns: string[] = [
        'id',
        'nombre',
        'kg',
        'largo',
        'ancho',
        'cm',
        'cm2',
        'actions',
    ];
    dataSource = [];
    constructor(
        private materialService: MaterialService,
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
        const dialogRef = this.dialog.open(CreateMaterialComponent, {
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
        const dialogRef = this.dialog.open(CreateMaterialComponent, {
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
