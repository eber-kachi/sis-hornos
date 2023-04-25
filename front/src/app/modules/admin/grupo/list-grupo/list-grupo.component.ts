import { Component, Inject } from '@angular/core';
import { TipoGrupoService } from '@core/service/api/tipo-grupo.service';
import { GrupoTrabajoService } from '@core/service/api/grupo-trabajo.service';
import { CreateGrupoComponent } from '@app/modules/admin/grupo/list-grupo/create-grupo/create-grupo.component';
import { MAT_DIALOG_DATA, MatDialog } from '@angular/material/dialog';
import { AlertSwallService } from '@core/service/alert-swall.service';
import { AlertDialogComponent } from '@app/shared/alert-dialog/alert-dialog.component';

@Component({
    selector: 'app-list-grupo',
    templateUrl: './list-grupo.component.html',
    styleUrls: ['./list-grupo.component.scss'],
})
export class ListGrupoComponent {
    displayedColumns: string[] = [
        'id',
        'nombre',
        'cantidad_integrantes',
        'actions',
    ];
    dataSource = [];

    constructor(
        private grupoTrabajoService: GrupoTrabajoService,
        public dialog: MatDialog
    ) {}

    ngOnInit(): void {
        this.list();
    }

    list() {
        this.grupoTrabajoService.getAll().subscribe((res) => {
            console.log(res);
            this.dataSource = res.data;
        });
    }

    edit(id: string | number | ArrayBufferView | ArrayBuffer) {
        console.log(id);
        const dialogRef = this.dialog.open(CreateGrupoComponent, {
            width: '640px',
            disableClose: true,
            data: { id: id },
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
                this.grupoTrabajoService
                    .delete(id as string)
                    .subscribe((res) => {
                        console.log(res);
                        this.list();
                    });
            }
        });

        console.log(id);
    }

    createNew() {
        const dialogRef = this.dialog.open(CreateGrupoComponent, {
            width: '640px',
            disableClose: true,
        });
    }
}
