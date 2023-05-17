import {Component, ChangeDetectorRef} from '@angular/core';
import {CalendarOptions, DateSelectArg, EventClickArg, EventApi} from '@fullcalendar/core';
import interactionPlugin from '@fullcalendar/interaction';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';
import {INITIAL_EVENTS, createEventId} from './event-utils';
import esLocale from '@fullcalendar/core/locales/es';
import {GrupoTrabajoService} from "@core/service/api/grupo-trabajo.service";
import {LoteProduccionService} from "@core/service/api/lote-produccion.service";

@Component({
    selector: 'app-show-cronograma',
    templateUrl: './show-cronograma.component.html',
    styleUrls: ['./show-cronograma.component.scss']
})
export class ShowCronogramaComponent {
    currentEvents: EventApi[] = [];
    calendarVisible = true;
    // events: any[] = [];

    calendarOptions: CalendarOptions = {
        locale: esLocale,
        plugins: [
            interactionPlugin,
            dayGridPlugin,
            timeGridPlugin,
            listPlugin,
        ],
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
        },
        initialView: 'dayGridMonth',
        // initialEvents: INITIAL_EVENTS, // alternatively, use the `events` setting to fetch from a feed
        weekends: true,
        editable: true,
        selectable: true,
        selectMirror: true,
        dayMaxEvents: true,
        select: this.handleDateSelect.bind(this),
        eventClick: this.handleEventClick.bind(this),
        eventsSet: this.handleEvents.bind(this),
        // dateClick: this.dateClik(this),
        /* you can update a remote database when these fire:
        eventAdd:
        eventChange:
        eventRemove:
        */
    };


    constructor(
        private changeDetector: ChangeDetectorRef,
        private loteProduccionService: LoteProduccionService,
    ) {
    }

    ngOnInit(): void {
        this.list();
    }

    list() {
        this.loteProduccionService.getAll().subscribe((res) => {
            console.log(res);
            // this.dataSource = res.data;
            const arrays: EventApi[] = res.data.map(lote => {
                return {
                    id: '' + lote.id,
                    title: 'Lote ' + lote.id,
                    start: lote?.fecha_inicio + 'T08:00:00',
                    // start: lote?.fecha_inicio + 'T12:00:00',
                    end: lote?.fecha_final + 'T18:00:00',
                    backgroundColor: (lote.estado == 'Inactivo') ? '#999999' : lote.color
                };
            });
            this.currentEvents = arrays;
            this.calendarOptions.events = this.currentEvents;
            // this.currentEvents = arrays;
            // this.handleEvents(array);
            this.changeDetector.detectChanges();
            console.log(this.currentEvents);
            console.log(INITIAL_EVENTS)

        });
    }

    handleCalendarToggle() {
        this.calendarVisible = !this.calendarVisible;
    }

    handleWeekendsToggle() {
        const {calendarOptions} = this;
        calendarOptions.weekends = !calendarOptions.weekends;
    }

    handleDateSelect(selectInfo: DateSelectArg) {
        console.log(selectInfo)
        const title = prompt('Please enter a new title for your event');
        const calendarApi = selectInfo.view.calendar;

        calendarApi.unselect(); // clear date selection

        if (title) {
            calendarApi.addEvent({
                id: createEventId(),
                title,
                start: selectInfo.startStr,
                end: selectInfo.endStr,
                allDay: selectInfo.allDay
            });
        }
    }

    handleEventClick(clickInfo: EventClickArg) {
        if (confirm(`Are you sure you want to delete the event '${clickInfo.event.title}'`)) {
            clickInfo.event.remove();
        }
    }

    handleEvents(events: EventApi[]) {
        this.currentEvents = events;
        this.changeDetector.detectChanges();
    }


    dateClik(e:any){
        console.log(e)
    }
}
