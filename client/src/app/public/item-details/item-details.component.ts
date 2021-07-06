import {AfterViewInit, Component, OnDestroy} from '@angular/core';
import {FormBuilder, Validators} from '@angular/forms';
import {ItemService} from '../../services/item/item.service';
import {SnackbarService} from '../../services/snackbar/snackbar.service';
import {Item} from '../../interfaces/item';
import {Bid} from '../../interfaces/bid';
import {AutoBidConfig} from '../../interfaces/auto-bid-config';
import {Observable} from 'rxjs';
import {ActivatedRoute, Params} from '@angular/router';
import {UserService} from '../../services/user/user.service';
import {ConfigService} from '../../services/config/config.service';
import {EventListenerService} from '../../services/event-listener/event-listener.service';

@Component({
  selector: 'app-item-details',
  templateUrl: './item-details.component.html',
  styleUrls: ['./item-details.component.sass']
})
export class ItemDetailsComponent implements AfterViewInit, OnDestroy {

  title = 'Item Details';
  submitButtonLabel = 'Submit Bid';
  isAutoBidEnabled = false;
  socket: any = undefined;
  itemId?: number;
  remainingTime = '0 day(s), 00 hr(s), 00 min(s), 00 sec(s)';
  allowSubmit = true;
  changeItemBid = true;
  showBidHistoryBtn = false;
  updateRemainingTimeInterval?: any;
  item$!: Observable<Item>;

  item: Item = {
    id: undefined,
    name: '',
    description: '',
    price: 0,
    bid: 0,
    closeDateTime: '',
    accessToken: ''
  };

  autoBidConfig: AutoBidConfig = {
    id: undefined,
    maxBidAmount: 0,
    currentBidAmount: 0,
    notifyPercentage: 0,
    isAutoBidEnabled: false
  };

  constructor(
    private formBuilder: FormBuilder,
    private itemService: ItemService,
    private userService: UserService,
    private configService: ConfigService,
    private snackbarService: SnackbarService,
    private eventListenerService: EventListenerService,
    private route: ActivatedRoute
  ) {
    this.subscribeForEvents();
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
          this.updateRemainingTimeInterval = setInterval(this.updateRemainingTime.bind(this), 1000);
        }
      );
  }

  ngOnDestroy(): void {
    if (this.socket) {
      this.socket.close();
    }
  }

  fetchItemDetails(): void {
    const url = localStorage.getItem('serverUrl') + '/items/' + this.itemId + '?accessToken=' + localStorage.getItem('accessToken');
    this.item$ = this.itemService.getItem(url);
    this.itemService.getItem(url)
      .subscribe(item => {
        this.checkForChangeItemBid(item);
        this.item = item;
        this.fetchAutoBigConfig();
        this.checkBidHistoryPermissions();
        this.subscribeForItemDetailsChangeEvent();
      });
  }

  checkForChangeItemBid(item: Item): void {
    this.changeItemBid = !this.item.bid || !item.bid || this.bidForm.value.bid < (+item.bid + 1);
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
      this.submitButtonLabel = 'Bid Closed';
      this.remainingTime = '0 day(s), 00 hr(s), 00 min(s), 00 sec(s)';
      clearInterval(this.updateRemainingTimeInterval);
    }
  }

  fetchAutoBigConfig(): void {
    const url = localStorage.getItem('serverUrl') + '/autoBidConfig?accessToken=' + localStorage.getItem('accessToken');
    this.configService.getAutoBidConfig(url)
      .subscribe(autoBidConfig => {
        this.autoBidConfig = autoBidConfig;
        this.isAutoBidEnabled = !!autoBidConfig.isAutoBidEnabled;
        this.updateBidForm();
      });
  }

  updateBidForm(): void {
    if (this.changeItemBid) {
      this.bidForm = this.formBuilder.group({
        itemId: [this.item.id],
        bid: [this.item.bid ? (+this.item.bid + 1).toFixed(2) : 0, this.currencyInputValidators],
        isAutoBid: [this.item.isAutoBidEnabled],
        accessToken: [localStorage.getItem('accessToken')]
      });
    }
  }

  subscribeForEvents(): void {
    this.eventListenerService.eventSaveEmit$.subscribe(bid => {
      this.onSaved(bid);
    });
    this.eventListenerService.eventFailureEmit$.subscribe(error => {
      this.onFailure(error);
    });
  }

  subscribeForItemDetailsChangeEvent(): void {
    if (!this.socket) {
      // @ts-ignore
      const webSocketUrl = localStorage.getItem('webSocketUrl') + this.item.id;
      this.socket = new WebSocket(webSocketUrl);
    }
    const that = this;
    this.socket.onmessage = (msg: string) => {
      console.log(msg);
      // that.fetchItemDetails();
    };
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
    if (error.status === 400) {
      this.showFailureNotification(error);
    } else {
      this.snackbarService.openSnackBar('Request Failed!');
    }
  }

  showFailureNotification(error: any): void {
    if (error.error.includes('Bid is closed')) {
      this.snackbarService.openSnackBarNotification('Warning: Bid is closed!');
    } else if (error.error.includes('Bid should be higher than the item bid')) {
      this.snackbarService.openSnackBarNotification('Warning: Bid should be higher than the current bid of the item!');
    } else if (error.error.includes('Already have the highest bid for the item')) {
      this.snackbarService.openSnackBarNotification('Warning: You already have the highest bid for this item!');
    } else {
      this.snackbarService.openSnackBar('Request Failed!');
    }
  }

  checkBidHistoryPermissions(): void {
    this.showBidHistoryBtn = this.userService.checkPermissions('bid_history', 'canRead');
  }
}
