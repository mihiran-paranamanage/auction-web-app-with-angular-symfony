import {Component, Inject, OnInit} from '@angular/core';
import {FormBuilder, Validators} from '@angular/forms';
import {MAT_DIALOG_DATA} from '@angular/material/dialog';

import {Item} from '../../interfaces/item';

@Component({
  selector: 'app-item-details-form',
  templateUrl: './item-details-form.component.html',
  styleUrls: ['./item-details-form.component.sass']
})
export class ItemDetailsFormComponent implements OnInit {

  constructor(
    private formBuilder: FormBuilder,
    @Inject(MAT_DIALOG_DATA) public data: {
      item: Item,
      title: string,
      submitButtonLabel: string
    }
  ) { }

  textInputValidators = [Validators.required, Validators.maxLength(100)];
  textLongInputValidators = [Validators.required, Validators.maxLength(2000)];
  currencyInputValidators = [Validators.required, Validators.pattern(/^\d+(.\d{2})?$/)];
  dateInputValidators = [Validators.required];

  itemForm = this.formBuilder.group({
    name: [this.data.item.name, this.textInputValidators],
    description: [this.data.item.description, this.textLongInputValidators],
    price: [this.data.item.price, this.currencyInputValidators],
    bid: [this.data.item.bid, this.currencyInputValidators],
    closeDateTime: [this.data.item.closeDateTime, this.dateInputValidators],
    accessToken: [localStorage.getItem('accessToken')]
  });

  ngOnInit(): void {
  }
}
