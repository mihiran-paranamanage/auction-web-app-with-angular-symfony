import {Component, OnDestroy, OnInit} from '@angular/core';
import {FormBuilder} from '@angular/forms';
import {SnackbarService} from '../../services/snackbar/snackbar.service';
import {Item} from '../../interfaces/item';
import {ItemService} from '../../services/item/item.service';
import {Router} from '@angular/router';
import {CommonService} from '../../services/common/common.service';
import {UserService} from '../../services/user/user.service';
import {EventListenerService} from '../../services/event-listener/event-listener.service';
import {Subscription} from 'rxjs';

@Component({
  selector: 'app-add-item',
  templateUrl: './add-item.component.html',
  styleUrls: ['./add-item.component.sass']
})
export class AddItemComponent implements OnInit, OnDestroy {

  title = 'Add Item';
  submitButtonLabel = 'Add';
  minCloseDate = new Date();
  subscriptionEventSaveEmit?: Subscription;
  subscriptionEventFailureEmit?: Subscription;

  constructor(
    private formBuilder: FormBuilder,
    private itemService: ItemService,
    private commonService: CommonService,
    private userService: UserService,
    private snackbarService: SnackbarService,
    private eventListenerService: EventListenerService,
    private router: Router
  ) {
    this.subscribeForEvents();
  }

  itemForm = this.formBuilder.group({
    name: ['', this.commonService.getTextInputValidators()],
    description: ['', this.commonService.getTextLongInputValidators()],
    price: ['', this.commonService.getCurrencyInputValidators()],
    bid: ['', this.commonService.getCurrencyInputValidators()],
    closeDate: ['', this.commonService.getDateInputValidators()],
    closeTime: ['', this.commonService.getTimeInputValidators()],
    accessToken: [localStorage.getItem('accessToken')]
  });

  ngOnInit(): void {
    this.checkPermissions();
  }

  ngOnDestroy(): void {
    this.subscriptionEventSaveEmit?.unsubscribe();
    this.subscriptionEventFailureEmit?.unsubscribe();
  }

  subscribeForEvents(): void {
    this.subscriptionEventSaveEmit = this.eventListenerService.eventSaveEmit$.subscribe(item => {
      this.onAdded(item);
    });
    this.subscriptionEventFailureEmit = this.eventListenerService.eventFailureEmit$.subscribe(error => {
      this.onFailure(error);
    });
  }

  onAdd(): void {
    const url = localStorage.getItem('serverUrl') + '/items';
    const item: Item = {
      name: this.itemForm.value.name,
      description: this.itemForm.value.description,
      price: this.itemForm.value.price,
      bid: this.itemForm.value.bid,
      closeDateTime: this.commonService.dateToYmd(this.itemForm.value.closeDate) + ' ' + this.itemForm.value.closeTime,
      accessToken: this.itemForm.value.accessToken,
    };
    this.itemService.addItem(url, item)
      .subscribe();
  }

  onAdded(item: Item): void {
    this.snackbarService.openSnackBar('Item Added Successfully!');
  }

  onFailure(error: any): void {
    this.snackbarService.openSnackBar('Request Failed!');
  }

  checkPermissions(): void {
    const isPermitted = this.userService.checkPermissions('item', 'canCreate');
    if (!isPermitted) {
      this.router.navigate(['/forbidden']);
    }
  }
}
