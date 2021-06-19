import {AfterViewInit, Component, OnInit} from '@angular/core';
import {FormBuilder, Validators} from '@angular/forms';
import {SnackbarService} from '../../services/snackbar/snackbar.service';

import {ItemService} from '../../services/item/item.service';
import {ItemEventListenerService} from '../../services/item-event-listener/item-event-listener.service';
import {AutoBidConfig} from '../../interfaces/auto-bid-config';

@Component({
  selector: 'app-auto-bid-config',
  templateUrl: './auto-bid-config.component.html',
  styleUrls: ['./auto-bid-config.component.sass']
})
export class AutoBidConfigComponent implements AfterViewInit {

  title = 'Auto Bid Configurations';
  submitButtonLabel = 'Save';

  private autoBidConfig: AutoBidConfig = {
    id: undefined,
    isAutoBidEnabled: false,
    maxBidAmount: 0,
    currentBidAmount: 0,
    notifyPercentage: 0
  };

  constructor(
    private formBuilder: FormBuilder,
    private itemService: ItemService,
    private snackbarService: SnackbarService,
    private itemEventListenerService: ItemEventListenerService
  ) {
    this.subscribeForItemEvents();
  }

  currencyInputValidators = [Validators.required, Validators.pattern(/^\d+(.\d{2})?$/)];
  percentageInputValidators = [Validators.required, Validators.pattern(/^([0-9]|([1-9][0-9])|100)$/)];

  autoBidConfigForm = this.formBuilder.group({
    isAutoBidEnabled: [false],
    maxBidAmount: [0, this.currencyInputValidators],
    notifyPercentage: [100, this.percentageInputValidators],
    accessToken: [localStorage.getItem('accessToken')]
  });

  ngAfterViewInit(): void {
    this.fetchAutoBigConfig();
  }

  fetchAutoBigConfig(): void {
    const url = localStorage.getItem('serverUrl') + '/autoBidConfig?accessToken=' + localStorage.getItem('accessToken');
    this.itemService.getAutoBidConfig(url)
      .subscribe(autoBidConfig => {
        this.autoBidConfig = autoBidConfig;
        this.updateAutoBidConfigForm();
        this.checkForBidAlertNotifications();
      });
  }

  updateAutoBidConfigForm(): void {
    this.autoBidConfigForm = this.formBuilder.group({
      isAutoBidEnabled: [this.autoBidConfig.isAutoBidEnabled],
      maxBidAmount: [this.autoBidConfig.maxBidAmount, this.currencyInputValidators],
      notifyPercentage: [this.autoBidConfig.notifyPercentage, this.percentageInputValidators],
      accessToken: [localStorage.getItem('accessToken')]
    });
  }

  checkForBidAlertNotifications(): void {
    // @ts-ignore
    const maxBidAmount = parseFloat(this.autoBidConfig.maxBidAmount);
    // @ts-ignore
    const currentBidAmount = parseFloat(this.autoBidConfig.currentBidAmount);
    // @ts-ignore
    const notifyPercentage = parseFloat(this.autoBidConfig.notifyPercentage);
    if (this.autoBidConfig.isAutoBidEnabled && maxBidAmount && currentBidAmount) {
      if (maxBidAmount <= currentBidAmount) {
        this.onMaxBidAmountReached();
      } else {
        if (
          notifyPercentage &&
          (maxBidAmount * notifyPercentage / 100) <= currentBidAmount
        ) {
          this.onMaxBidAmountPercentageReached(notifyPercentage);
        }
      }
    }
  }

  subscribeForItemEvents(): void {
    this.itemEventListenerService.autoBidConfigEventSaveEmit$.subscribe(autoBidConfig => {
      this.onSaved(autoBidConfig);
    });
    this.itemEventListenerService.itemEventFailureEmit$.subscribe(error => {
      this.onFailure(error);
    });
  }

  onSave(): void {
    const url = localStorage.getItem('serverUrl') + '/autoBidConfig';
    this.itemService.saveAutoBidConfig(url, this.autoBidConfigForm.value)
      .subscribe();
  }

  onSaved(autoBidConfig: AutoBidConfig): void {
    this.snackbarService.openSnackBar('Configuration Saved Successfully!');
  }

  onFailure(error: any): void {
    this.snackbarService.openSnackBar('Request Failed!');
  }

  onMaxBidAmountReached(): void {
    this.snackbarService.openSnackBarNotification(
      'Maximum bid amount has been reached & auto-bidding process stopped. Please increase the maximum bid amount to continue.'
    );
  }

  onMaxBidAmountPercentageReached(notifyPercentage: number): void {
    const message = notifyPercentage + '% of the maximum bid amount is reserved!';
    this.snackbarService.openSnackBarNotification(message);
  }
}
