import {
    HttpEvent,
    HttpHandler,
    HttpInterceptor,
    HttpRequest,
} from '@angular/common/http';
import { Injectable } from '@angular/core';
import { environment } from '@env/environment';
import { Observable } from 'rxjs';

/**
 * Prefixes all requests with `environment.serverUrl`.
 */
@Injectable()
export class ApiPrefixInterceptor implements HttpInterceptor {
    constructor() {}

    intercept(
        request: HttpRequest<any>,
        next: HttpHandler
    ): Observable<HttpEvent<any>> {
        if (request.url.indexOf('web') !== -1) {
            // console.log('existe web');
            request = request.clone({
                url: request.url,
            });
        } else {
            request = request.clone({
                url: environment.serverUrl + request.url,
            });
        }
        return next.handle(request);
    }
}
