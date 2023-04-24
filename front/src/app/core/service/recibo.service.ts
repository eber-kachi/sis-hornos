import { Injectable } from '@angular/core';
import * as moment from 'moment';

@Injectable({
  providedIn: 'root'
})
export class ReciboService {
  constructor() {}

  generateResivoHtml(data: any) {
    // table.tableInfo {
    //   border: 1px solid black;
    //   border-collapse: collapse;
    // }
    // table.tableInfo tr th {
    //   border: 1px solid black;
    //   border-collapse: collapse;
    // }
    // table.tableInfo tr td {
    //   border: 1px solid black;
    //   border-collapse: collapse;
    // }

    let html = `
      <table style="width: 30%; text-align: center; font-size: 15px">
        <tr>
          <td>
            <b>${data.factura.organizacion}</b>
          </td>
        </tr>
        <tr>
          <td>
            N° ${data.factura.id}
          </td>
        </tr>
      </table>
      <br>
      <table style="width: 30%; text-align: left; font-size: 14px">
        <tr>
          <th style="vertical-align: top;">
            Fecha:
          </th>
          <td>
            ${moment(data.factura.fecha).format('DD-MM-YYYY hh:mm')}
          </td>
        </tr>
        <tr>
          <th style="vertical-align: top;">
            Socio:
          </th>
          <td>
            ${data.parcels[0].membersactive[0].full_name}
          </td>
        </tr>
      </table>
      <style>
        table.tableInfo tr th {
          border-bottom: 1px solid black;
          border-collapse: collapse;
          border-bottom-style: dashed;
        }
        table.tableInfo tr td {
          border-bottom: 1px solid black;
          border-collapse: collapse;
          border-bottom-style: dashed;
        }
      </style>
      <br>
      <table style="width: 30%; font-size: 12px;" class="tableInfo">
        <tr>
          <th style="text-align: left;">Concepto</th>
          <th style="text-align: right;">Total</th>
        </tr>
    `;

    data.deudas.forEach((element: any, index: number) => {
      html += `
        <tr>
          <td colspan="2" style="text-align: left; border: 0px solid black;">
            <b>${element?.conjunto_ingreso?.cuenta_ingreso?.nombre}</b><br>
            ${element?.conjunto_ingreso?.nombre}<br>
            <span style="font-size: 12px">
              ${element.concepto}
      `;
      if (element?.lectura_id) {
        html += `
              <table style="text-align: left;">
                <tr>
                  <td style="border: 0px solid black; font-size: 10px">
                    Lect. Anterior:
                    ${element?.lectura?.lectura_anterior}
                  </td>
                  <td style="border: 0px solid black; font-size: 10px">
                    Lect. Actual:
                    ${element?.lectura?.lectura_actual}
                  </td>
                <tr>
                <tr>
                  <td style="border: 0px solid black; font-size: 10px">
                    Cubos:
                    ${element?.lectura?.cubos}
                  </td>
                  <td style="border: 0px solid black; font-size: 10px">
                    Cubos Exeso:
                    ${element?.lectura?.cubos_exeso}
                  </td>
                <tr>
              </table>
          `;
      }
      html += `
            </span>
          </td>
        </tr>
        <tr>
          <th colspan="2" style="text-align: right;">
            ${data.ingresos[index].monto_importe}
          </th>
        </tr>
      `;
    });

    html += `
        <tr>
          <th style="font-size: 13px;">TOTAL</th>
          <th style="text-align: right; font-size: 13px;">${data.factura.total}</th>
        </tr>
      </table>
      <br>
      <table style="width: 30%; text-align: center; font-size: 8px;">
        <tr>
          <td>
            <p style="font-size: small;">InittSoft</p>
          </td>
        <tr>
      </table>
    `;
    return html;
  }
}
