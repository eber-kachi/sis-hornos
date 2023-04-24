import { ElementRef } from '@angular/core';

export function convertFormToFormData(formDataObject: any): FormData {
  if (formDataObject) {
    const formData = new FormData();
    const fitlerKeys: any[] = Object.keys(formDataObject);
    console.log(fitlerKeys);
    if (fitlerKeys.length > 0) {
      // queryString = '?';
    }
    fitlerKeys.forEach((key: any, index) => {
      if (formDataObject[key] !== undefined && formDataObject[key] !== null) {
        if (formDataObject[key].toString().length) {
          console.log(`${key}=${formDataObject[key]}`);
          formData.append(key, formDataObject[key]);
        }
      }
    });
    return formData;
  }
}

export function converObjetToQueryString(filterObject: any) {
  let queryString = '';
  if (filterObject) {
    const fitlerKeys: any[] = Object.keys(filterObject);
    if (fitlerKeys.length > 0) {
      queryString = '?';
    }
    fitlerKeys.forEach((key: any, index) => {
      if (filterObject[key] !== undefined && filterObject[key] !== null) {
        if (filterObject[key].toString().length) {
          queryString += `${key}=${filterObject[key]}&`;
        }
      }
    });
    if (fitlerKeys.length > 0 && queryString[queryString.length - 1] === '&') {
      queryString = queryString.slice(0, -1);
    }
  }
  return queryString;
}

/* To copy any Text */
export function copyText(val: string) {
  return new Promise((resolve, reject) => {
    try {
      const selBox = document.createElement('textarea');
      selBox.style.position = 'fixed';
      selBox.style.left = '0';
      selBox.style.top = '0';
      selBox.style.opacity = '0';
      selBox.value = val;
      document.body.appendChild(selBox);
      selBox.focus();
      selBox.select();
      document.execCommand('copy');
      document.body.removeChild(selBox);
      resolve(true);
    } catch (e) {
      reject(e);
    }
  });
}
