import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';

import {CronogramaRoutingModule} from './cronograma-routing.module';
import {ShowCronogramaComponent} from './show-cronograma/show-cronograma.component';
import {FullCalendarModule} from "@fullcalendar/angular";


@NgModule({
    declarations: [
        ShowCronogramaComponent
    ],
    imports: [
        CommonModule,
        FullCalendarModule,
        CronogramaRoutingModule
    ]
})
export class CronogramaModule {
}
