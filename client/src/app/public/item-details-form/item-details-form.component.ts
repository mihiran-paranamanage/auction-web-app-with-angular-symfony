import {Component, Inject, OnInit} from '@angular/core';
import {FormBuilder, Validators} from '@angular/forms';
import {MAT_DIALOG_DATA} from '@angular/material/dialog';

import {Item} from '../../interfaces/item';
import {ItemService} from '../../services/item/item.service';
import {CommonService} from "../../services/common/common.service";

@Component({
  selector: 'app-item-details-form',
  templateUrl: './item-details-form.component.html',
  styleUrls: ['./item-details-form.component.sass']
})
export class ItemDetailsFormComponent implements OnInit {

  constructor(
    private formBuilder: FormBuilder,
    private itemService: ItemService,
    private commonService: CommonService,
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
  timeInputValidators = [Validators.required, Validators.pattern(/^(?:2[0-3]|[01][0-9]):[0-5][0-9]$/)];

  itemForm = this.formBuilder.group({
    name: [this.data.item.name, this.textInputValidators],
    description: [this.data.item.description, this.textLongInputValidators],
    price: [this.data.item.price, this.currencyInputValidators],
    bid: [this.data.item.bid, this.currencyInputValidators],
    closeDate: [this.commonService.stringToDate(this.data.item.closeDateTime), this.dateInputValidators],
    closeTime: [this.commonService.stringToHHMM(this.data.item.closeDateTime), this.timeInputValidators],
    accessToken: [localStorage.getItem('accessToken')]
  });

  ngOnInit(): void {
  }
}
