import { Injectable } from '@angular/core';
import {Subject} from 'rxjs';

import {Item} from '../../interfaces/item';
import {AutoBidConfig} from '../../interfaces/auto-bid-config';
import {Bid} from '../../interfaces/bid';
import {UserDetails} from "../../interfaces/user-details";

@Injectable({
  providedIn: 'root'
})
export class ItemEventListenerService {

  private itemEventUpdateInputSource = new Subject<Item>();
  private itemEventDeleteInputSource = new Subject<Item>();
  private itemEventAddInputSource = new Subject<Item>();
  private autoBidConfigEventSaveInputSource = new Subject<Item>();
  private bidEventSaveInputSource = new Subject<Item>();

  private itemEventUpdateEmitSource = new Subject<Item>();
  private itemEventDeleteEmitSource = new Subject<any>();
  private itemEventAddEmitSource = new Subject<Item>();
  private autoBidConfigEventSaveEmitSource = new Subject<Item>();
  private bidEventSaveEmitSource = new Subject<Item>();

  private itemEventFailureEmitSource = new Subject<any>();
  private onChangeAuthenticationEventEmitSource = new Subject<any>();

  itemEventUpdateInput$ = this.itemEventUpdateInputSource.asObservable();
  itemEventDeleteInput$ = this.itemEventDeleteInputSource.asObservable();
  itemEventAddInput$ = this.itemEventAddInputSource.asObservable();
  autoBidConfigEventSaveInput$ = this.autoBidConfigEventSaveInputSource.asObservable();
  bidEventSaveInput$ = this.bidEventSaveInputSource.asObservable();

  itemEventUpdateEmit$ = this.itemEventUpdateEmitSource.asObservable();
  itemEventDeleteEmit$ = this.itemEventDeleteEmitSource.asObservable();
  itemEventAddEmit$ = this.itemEventAddEmitSource.asObservable();
  autoBidConfigEventSaveEmit$ = this.autoBidConfigEventSaveEmitSource.asObservable();
  bidEventSaveEmit$ = this.bidEventSaveEmitSource.asObservable();

  itemEventFailureEmit$ = this.itemEventFailureEmitSource.asObservable();
  onChangeAuthenticationEventEmit$ = this.onChangeAuthenticationEventEmitSource.asObservable();

  constructor() { }

  onUpdate(item: Item): void {
    this.itemEventUpdateInputSource.next(item);
  }

  onUpdated(item: Item): void {
    this.itemEventUpdateEmitSource.next(item);
  }

  onDelete(item: Item): void {
    this.itemEventDeleteInputSource.next();
  }

  onDeleted(): void {
    this.itemEventDeleteEmitSource.next();
  }

  onAdd(item: Item): void {
    this.itemEventAddInputSource.next(item);
  }

  onAdded(item: Item): void {
    this.itemEventAddEmitSource.next(item);
  }

  onFailure(error: any): void {
    this.itemEventFailureEmitSource.next(error);
  }

  onSaveAutoBidConfig(autoBidConfig: AutoBidConfig): void {
    this.autoBidConfigEventSaveInputSource.next(autoBidConfig);
  }

  onSavedAutoBidConfig(autoBidConfig: AutoBidConfig): void {
    this.autoBidConfigEventSaveEmitSource.next(autoBidConfig);
  }

  onSaveBid(bid: Bid): void {
    this.bidEventSaveInputSource.next(bid);
  }

  onSavedBid(bid: Bid): void {
    this.bidEventSaveEmitSource.next(bid);
  }

  onChangeAuthentication(): void {
    this.onChangeAuthenticationEventEmitSource.next();
  }

  onUpdatedUserDetails(userDetails: UserDetails): void {
    this.itemEventUpdateEmitSource.next(userDetails);
  }
}
