import { Injectable } from '@angular/core';
import {ObservableInput, of} from 'rxjs';
import {HttpClient} from '@angular/common/http';
import {EventListenerService} from '../event-listener/event-listener.service';

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
}
