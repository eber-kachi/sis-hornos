import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { BaseAPIClass } from '@app/core';

@Injectable({
    providedIn: 'root',
})
export class TipoGrupoService extends BaseAPIClass {
    constructor(protected httpClient: HttpClient) {
        super(httpClient);
        this.baseUrl = '/tipo_grupos';
    }
}
