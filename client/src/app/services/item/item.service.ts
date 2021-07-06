import { Injectable } from '@angular/core';
import {Observable, ObservableInput, of} from 'rxjs';
import {HttpClient} from '@angular/common/http';
import {catchError, map, tap} from 'rxjs/operators';
import {Item} from '../../interfaces/item';
import {Bid} from '../../interfaces/bid';
import {EventListenerService} from '../event-listener/event-listener.service';

@Injectable({
  providedIn: 'root'
})
export class ItemService {

  constructor(
    private http: HttpClient,
    private eventListenerService: EventListenerService
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
        tap(response => this.eventListenerService.onSaved(response)),
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
        tap(response => this.eventListenerService.onUpdated(response)),
        catchError(error => this.handleError(error))
      );
  }

  deleteItem(url: string): Observable<{}> {
    return this.http.delete<{}>(url)
      .pipe(
        tap(response => this.eventListenerService.onDeleted()),
        catchError(error => this.handleError(error))
      );
  }

  getBids(url: string): Observable<Bid[]> {
    return this.http.get<Bid>(url)
      .pipe(
        catchError(error => this.handleError(error))
      );
  }

  saveBid(url: string, bid: Bid): Observable<Bid> {
    return this.http.post<Bid>(url, bid)
      .pipe(
        tap(response => this.eventListenerService.onSaved(response)),
        catchError(error => this.handleError(error))
      );
  }

  handleError(error: any): ObservableInput<any> {
    this.eventListenerService.onFailure(error);
    return of([]);
  }
}
