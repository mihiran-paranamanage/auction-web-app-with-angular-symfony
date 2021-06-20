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
        tap(response => this.itemEventListenerService.onAdded(item)),
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
        tap(response => this.itemEventListenerService.onUpdated(item)),
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

  getPermissions(url: string): Observable<Permission[]> {
    return this.http.get<Permission>(url)
      .pipe(
        catchError(error => this.handleError(error))
      );
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

  getBids(url: string): Observable<ItemBid[]> {
    return this.http.get<ItemBid>(url)
      .pipe(
        catchError(error => this.handleError(error))
      );
  }

  saveBid(url: string, bid: Bid): Observable<Bid> {
    return this.http.post<Bid>(url, bid)
      .pipe(
        tap(response => this.itemEventListenerService.onSavedBid(bid)),
        catchError(error => this.handleError(error))
      );
  }

  getAccessToken(url: string): Observable<AccessToken> {
    return this.http.get<AccessToken>(url)
      .pipe(
        catchError(error => this.handleError(error))
      );
  }

  checkPermissions(dataGroup: string, requiredPermission: string): boolean {
    // @ts-ignore
    const permissions = localStorage.getItem('permissions') ? JSON.parse(localStorage.getItem('permissions')) : {};
    return permissions[dataGroup] && permissions[dataGroup][requiredPermission] === true;
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
