import { Component, OnInit } from '@angular/core';
import {FormBuilder, Validators} from '@angular/forms';
import {SnackbarService} from '../../services/snackbar/snackbar.service';

import {Item} from '../../interfaces/item';
import {ItemService} from '../../services/item/item.service';
import {ItemEventListenerService} from '../../services/item-event-listener/item-event-listener.service';

@Component({
  selector: 'app-add-item',
  templateUrl: './add-item.component.html',
  styleUrls: ['./add-item.component.sass']
})
export class AddItemComponent implements OnInit {

  itemActionTitle = 'Add Item';
  submitButtonLabel = 'Add';

  constructor(
    private formBuilder: FormBuilder,
    private itemService: ItemService,
    private snackbarService: SnackbarService,
    private itemEventListenerService: ItemEventListenerService
  ) {
    this.subscribeForItemEvents();
  }

  textInputValidators = [Validators.required, Validators.maxLength(100)];
  textLongInputValidators = [Validators.required, Validators.maxLength(2000)];
  currencyInputValidators = [Validators.required, Validators.pattern(/^\d+(.\d{2})?$/)];
  dateInputValidators = [Validators.required];

  itemForm = this.formBuilder.group({
    name: ['', this.textInputValidators],
    description: ['', this.textLongInputValidators],
    price: ['', this.currencyInputValidators],
    bid: ['', this.currencyInputValidators],
    closeDateTime: ['', this.dateInputValidators],
    accessToken: [localStorage.getItem('accessToken')]
  });

  ngOnInit(): void {
  }

  subscribeForItemEvents(): void {
    this.itemEventListenerService.itemEventAddEmit$.subscribe(item => {
      this.onAdded(item);
    });
    this.itemEventListenerService.itemEventFailureEmit$.subscribe(error => {
      this.onFailure(error);
    });
  }

  onAdd(): void {
    const url = localStorage.getItem('serverUrl') + '/items';
    this.itemService.addItem(url, this.itemForm.value)
      .subscribe();
  }

  onAdded(item: Item): void {
    this.snackbarService.openSnackBar('Item Added Successfully!');
  }

  onFailure(error: any): void {
    this.snackbarService.openSnackBar('Request Failed!');
  }
}
