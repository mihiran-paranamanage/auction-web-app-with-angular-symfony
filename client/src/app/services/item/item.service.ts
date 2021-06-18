import { Injectable } from '@angular/core';
import {Observable, ObservableInput, of} from 'rxjs';
import {HttpClient} from '@angular/common/http';
import {catchError, map, tap} from 'rxjs/operators';

import {Item} from '../../interfaces/item';
import {ItemEventListenerService} from '../item-event-listener/item-event-listener.service';
import {Permission} from '../../interfaces/permission';

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
    console.log(error);
    this.itemEventListenerService.onFailure(error);
    return of([]);
  }

  getPermissions(url: string): Observable<Permission[]> {
    return this.http.get<Permission>(url)
      .pipe(
        catchError(error => this.handleError(error))
      );
  }
}
