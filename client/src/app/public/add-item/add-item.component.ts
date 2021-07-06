import { Component, OnInit } from '@angular/core';
import {FormBuilder, Validators} from '@angular/forms';
import {SnackbarService} from '../../services/snackbar/snackbar.service';
import {Item} from '../../interfaces/item';
import {ItemService} from '../../services/item/item.service';
import {Router} from '@angular/router';
import {CommonService} from '../../services/common/common.service';
import {UserService} from '../../services/user/user.service';
import {EventListenerService} from '../../services/event-listener/event-listener.service';

@Component({
  selector: 'app-add-item',
  templateUrl: './add-item.component.html',
  styleUrls: ['./add-item.component.sass']
})
export class AddItemComponent implements OnInit {

  title = 'Add Item';
  submitButtonLabel = 'Add';
  minCloseDate = new Date();

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

  textInputValidators = [Validators.required, Validators.maxLength(100)];
  textLongInputValidators = [Validators.required, Validators.maxLength(2000)];
  currencyInputValidators = [Validators.required, Validators.pattern(/^\d+(.\d{2})?$/)];
  dateInputValidators = [Validators.required];
  timeInputValidators = [Validators.required, Validators.pattern(/^(?:2[0-3]|[01][0-9]):[0-5][0-9]$/)];

  itemForm = this.formBuilder.group({
    name: ['', this.textInputValidators],
    description: ['', this.textLongInputValidators],
    price: ['', this.currencyInputValidators],
    bid: ['', this.currencyInputValidators],
    closeDate: ['', this.dateInputValidators],
    closeTime: ['', this.timeInputValidators],
    accessToken: [localStorage.getItem('accessToken')]
  });

  ngOnInit(): void {
    this.checkPermissions();
  }

  subscribeForEvents(): void {
    this.eventListenerService.eventSaveEmit$.subscribe(item => {
      this.onAdded(item);
    });
    this.eventListenerService.eventFailureEmit$.subscribe(error => {
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
