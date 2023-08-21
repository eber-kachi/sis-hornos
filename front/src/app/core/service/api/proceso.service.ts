import { Injectable } from '@angular/core';
import { BaseAPIClass } from '@app/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class ProcesoService extends BaseAPIClass {
  public showLoader = false;

  constructor(protected httpClient: HttpClient) {
    super(httpClient);
    this.baseUrl = '/procesos';
  }

   // Definir el método update que recibe el id y el array de valores del proceso
update(id: string, values: string[]): Observable<any> {
  // Crear el objeto data con el array de selectedRights
  let data = {
    selectedRights: values
  };
  // Hacer la petición PUT al backend con el id y el data
  return this.httpClient.put(`${this.baseUrl}/${id}`, data);
}


  // Definir el método getById que recibe el id del proceso
  getById(id: string): Observable<any> {
    // Hacer la petición GET al backend con el id
    return this.httpClient.get(`${this.baseUrl}/${id}`);
  }
}
