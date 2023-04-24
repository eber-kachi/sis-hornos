import { Injectable } from '@angular/core';
import { BaseAPIClass } from '@app/core';
import { HttpClient } from '@angular/common/http';
import { Observable, throwError } from 'rxjs';
import { catchError } from 'rxjs/operators';

@Injectable({
  providedIn: 'root'
})
export class ArchivoService extends BaseAPIClass {
  constructor(protected httpClient: HttpClient) {
    super(httpClient);
    this.baseUrl = '/archivos';
  }

  upload(newFormdata: FormData): Observable<any> {
    return this.httpClient
      .post(this.baseUrl, newFormdata, { observe: 'events', reportProgress: true })
      .pipe(catchError(err => this.handleError(err)));
  }

  private handleError(error: any) {
    return throwError(error);
  }

  download(id: any) {
    return this.httpClient.get(this.baseUrl + `/`);
  }
}
