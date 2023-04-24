import { NgModule, Optional, SkipSelf } from '@angular/core';
import { AuthModule } from 'app/core/auth/auth.module';
import { IconsModule } from 'app/core/icons/icons.module';
import { TranslocoCoreModule } from 'app/core/transloco/transloco.module';
import {
    HTTP_INTERCEPTORS,
    HttpClient,
    HttpClientModule,
} from '@angular/common/http';
import {
    ApiPrefixInterceptor,
    ErrorHandlerInterceptor,
    HttpService,
} from '@app/core/http';

import { LocalStorageService } from '@app/core/local-storage.service';
import {
    ErrorMessageService,
    UserService,
    UtilService,
} from '@app/core/service';
// import { JwtInterceptor } from '@core/http/jwt.interceptor';
@NgModule({
    imports: [AuthModule, IconsModule, TranslocoCoreModule],
    providers: [
        LocalStorageService,
        // AuthenticationService,
        // AuthenticationGuard,
        ApiPrefixInterceptor,
        ErrorHandlerInterceptor,
        UserService,
        UtilService,
        ErrorMessageService,
        {
            provide: HTTP_INTERCEPTORS,
            useClass: ApiPrefixInterceptor,
            multi: true,
        },
        {
            provide: HttpClient,
            useClass: HttpService,
        },
    ],
})
export class CoreModule {
    /**
     * Constructor
     */
    constructor(@Optional() @SkipSelf() parentModule?: CoreModule) {
        // Do not allow multiple injections
        if (parentModule) {
            throw new Error(
                'CoreModule has already been loaded. Import this module in the AppModule only.'
            );
        }
    }
}
