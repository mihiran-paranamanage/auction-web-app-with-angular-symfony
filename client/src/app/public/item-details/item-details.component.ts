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
        }
      );
  }

  fetchItemDetails(): void {
    const url = localStorage.getItem('serverUrl') + '/items/' + this.itemId + '?accessToken=' + localStorage.getItem('accessToken');
    this.item$ = this.itemService.getItem(url);
    this.itemService.getItem(url)
      .subscribe(item => {
        this.item = item;
        this.fetchAutoBigConfig();
      });
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
    console.log(this.item);
    console.log(this.autoBidConfig);
    this.bidForm = this.formBuilder.group({
      itemId: [this.item.id],
      bid: [this.item.bid ? (+this.item.bid + 1).toFixed(2) : 0, this.currencyInputValidators],
      isAutoBid: [this.autoBidConfig.isAutoBidEnabled],
      accessToken: [localStorage.getItem('accessToken')]
    });
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
}
