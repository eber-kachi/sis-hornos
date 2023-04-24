import { Injectable } from '@angular/core';
import {
    HttpRequest,
    HttpHandler,
    HttpEvent,
    HttpInterceptor,
} from '@angular/common/http';
import { Observable } from 'rxjs';
// import { AuthenticationService } from '../authentication/authentication.service';

@Injectable()
export class JwtInterceptor implements HttpInterceptor {
    //   constructor(private authenticationService: AuthenticationService) {}

    intercept(
        request: HttpRequest<unknown>,
        next: HttpHandler
    ): Observable<HttpEvent<unknown>> {
        // const token = this.authenticationService.accessToken;
        // if (this.authenticationService.isAuthenticated()) {
        //   request = request.clone({
        //     setHeaders: {
        //       Authorization: `Bearer ${token}`
        //     }
        //   });
        // }
        return next.handle(request);
    }
}
