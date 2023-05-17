import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {ShowCronogramaComponent} from "@app/modules/admin/cronograma/show-cronograma/show-cronograma.component";

const routes: Routes = [
    {
        path: 'cronograma',
        component: ShowCronogramaComponent
    },
];

@NgModule({
    imports: [RouterModule.forChild(routes)],
    exports: [RouterModule]
})
export class CronogramaRoutingModule {
}
