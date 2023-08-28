import {
  Input,
  ElementRef,
  ViewContainerRef,
  TemplateRef,
} from '@angular/core';
// import * as jwt_decode from 'jwt-decode';
import { Directive } from '@angular/core';
// import { AuthenticationService } from '@app/core';
import { Subscription } from 'rxjs';
import {AuthService} from "@core/auth/auth.service";
import {UserService} from "@core/user/user.service";

@Directive({
  // tslint:disable-next-line:directive-selector
  selector: '[hasPermission]',
})
export class HasPermissionDirective {
  //  private currentUser;
  private permissions: any[];
  private isHidden = true;
  username: string;
  // user: User;
  currentUser: any;

  userRoles: any;

  currentUserSubscription: Subscription;

  constructor(
    private element: ElementRef,
    private templateRef: TemplateRef<any>,
    private authenticationService: AuthService,
    private userService: UserService,
    private viewContainer: ViewContainerRef
  ) {}

  /// ngOnInit() {
  // this.updateView();
  // }

  @Input() set hasPermission(val: any) {
    this.permissions = Array.isArray(val) ? val : [];
    // console.log(val);
    this.updateView();
  }

  private updateView() {
    // console.log(this.permissions);
    if (this.checkPermission()) {
      if (this.isHidden) {
        this.viewContainer.createEmbeddedView(this.templateRef);
        this.isHidden = false;
      }
    } else {
      this.isHidden = true;
      this.viewContainer.clear();
    }
  }

  private checkPermission() {
    let hasPermission = false;

    if (this.authenticationService.check()) {
      // console.log(route.data.roles);
      const match = this.roleMatch(this.permissions);
      if (match) {
        hasPermission = true;
      }
    }

    return hasPermission;
  }

  roleMatch(allowedRoles: any): boolean {
    // this.currentUser = this.authenticationService.credentials;
    // // this.user = this.currentUser;
    // this.userRoles = this.currentUser.roles;
    this.userService.role$.subscribe(res=>{
      // console.log(res)
      this.userRoles= [res]
    })

    let isMatch = false;
    const userRoles = this.userRoles;
    allowedRoles.forEach((element: any) => {
      const allowed = userRoles.filter((a: any) => a.name === element);
      if (allowed.length > 0) {
        isMatch = true;
        return false;
      }
    });
    return isMatch;
  }

  getDecodedAccessToken(token: string): any {
    try {
      // return jwt_decode(token);
    } catch (Error) {
      return null;
    }
  }

  // ngOnDestroy() {
  //   // this.subscription && this.subscription.unsubscribe();
  // }
}
