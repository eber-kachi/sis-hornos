import { Injectable } from '@angular/core';
import { BaseAPIClass } from '@app/core';
import { HttpClient } from '@angular/common/http';
import {map} from "rxjs/operators";

@Injectable({
    providedIn: 'root',
})
export class PersonalService extends BaseAPIClass {
    constructor(protected httpClient: HttpClient) {
        super(httpClient);
        this.baseUrl = '/personal';
    }

    sinGrupo() {
        return this.httpClient.get(`${this.baseUrl}/sin-grupo`).pipe(
            map((body: any) => {
                return body;
            })
        );
    }

    getAllJefes() {
        return this.httpClient.get(`${this.baseUrl}/lista/jefe`).pipe(
            map((body: any) => {
                return body;
            })
        );
    }
    getAllNoJefes() {
        return this.httpClient.get(`${this.baseUrl}/lista/nojefe`).pipe(
            map((body: any) => {
                return body;
            })
        );
    }
}
