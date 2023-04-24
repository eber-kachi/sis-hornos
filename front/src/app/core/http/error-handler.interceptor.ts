import {
    HttpEvent,
    HttpHandler,
    HttpInterceptor,
    HttpRequest,
    HttpResponse,
} from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Router } from '@angular/router';
import { LocalStorageService } from '@app/core/local-storage.service';
import { ErrorMessageService } from '@app/core/service';
import { environment } from '@env/environment';
import { Observable } from 'rxjs';
import { catchError, map, tap } from 'rxjs/operators';
import { AlertSwallService } from '@core/service/alert-swall.service';

const credentialsKey = 'credentials';

/**
 * Adds a default error handler to all requests.
 */
@Injectable()
export class ErrorHandlerInterceptor implements HttpInterceptor {
    constructor(
        private router: Router,
        private localStorageService: LocalStorageService,
        private errorMessageService: ErrorMessageService,
        public alertSwal: AlertSwallService
    ) {}

    intercept(
        request: HttpRequest<any>,
        next: HttpHandler
    ): Observable<HttpEvent<any>> {
        return next.handle(request).pipe(
            // last(),
            // debounceTime(200),
            // pluck('body'),
            map((body: any) => {
                return body;
            }),
            // take(3),
            tap((x) => {
                if (x.status === 201 && x?.body?.message) {
                    // @ts-ignore
                    this.alertSwal.showSwallSuccess(x?.body?.message);
                }
            }),
            catchError((error) => this.errorHandler(error))
        );
    }

    // Customize the default error handler here if needed
    private errorHandler(
        response: HttpResponse<any>
    ): Observable<HttpEvent<any>> {
        if (response.status === 409) {
            // console.log(response);
            // @ts-ignore
            // this.alertSwal.showSwallError(
            //     // @ts-ignore
            //     response?.error?.errors[0] || response?.error?.message
            // );
        } else if (response.status === 401) {
            this.localStorageService.clearItem(credentialsKey);
            this.router.navigate(['/sign-in'], {
                replaceUrl: true,
            });
        } else if (response.status === 400) {
            const errorResponse: any = response;
            if (errorResponse.error) {
                if (errorResponse.error.validation) {
                    errorResponse.error.validation.keys.forEach(
                        (key: string) => {
                            this.errorMessageService.set(
                                errorResponse.error.validation.errors[key],
                                key,
                                response.url
                            );
                        }
                    );
                } else {
                    this.errorMessageService.set(
                        errorResponse.error.error,
                        '_GLOBAL_',
                        response.url
                    );
                }
            }
        } else {
            //@ts-ignore
            this.alertSwal.showSwallError(
                // @ts-ignore
                response?.error?.message ||
                    // @ts-ignore
                    response?.message ||
                    // @ts-ignore
                    response?.error?.errors[0]
            );
        }

        if (!environment.production) {
            // Do something with the error
            console.error('Request error', response);
        }
        throw response;
    }
}
