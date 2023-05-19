import { Injectable } from '@angular/core';
import { ToastrService } from 'ngx-toastr';

@Injectable({
  providedIn: 'root',
})
export class AlertSwallService {
  constructor(private toastrService: ToastrService) {}

  showSwallError(message: string) {
    console.log('MOSTRANDO ERRAR');

    this.toastrService.error(message, 'ERROR', {
      closeButton: true,
      timeOut: 3000,
    });
    /* Swal.fire({
       position: 'top-end',
       width: '20vh',
       heightAuto: true,
       icon: 'error',
       text: message,
       showConfirmButton: false,
       timer: 2000
     });*/
  }

  showSwallSuccess(message: string, html?: string) {
    this.toastrService.success(message, 'CORRECTO', {
      closeButton: true,
      timeOut: 2000,
    });
    // Swal.fire({
    //   position: 'top-end',
    //   width: '20vh',
    //   heightAuto: true,
    //   icon: 'success',
    //   text: message,
    //   html: html,
    //   showConfirmButton: false,
    //   timer: 2000
    // });
  }

  async showConfirm(options: {
    title: string;
    text: string;
    icon?: 'warning';
  }) {
    return 'log no hay confirm ';
    // return await Swal.fire({
    //     title: options.title,
    //     html: options.text,
    //     icon: options.icon,
    //     showCancelButton: true,
    //     confirmButtonColor: '#3085d6',
    //     cancelButtonColor: '#d33',
    //     confirmButtonText: 'Si, acepto',
    // });
  }
}
