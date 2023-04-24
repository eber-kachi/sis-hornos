import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { finalize, map } from 'rxjs/operators';

@Injectable()
export abstract class BaseAPIClass {
  baseUrl: string;
  public showLoader = false;

  constructor(protected httpClient: HttpClient) {}

  getAll(filterObject?: any): Observable<any> {
    let queryString = '';
    if (filterObject) {
      const fitlerKeys: any[] = Object.keys(filterObject);
      if (fitlerKeys.length > 0) {
        queryString = '?';
      }
      fitlerKeys.forEach((key: any, index) => {
        if (filterObject[key] !== undefined && filterObject[key] !== null) {
          if (filterObject[key].toString().length) {
            queryString += `${key}=${filterObject[key]}&`;
          }
        }
      });
      if (fitlerKeys.length > 0 && queryString[queryString.length - 1] === '&') {
        queryString = queryString.slice(0, -1);
      }
    }
    return this.httpClient.get(`${this.baseUrl}${queryString}`).pipe(
      map((body: any) => {
        return body;
      })
    );
  }

  getById(id: string, filterObject?: any): Observable<any> {
    let queryString = '';
    if (filterObject) {
      const fitlerKeys: any[] = Object.keys(filterObject);
      if (fitlerKeys.length > 0) {
        queryString = '?';
      }
      fitlerKeys.forEach((key: any, index) => {
        if (filterObject[key] !== undefined && filterObject[key] !== null) {
          if (filterObject[key].toString().length) {
            queryString += `${key}=${filterObject[key]}&`;
          }
        }
      });
      if (fitlerKeys.length > 0 && queryString[queryString.length - 1] === '&') {
        queryString = queryString.slice(0, -1);
      }
    }
    return this.httpClient.get(`${this.baseUrl}/${id}${queryString}`).pipe(
      map((body: any) => {
        return body;
      })
    );
  }

  create(payload: any): Observable<any> {
    this.showLoader = true;
    return this.httpClient.post(this.baseUrl, payload).pipe(
      finalize(() => (this.showLoader = false)),
      map((body: any) => {
        return body;
      })
    );
  }

  updateFD(id: string, payload: FormData): Observable<any> {
    payload.append('_method', 'PUT');
    return this.httpClient.post(`${this.baseUrl}/${id}`, payload).pipe(
      map((body: any) => {
        return body;
      })
    );
  }

  update(id: string, payload: any): Observable<any> {
    this.showLoader = true;
    return this.httpClient.put(`${this.baseUrl}/${id}`, payload).pipe(
      map((body: any) => {
        return body;
      })
    );
  }

  delete(id: string): Observable<any> {
    return this.httpClient.delete(`${this.baseUrl}/${id}`).pipe(
      map((body: any) => {
        return body;
      })
    );
  }

  deleteAll(): Observable<any> {
    return this.httpClient.delete(`${this.baseUrl}/all`).pipe(
      map((body: any) => {
        return body;
      })
    );
  }

  enabled(id: any) {
    return this.httpClient.get(`${this.baseUrl}/enabled/${id}`).pipe(
      map((body: any) => {
        return body;
      })
    );
  }

  getAllEnabled() {
    return this.httpClient.get(`${this.baseUrl}/all/enabled`).pipe(
      map((body: any) => {
        return body;
      })
    );
  }
}
