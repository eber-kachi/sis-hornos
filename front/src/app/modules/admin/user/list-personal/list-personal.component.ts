import { Component, OnInit } from '@angular/core';
import { PersonalService } from '@core/service/api/personal.service';
import { CreateGrupoComponent } from '@app/modules/admin/grupo/list-grupo/create-grupo/create-grupo.component';
import { AlertDialogComponent } from '@app/shared/alert-dialog/alert-dialog.component';
import { MatDialog } from '@angular/material/dialog';
import { CreatePersonalComponent } from '@app/modules/admin/user/list-personal/create-personal/create-personal.component';

@Component({
    selector: 'app-list-personal',
    templateUrl: './list-personal.component.html',
    styleUrls: ['./list-personal.component.scss'],
})
export class ListPersonalComponent implements OnInit {
    displayedColumns: string[] = [
        'id',
        'nombres',
        'carnet_identidad',
        'rol_nombre',
        'grupo_trabajo_nombre',
        // 'personales',
        // 'grupo',
        'actions',
    ];
    dataSource = [];
    constructor(
        private personalService: PersonalService,
        public dialog: MatDialog
    ) {}
    ngOnInit(): void {
        // this.usuario$ = this.usuarioService.getAll();

        this.list();
    }

    list() {
        this.personalService.getAll().subscribe((res) => {
            console.log(res);
            this.dataSource = res.data;
        });
    }

    edit(id: string | number | ArrayBufferView | ArrayBuffer) {
        console.log(id);
        const dialogRef = this.dialog.open(CreatePersonalComponent, {
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
                    cancel: 'Canselar',
                },
            },
        });
        dialogRef.afterClosed().subscribe((confirmed: boolean) => {
            if (confirmed) {
                console.log('borrando');
                this.personalService.delete(id as string).subscribe((res) => {
                    console.log(res);
                    this.list();
                });
            }
        });

        console.log(id);
    }

    createNew() {
        const dialogRef = this.dialog.open(CreatePersonalComponent, {
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
