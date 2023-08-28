import { NgModule } from '@angular/core';
import { RouterModule } from '@angular/router';
import { MatButtonModule } from '@angular/material/button';
import { FuseCardModule } from '@fuse/components/card';
import { SharedModule } from 'app/shared/shared.module';
import { AuthForgotPasswordComponent } from 'app/modules/auth/forgot-password/forgot-password.component';
import { authForgotPasswordRoutes } from 'app/modules/auth/forgot-password/forgot-password.routing';


@NgModule({
    declarations: [
        AuthForgotPasswordComponent
    ],
    imports     : [
        RouterModule.forChild(authForgotPasswordRoutes),
        MatButtonModule,
        FuseCardModule,
        SharedModule
        
    ]
})
export class AuthForgotPasswordModule
{
}
