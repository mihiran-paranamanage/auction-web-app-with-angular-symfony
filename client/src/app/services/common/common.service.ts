import { Injectable } from '@angular/core';
import {Observable, ObservableInput, of} from 'rxjs';
import {HttpClient} from '@angular/common/http';
import {catchError, map, tap} from 'rxjs/operators';

import {Item} from '../../interfaces/item';
import {ItemEventListenerService} from '../item-event-listener/item-event-listener.service';
import {Permission} from '../../interfaces/permission';
import {AutoBidConfig} from '../../interfaces/auto-bid-config';
import {Bid} from '../../interfaces/bid';
import {ItemBid} from '../../interfaces/item-bid';
import {AccessToken} from '../../interfaces/access-token';

@Injectable({
  providedIn: 'root'
})
export class CommonService {

  constructor(
    private http: HttpClient,
    private itemEventListenerService: ItemEventListenerService
  ) { }

  handleError(error: any): ObservableInput<any> {
    this.itemEventListenerService.onFailure(error);
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
