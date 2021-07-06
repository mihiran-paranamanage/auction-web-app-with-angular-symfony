import { Injectable } from '@angular/core';
import {Observable, ObservableInput, of} from 'rxjs';
import {HttpClient} from '@angular/common/http';
import {catchError, tap} from 'rxjs/operators';
import {AutoBidConfig} from '../../interfaces/auto-bid-config';
import {EventListenerService} from '../event-listener/event-listener.service';

@Injectable({
  providedIn: 'root'
})
export class ConfigService {

  constructor(
    private http: HttpClient,
    private eventListenerService: EventListenerService
  ) { }

  getAutoBidConfig(url: string): Observable<AutoBidConfig> {
    return this.http.get<AutoBidConfig>(url)
      .pipe(
        catchError(error => this.handleError(error))
      );
  }

  saveAutoBidConfig(url: string, autoBidConfig: AutoBidConfig): Observable<AutoBidConfig> {
    return this.http.put<AutoBidConfig>(url, autoBidConfig)
      .pipe(
        tap(response => this.eventListenerService.onSaved(autoBidConfig)),
        catchError(error => this.handleError(error))
      );
  }

  handleError(error: any): ObservableInput<any> {
    this.eventListenerService.onFailure(error);
    return of([]);
  }
}
