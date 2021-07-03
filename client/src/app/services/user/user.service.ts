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
export class UserService {

  constructor(
    private http: HttpClient,
    private itemEventListenerService: ItemEventListenerService
  ) { }

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
}
