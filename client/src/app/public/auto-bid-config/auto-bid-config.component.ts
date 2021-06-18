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
    maxBidAmount: 0
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

  autoBidConfigForm = this.formBuilder.group({
    isAutoBidEnabled: [false],
    maxBidAmount: [0, this.currencyInputValidators],
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
      });
  }

  updateAutoBidConfigForm(): void {
    this.autoBidConfigForm = this.formBuilder.group({
      isAutoBidEnabled: [this.autoBidConfig.isAutoBidEnabled],
      maxBidAmount: [this.autoBidConfig.maxBidAmount, this.currencyInputValidators],
      accessToken: [localStorage.getItem('accessToken')]
    });
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
}
