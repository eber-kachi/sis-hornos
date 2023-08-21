import { Injectable } from '@angular/core';
import { BaseAPIClass } from '@app/core';
import { HttpClient } from '@angular/common/http';
import { map } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class AsignacionService extends BaseAPIClass {
  public showLoader = false;

  constructor(protected httpClient: HttpClient) {
    super(httpClient);
    this.baseUrl = '/asignacion';
  }

getAsignacionByIdLote(lote_id:any){
    return this.httpClient.get(`${this.baseUrl}/lote/${lote_id}`).pipe(
      map((body: any) => {
        return body;
      })
    );
  }
  download(){
    // Asignaciones-productos/lote/2?descargar=true
  }


  getAsignacionByIdProducto(productoId: string) {
    return this.httpClient.get(`${this.baseUrl}/lista/${productoId}`).pipe(
      map((body: any) => {
        return body;
      })
    );
  }
}
