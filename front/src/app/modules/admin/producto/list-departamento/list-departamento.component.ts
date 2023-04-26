import {Component, OnInit} from '@angular/core';
import {MatDialog} from "@angular/material/dialog";
import {CreateMaterialComponent} from "@app/modules/admin/producto/list-material/create-material/create-material.component";
import {AlertDialogComponent} from "@app/shared/alert-dialog/alert-dialog.component";
import {DepartamentoService} from "@core/service/api/departamento.service";
import {CreateDepartamentoComponent} from "@app/modules/admin/producto/list-departamento/create-departamento/create-departamento.component";

@Component({
  selector: 'app-list-departamento',
  templateUrl: './list-departamento.component.html',
  styleUrls: ['./list-departamento.component.scss']
})
export class ListDepartamentoComponent implements OnInit {
    displayedColumns: string[] = [
        'id',
        'nombre',
        'actions',
    ];
    dataSource = [];
    constructor(
        private departamantoService: DepartamentoService,
        public dialog: MatDialog
    ) {}
    ngOnInit(): void {
        // this.usuario$ = this.usuarioService.getAll();

        this.list();
    }

    list() {
        this.departamantoService.getAll().subscribe((res) => {
            console.log(res);
            this.dataSource = res.data;
        });
    }

    edit(id: string | number | ArrayBufferView | ArrayBuffer) {
        console.log(id);
        const dialogRef = this.dialog.open(CreateDepartamentoComponent, {
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
                this.departamantoService.delete(id as string).subscribe((res) => {
                    console.log(res);
                    this.list();
                });
            }
        });

        console.log(id);
    }

    createNew() {
        const dialogRef = this.dialog.open(CreateDepartamentoComponent, {
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
