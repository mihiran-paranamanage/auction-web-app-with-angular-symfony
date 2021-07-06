import { Injectable } from '@angular/core';
import {Subject} from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class EventListenerService {

  private eventSaveInputSource = new Subject<any>();
  private eventUpdateInputSource = new Subject<any>();
  private eventDeleteInputSource = new Subject<any>();

  private eventSaveEmitSource = new Subject<any>();
  private eventUpdateEmitSource = new Subject<any>();
  private eventDeleteEmitSource = new Subject<any>();

  private eventFailureEmitSource = new Subject<any>();
  private onChangeAuthenticationEventEmitSource = new Subject<any>();

  eventSaveInput$ = this.eventSaveInputSource.asObservable();
  eventUpdateInput$ = this.eventUpdateInputSource.asObservable();
  eventDeleteInput$ = this.eventDeleteInputSource.asObservable();

  eventSaveEmit$ = this.eventSaveEmitSource.asObservable();
  eventUpdateEmit$ = this.eventUpdateEmitSource.asObservable();
  eventDeleteEmit$ = this.eventDeleteEmitSource.asObservable();

  eventFailureEmit$ = this.eventFailureEmitSource.asObservable();
  onChangeAuthenticationEventEmit$ = this.onChangeAuthenticationEventEmitSource.asObservable();

  constructor() { }

  onSave(obj: any): void {
    this.eventSaveInputSource.next(obj);
  }

  onSaved(obj: any): void {
    this.eventSaveEmitSource.next(obj);
  }

  onUpdate(obj: any): void {
    this.eventUpdateInputSource.next(obj);
  }

  onUpdated(obj: any): void {
    this.eventUpdateEmitSource.next(obj);
  }

  onDelete(obj: any): void {
    this.eventDeleteInputSource.next();
  }

  onDeleted(): void {
    this.eventDeleteEmitSource.next();
  }

  onFailure(error: any): void {
    this.eventFailureEmitSource.next(error);
  }

  onChangeAuthentication(): void {
    this.onChangeAuthenticationEventEmitSource.next();
  }
}
