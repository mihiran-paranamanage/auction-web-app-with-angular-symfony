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
export class ConfigService {

  constructor(
    private http: HttpClient,
    private itemEventListenerService: ItemEventListenerService
  ) { }

  handleError(error: any): ObservableInput<any> {
    this.itemEventListenerService.onFailure(error);
    return of([]);
  }

  getAutoBidConfig(url: string): Observable<AutoBidConfig> {
    return this.http.get<AutoBidConfig>(url)
      .pipe(
        catchError(error => this.handleError(error))
      );
  }

  saveAutoBidConfig(url: string, autoBidConfig: AutoBidConfig): Observable<AutoBidConfig> {
    return this.http.put<AutoBidConfig>(url, autoBidConfig)
      .pipe(
        tap(response => this.itemEventListenerService.onSavedAutoBidConfig(autoBidConfig)),
        catchError(error => this.handleError(error))
      );
  }
}
