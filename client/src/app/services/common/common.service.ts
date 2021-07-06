import { Injectable } from '@angular/core';
import {ObservableInput, of} from 'rxjs';
import {HttpClient} from '@angular/common/http';
import {EventListenerService} from '../event-listener/event-listener.service';
import {AbstractControl, Validators} from '@angular/forms';

@Injectable({
  providedIn: 'root'
})
export class CommonService {

  constructor(
    private http: HttpClient,
    private eventListenerService: EventListenerService
  ) { }

  handleError(error: any): ObservableInput<any> {
    this.eventListenerService.onFailure(error);
    return of([]);
  }

  dateToYmd(date?: Date): string {
    if (!date) {
      date = new Date();
    }
    const d = date.getDate();
    const m = date.getMonth() + 1;
    const y = date.getFullYear();
    return y + '-' + (m < 10 ? '0' + m : m) + '-' + (d < 10 ? '0' + d : d);
  }

  stringToDate(date?: string): Date {
    if (!date) {
      return new Date();
    }
    return new Date(date);
  }

  stringToHHMM(date?: string): string {
    if (!date) {
      return '00:00';
    }
    return date.slice(11, 16);
  }

  getTextInputValidators(): object {
    return [Validators.required, Validators.maxLength(100)];
  }

  getTextLongInputValidators(): object {
    return [Validators.required, Validators.maxLength(2000)];
  }

  getCurrencyInputValidators(): object {
    return [Validators.required, Validators.pattern(/^\d+(.\d{2})?$/)];
  }

  getDateInputValidators(): object {
    return [Validators.required];
  }

  getTimeInputValidators(): object {
    return [Validators.required, Validators.pattern(/^(?:2[0-3]|[01][0-9]):[0-5][0-9]$/)];
  }

  getEmailInputValidators(): object {
    return [Validators.required, Validators.maxLength(100), Validators.pattern(/^\w+@[a-zA-Z_]+?\.[a-zA-Z]{2,3}$/)];
  }

  getPercentageInputValidators(): object {
    return [Validators.required, Validators.pattern(/^([0-9]|([1-9][0-9])|100)$/)];
  }

  getPasswordInputValidators(): object {
    return [Validators.maxLength(100)];
  }

  getPasswordConfirmationInputValidators(): object {
    return [Validators.maxLength(100), this.checkPasswordConfirmation];
  }

  checkPasswordConfirmation(control: AbstractControl): { [key: string]: boolean } | null {
    // @ts-ignore
    if (control.parent && control.parent.controls && control.parent.controls.password && control.parent.controls.password.value) {
      // @ts-ignore
      return control.parent.controls.password.value === control.value ? null : { mismatch: true };
    } else {
      return !control.value ? null : { mismatch: true };
    }
  }
}
