import {Component, OnInit} from '@angular/core';
import {DepartamentoService} from "@core/service/api/departamento.service";
import {MatDialog} from "@angular/material/dialog";
import {CreateDepartamentoComponent} from "@app/modules/admin/producto/list-departamento/create-departamento/create-departamento.component";
import {AlertDialogComponent} from "@app/shared/alert-dialog/alert-dialog.component";
import {RolService} from "@core/service/rol.service";
import {CreateRolComponent} from "@app/modules/admin/user/list-rol/create-rol/create-rol.component";

@Component({
  selector: 'app-list-rol',
  templateUrl: './list-rol.component.html',
  styleUrls: ['./list-rol.component.scss']
})
export class ListRolComponent implements OnInit {
    displayedColumns: string[] = [
        'id',
        // 'name',
        'display_name',
        'enabled',
        'actions',
    ];
    dataSource = [];
    constructor(
        private departamantoService: RolService,
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
        const dialogRef = this.dialog.open(CreateRolComponent, {
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
        const dialogRef = this.dialog.open(CreateRolComponent, {
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
