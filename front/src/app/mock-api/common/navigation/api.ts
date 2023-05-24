import { Injectable } from '@angular/core';
import { cloneDeep } from 'lodash-es';
import { FuseNavigationItem } from '@fuse/components/navigation';
import { FuseMockApiService } from '@fuse/lib/mock-api';
import { compactNavigation, defaultNavigation, futuristicNavigation, horizontalNavigation } from 'app/mock-api/common/navigation/data';
import {UserService} from "@core/user/user.service";

@Injectable({
    providedIn: 'root'
})
export class NavigationMockApi
{
    private readonly _compactNavigation: FuseNavigationItem[] = compactNavigation;
    private readonly _defaultNavigation: FuseNavigationItem[] = defaultNavigation;
    private readonly _futuristicNavigation: FuseNavigationItem[] = futuristicNavigation;
    private readonly _horizontalNavigation: FuseNavigationItem[] = horizontalNavigation;
    private  navigation: FuseNavigationItem[] = [];

    private  roleName:string
    /**
     * Constructor
     */
    constructor(
      private _fuseMockApiService: FuseMockApiService,
      private userService: UserService,
    )
    {
        // Register Mock API handlers


        this.userService.role$.subscribe(role=>{
          console.log('api.ts', role)
          this.roleName = role.name;
          this.registerHandlers();
        })
    }

    // -----------------------------------------------------------------------------------------------------
    // @ Public methods
    // -----------------------------------------------------------------------------------------------------

    /**
     * Register Mock API handlers
     */
    registerHandlers(): void
    {
        // -----------------------------------------------------------------------------------------------------
        // @ Navigation - GET
        // -----------------------------------------------------------------------------------------------------
        this._fuseMockApiService
            .onGet('api/common/navigation')
            .reply(() => {
                // @ts-ignore
              this.navigation = [];
                // Fill compact navigation children using the default navigation
                this._defaultNavigation.forEach((defaultItem) => {
                  // debugger
                  if(defaultItem.roles.includes(this.roleName)){
                    this.navigation.push(defaultItem);
                  }
                    // this._defaultNavigation.forEach((defaultNavItem) => {
                    //     if ( defaultNavItem.id === compactNavItem.id )
                    //     {
                    //         compactNavItem.children = cloneDeep(defaultNavItem.children);
                    //     }
                    // });
                });

                // Fill futuristic navigation children using the default navigation
                // this._futuristicNavigation.forEach((futuristicNavItem) => {
                //     this._defaultNavigation.forEach((defaultNavItem) => {
                //         if ( defaultNavItem.id === futuristicNavItem.id )
                //         {
                //             futuristicNavItem.children = cloneDeep(defaultNavItem.children);
                //         }
                //     });
                // });

                // Fill horizontal navigation children using the default navigation
                // this._horizontalNavigation.forEach((horizontalNavItem) => {
                //     this._defaultNavigation.forEach((defaultNavItem) => {
                //         if ( defaultNavItem.id === horizontalNavItem.id )
                //         {
                //             horizontalNavItem.children = cloneDeep(defaultNavItem.children);
                //         }
                //     });
                // });




                // Return the response
                return [
                    200,
                    {
                        compact   : cloneDeep(this._compactNavigation),
                        default   : cloneDeep(this.navigation),
                        futuristic: cloneDeep(this._futuristicNavigation),
                        horizontal: cloneDeep(this._horizontalNavigation)
                    }
                ];
            });
    }
}
