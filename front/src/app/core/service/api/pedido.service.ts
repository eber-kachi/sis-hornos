import { Injectable } from '@angular/core';
import {BaseAPIClass} from "@app/core";
import {HttpClient} from "@angular/common/http";
import {map} from "rxjs/operators";

@Injectable({
  providedIn: 'root'
})
export class PedidoService extends BaseAPIClass {
    constructor(protected httpClient: HttpClient) {
        super(httpClient);
        this.baseUrl = '/pedidos';
    }


  getAllActive(producto_id: any) {
    return this.httpClient.get(`${this.baseUrl}/lista/activos/${producto_id}`).pipe(
      map((body: any) => {
        return body;
      })
    );
  }
}
