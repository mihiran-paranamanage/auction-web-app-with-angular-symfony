import {Component, Inject, OnInit} from '@angular/core';
import {FormBuilder, Validators} from '@angular/forms';
import {MAT_DIALOG_DATA} from '@angular/material/dialog';
import {Item} from '../../interfaces/item';
import {ItemService} from '../../services/item/item.service';
import {CommonService} from '../../services/common/common.service';

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

  itemForm = this.formBuilder.group({
    name: [this.data.item.name, this.commonService.getTextInputValidators()],
    description: [this.data.item.description, this.commonService.getTextLongInputValidators()],
    price: [this.data.item.price, this.commonService.getCurrencyInputValidators()],
    bid: [this.data.item.bid, this.commonService.getCurrencyInputValidators()],
    closeDate: [this.commonService.stringToDate(this.data.item.closeDateTime), this.commonService.getDateInputValidators()],
    closeTime: [this.commonService.stringToHHMM(this.data.item.closeDateTime), this.commonService.getTimeInputValidators()],
    accessToken: [localStorage.getItem('accessToken')]
  });

  ngOnInit(): void {
  }
}
