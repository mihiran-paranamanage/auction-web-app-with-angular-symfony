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
export class ItemService {

  constructor(
    private http: HttpClient,
    private itemEventListenerService: ItemEventListenerService
  ) { }

  getItems(url: string): Observable<Item[]> {
    return this.http.get<Item>(url)
      .pipe(
        map(response => response),
        catchError(error => this.handleError(error))
      );
  }

  addItem(url: string, item: Item): Observable<Item> {
    return this.http.post<Item>(url, item)
      .pipe(
        tap(response => this.itemEventListenerService.onAdded(response)),
        catchError(error => this.handleError(error))
      );
  }

  getItem(url: string): Observable<Item> {
    return this.http.get<Item>(url)
      .pipe(
        catchError(error => this.handleError(error))
      );
  }

  updateItem(url: string, item: Item): Observable<Item> {
    return this.http.put<Item>(url, item)
      .pipe(
        tap(response => this.itemEventListenerService.onUpdated(response)),
        catchError(error => this.handleError(error))
      );
  }

  deleteItem(url: string): Observable<{}> {
    return this.http.delete<{}>(url)
      .pipe(
        tap(response => this.itemEventListenerService.onDeleted()),
        catchError(error => this.handleError(error))
      );
  }

  handleError(error: any): ObservableInput<any> {
    this.itemEventListenerService.onFailure(error);
    return of([]);
  }

  getBids(url: string): Observable<ItemBid[]> {
    return this.http.get<ItemBid>(url)
      .pipe(
        catchError(error => this.handleError(error))
      );
  }

  saveBid(url: string, bid: Bid): Observable<Bid> {
    return this.http.post<Bid>(url, bid)
      .pipe(
        tap(response => this.itemEventListenerService.onSavedBid(response)),
        catchError(error => this.handleError(error))
      );
  }
}
