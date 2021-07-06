import { Injectable } from '@angular/core';
import {Observable, ObservableInput, of} from 'rxjs';
import {HttpClient} from '@angular/common/http';
import {catchError, map, tap} from 'rxjs/operators';
import {Permission} from '../../interfaces/permission';
import {AccessToken} from '../../interfaces/access-token';
import {UserDetails} from '../../interfaces/user-details';
import {EventListenerService} from '../event-listener/event-listener.service';

@Injectable({
  providedIn: 'root'
})
export class UserService {

  constructor(
    private http: HttpClient,
    private eventListenerService: EventListenerService
  ) { }

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

  getUserDetails(url: string): Observable<UserDetails> {
    return this.http.get<UserDetails>(url)
      .pipe(
        catchError(error => this.handleError(error))
      );
  }

  updateUserDetails(url: string, userDetails: UserDetails): Observable<UserDetails> {
    return this.http.put<UserDetails>(url, userDetails)
      .pipe(
        tap(response => this.eventListenerService.onUpdated(response)),
        catchError(error => this.handleError(error))
      );
  }

  handleError(error: any): ObservableInput<any> {
    this.eventListenerService.onFailure(error);
    return of([]);
  }
}
