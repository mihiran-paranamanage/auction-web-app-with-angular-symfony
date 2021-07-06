import {AfterViewInit, Component} from '@angular/core';
import {FormBuilder, Validators} from '@angular/forms';
import {SnackbarService} from '../../services/snackbar/snackbar.service';
import {ItemService} from '../../services/item/item.service';
import {AutoBidConfig} from '../../interfaces/auto-bid-config';
import {Observable} from 'rxjs';
import {ConfigService} from '../../services/config/config.service';
import {EventListenerService} from '../../services/event-listener/event-listener.service';

@Component({
  selector: 'app-auto-bid-config',
  templateUrl: './auto-bid-config.component.html',
  styleUrls: ['./auto-bid-config.component.sass']
})
export class AutoBidConfigComponent implements AfterViewInit {

  title = 'Auto Bid Configurations';
  submitButtonLabel = 'Save';
  autoBidConfig$!: Observable<AutoBidConfig>;

  private autoBidConfig: AutoBidConfig = {
    id: undefined,
    maxBidAmount: 0,
    currentBidAmount: 0,
    notifyPercentage: 0
  };

  constructor(
    private formBuilder: FormBuilder,
    private itemService: ItemService,
    private configService: ConfigService,
    private snackbarService: SnackbarService,
    private eventListenerService: EventListenerService
  ) {
    this.subscribeForEvents();
  }

  currencyInputValidators = [Validators.required, Validators.pattern(/^\d+(.\d{2})?$/)];
  percentageInputValidators = [Validators.required, Validators.pattern(/^([0-9]|([1-9][0-9])|100)$/)];

  autoBidConfigForm = this.formBuilder.group({
    maxBidAmount: [0, this.currencyInputValidators],
    notifyPercentage: [100, this.percentageInputValidators],
    isAutoBidEnabled: [false],
    accessToken: [localStorage.getItem('accessToken')]
  });

  ngAfterViewInit(): void {
    this.fetchAutoBigConfig();
  }

  fetchAutoBigConfig(): void {
    const url = localStorage.getItem('serverUrl') + '/autoBidConfig?accessToken=' + localStorage.getItem('accessToken');
    this.autoBidConfig$ = this.itemService.getItem(url);
    this.configService.getAutoBidConfig(url)
      .subscribe(autoBidConfig => {
        this.autoBidConfig = autoBidConfig;
        this.updateAutoBidConfigForm();
        this.checkForBidAlertNotifications();
      });
  }

  updateAutoBidConfigForm(): void {
    this.autoBidConfigForm = this.formBuilder.group({
      maxBidAmount: [this.autoBidConfig.maxBidAmount, this.currencyInputValidators],
      notifyPercentage: [this.autoBidConfig.notifyPercentage, this.percentageInputValidators],
      isAutoBidEnabled: [this.autoBidConfig.isAutoBidEnabled],
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
    if (maxBidAmount > 0 && currentBidAmount > 0) {
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

  subscribeForEvents(): void {
    this.eventListenerService.eventSaveEmit$.subscribe(autoBidConfig => {
      this.onSaved(autoBidConfig);
    });
    this.eventListenerService.eventFailureEmit$.subscribe(error => {
      this.onFailure(error);
    });
  }

  onSave(): void {
    const url = localStorage.getItem('serverUrl') + '/autoBidConfig';
    this.configService.saveAutoBidConfig(url, this.autoBidConfigForm.value)
      .subscribe();
  }

  onSaved(autoBidConfig: AutoBidConfig): void {
    this.fetchAutoBigConfig();
    this.snackbarService.openSnackBar('Configuration Saved Successfully!');
  }

  onFailure(error: any): void {
    this.snackbarService.openSnackBar('Request Failed!');
  }

  onMaxBidAmountReached(): void {
    this.snackbarService.openSnackBarNotification(
      'Warning: Maximum bid amount has been reached & auto-bidding process stopped. Please increase the maximum bid amount to continue.'
    );
  }

  onMaxBidAmountPercentageReached(notifyPercentage: number): void {
    const message = 'Warning: ' + notifyPercentage + '% of the maximum bid amount is reserved!';
    this.snackbarService.openSnackBarNotification(message);
  }

  getBidUsagePercentage(autoBidConfig: AutoBidConfig): number|string {
    // @ts-ignore
    const maxBidAmount = parseFloat(autoBidConfig.maxBidAmount);
    // @ts-ignore
    const currentBidAmount = parseFloat(autoBidConfig.currentBidAmount);
    if (currentBidAmount && maxBidAmount) {
      const percentage = currentBidAmount * 100 / maxBidAmount;
      return percentage.toFixed(2);
    } else {
      return 0;
    }
  }
}
