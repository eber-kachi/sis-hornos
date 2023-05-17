import { EventInput } from '@fullcalendar/core';

let eventGuid = 0;
const TODAY_STR = new Date().toISOString().replace(/T.*$/, ''); // YYYY-MM-DD of today

export const INITIAL_EVENTS: EventInput[] = [
    {
        id: createEventId(),
        title: 'All-day event',
        start: TODAY_STR
    },
    {
        id: createEventId(),
        title: 'Timed event',
        start: TODAY_STR + 'T00:00:00',
        end: TODAY_STR + 'T03:00:00'
    },
    {
        id: createEventId(),
        title: 'Timed event',
        start: TODAY_STR + 'T12:00:00',
        end: TODAY_STR + 'T15:00:00'
    },
    {
        id: createEventId(),
        title: 'Eber kachi ',
        start: "2023-05-17" + 'T12:00:00',
        end: "2023-05-20" + 'T15:00:00',
        backgroundColor: '#378006'
    },
];

export function createEventId() {
    return String(eventGuid++);
}
