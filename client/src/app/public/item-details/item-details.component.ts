import {AfterViewInit, Component, OnInit} from '@angular/core';
import {FormBuilder, Validators} from '@angular/forms';
import {ItemService} from '../../services/item/item.service';
import {SnackbarService} from '../../services/snackbar/snackbar.service';
import {ItemEventListenerService} from '../../services/item-event-listener/item-event-listener.service';
import {Item} from '../../interfaces/item';
import {Bid} from '../../interfaces/bid';
import {AutoBidConfig} from '../../interfaces/auto-bid-config';
import {Observable} from 'rxjs';
import {ActivatedRoute, Params} from '@angular/router';

@Component({
  selector: 'app-item-details',
  templateUrl: './item-details.component.html',
  styleUrls: ['./item-details.component.sass']
})
export class ItemDetailsComponent implements AfterViewInit {

  title = 'Item Details';
  submitButtonLabel = 'Submit';

  itemId?: number;
  remainingTime = '0 day(s), 00 hr(s), 00 min(s), 00 sec(s)';
  allowSubmit = true;
  isItemBidChanged = false;
  fetchItemDetailsInterval?: any;
  updateRemainingTimeInterval?: any;
  showBidHistoryBtn = false;
  item$!: Observable<Item>;

  private item: Item = {
    id: undefined,
    name: '',
    description: '',
    price: 0,
    bid: 0,
    closeDateTime: '',
    accessToken: ''
  };

  private autoBidConfig: AutoBidConfig = {
    id: undefined,
    isAutoBidEnabled: false,
    maxBidAmount: 0
  };

  constructor(
    private formBuilder: FormBuilder,
    private itemService: ItemService,
    private snackbarService: SnackbarService,
    private itemEventListenerService: ItemEventListenerService,
    private route: ActivatedRoute
  ) {
    this.subscribeForItemEvents();
  }

  currencyInputValidators = [Validators.required, Validators.pattern(/^\d+(.\d{2})?$/)];

  bidForm = this.formBuilder.group({
    itemId: [undefined],
    bid: [0, this.currencyInputValidators],
    isAutoBid: [false],
    accessToken: [localStorage.getItem('accessToken')]
  });

  ngAfterViewInit(): void {
    this.route.params
      .subscribe(
        (params: Params) => {
          this.itemId = +params.id;
          this.fetchItemDetails();
          this.fetchItemDetailsInterval = setInterval(this.fetchItemDetails.bind(this), 1000 * 10);
          this.updateRemainingTimeInterval = setInterval(this.updateRemainingTime.bind(this), 1000);
        }
      );
  }

  fetchItemDetails(): void {
    const url = localStorage.getItem('serverUrl') + '/items/' + this.itemId + '?accessToken=' + localStorage.getItem('accessToken');
    this.item$ = this.itemService.getItem(url);
    this.itemService.getItem(url)
      .subscribe(item => {
        this.isItemBidChanged = this.item.bid !== item.bid;
        this.item = item;
        this.fetchAutoBigConfig();
        this.checkBidHistoryPermissions();
      });
  }

  updateRemainingTime(): void {
    const closeDateTime = (this.item && this.item.closeDateTime) ? new Date(this.item.closeDateTime) : new Date();

    let days: number|string = 0;
    let hours: number|string = 0;
    let hoursLeft: number|string = 0;
    let minutes: number|string = 0;
    let minutesLeft: number|string = 0;
    let seconds: number|string = 0;

    let diff = (closeDateTime.getTime() - Date.now()) / 1000;

    days        = Math.floor(diff / 24 / 60 / 60);
    hoursLeft   = Math.floor((diff) - (days * 86400));
    hours       = Math.floor(hoursLeft / 3600);
    minutesLeft = Math.floor((hoursLeft) - (hours * 3600));
    minutes     = Math.floor(minutesLeft / 60);
    seconds = Math.floor(diff % 60);

    function pad(n: number|string): number|string {
      return (n < 10 ? '0' + n : n);
    }

    this.remainingTime = days + ' day(s), ' + pad(hours) + ' hr(s), ' + pad(minutes) + ' min(s), ' + pad(seconds) + ' sec(s)';

    if (--diff <= 0) {
      this.allowSubmit = false;
      this.remainingTime = '0 day(s), 00 hr(s), 00 min(s), 00 sec(s)';
      clearInterval(this.updateRemainingTimeInterval);
    }
  }

  fetchAutoBigConfig(): void {
    const url = localStorage.getItem('serverUrl') + '/autoBidConfig?accessToken=' + localStorage.getItem('accessToken');
    this.itemService.getAutoBidConfig(url)
      .subscribe(autoBidConfig => {
        this.autoBidConfig = autoBidConfig;
        this.updateAutoBidConfigForm();
      });
  }

  updateAutoBidConfigForm(): void {
    if (this.isItemBidChanged) {
      this.bidForm = this.formBuilder.group({
        itemId: [this.item.id],
        bid: [this.item.bid ? (+this.item.bid + 1).toFixed(2) : 0, this.currencyInputValidators],
        isAutoBid: [this.autoBidConfig.isAutoBidEnabled],
        accessToken: [localStorage.getItem('accessToken')]
      });
    }
  }

  subscribeForItemEvents(): void {
    this.itemEventListenerService.bidEventSaveEmit$.subscribe(bid => {
      this.onSaved(bid);
    });
    this.itemEventListenerService.itemEventFailureEmit$.subscribe(error => {
      this.onFailure(error);
    });
  }

  onSave(): void {
    const url = localStorage.getItem('serverUrl') + '/bids';
    this.itemService.saveBid(url, this.bidForm.value)
      .subscribe();
  }

  onSaved(bid: Bid): void {
    this.snackbarService.openSnackBar('Bid Saved Successfully!');
  }

  onFailure(error: any): void {
    this.snackbarService.openSnackBar('Request Failed!');
  }

  checkBidHistoryPermissions(): void {
    this.showBidHistoryBtn = this.itemService.checkPermissions('bid_history', 'canRead');
  }
}