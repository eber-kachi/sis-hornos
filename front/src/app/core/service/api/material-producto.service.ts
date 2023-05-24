import { Injectable } from '@angular/core';
import {BaseAPIClass} from "@app/core";
import {HttpClient} from "@angular/common/http";
import {map} from "rxjs/operators";

@Injectable({
  providedIn: 'root'
})
export class MaterialProductoService extends BaseAPIClass {
  constructor(protected httpClient: HttpClient) {
    super(httpClient);
    this.baseUrl = '/materiales_productos';
  }


  getMaterialByIdLote(lote_id:any){
    return this.httpClient.get(`${this.baseUrl}/lote/${lote_id}`).pipe(
      map((body: any) => {
        return body;
      })
    );
  }
  download(){
    // materiales-productos/lote/2?descargar=true
  }


  getMaterialByIdProducto(productoId: string) {
    return this.httpClient.get(`${this.baseUrl}/lista/${productoId}`).pipe(
      map((body: any) => {
        return body;
      })
    );
  }
}
