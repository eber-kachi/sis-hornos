import { Injectable } from '@angular/core';
import {BaseAPIClass} from "@app/core";
import {HttpClient} from "@angular/common/http";

@Injectable({
  providedIn: 'root'
})
export class PedidoService extends BaseAPIClass {
    constructor(protected httpClient: HttpClient) {
        super(httpClient);
        this.baseUrl = '/pedidos';
    }
}
