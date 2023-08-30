import { Injectable } from '@angular/core';
import {BaseAPIClass} from "@app/core";
import {HttpClient} from "@angular/common/http";
import {map} from "rxjs/operators";
import { Observable } from 'rxjs';

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

  // Añade este método
   updateEstado(id: number, estado: string): Observable <any> { 
    // Construye la URL del pedido con el id 
    const url = `${this.baseUrl}/${id}`; 
    // Crea el objeto con el nuevo estado

     const data ={ ["estado"]: estado }; 
     console.log (data);
    // Retorna el observable de la petición HTTP PUT 
    return this.httpClient.put(url, data); }

  
}
