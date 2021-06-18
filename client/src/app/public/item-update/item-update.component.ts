import {Component, Input, OnInit} from '@angular/core';
import {MatDialog, MatDialogRef} from '@angular/material/dialog';

import {Item} from '../../interfaces/item';
import {ItemService} from '../../services/item/item.service';
import {ItemDetailsFormComponent} from '../item-details-form/item-details-form.component';

@Component({
  selector: 'app-item-update',
  templateUrl: './item-update.component.html',
  styleUrls: ['./item-update.component.sass']
})
export class ItemUpdateComponent implements OnInit {

  @Input() item!: Item;

  constructor(
    private matDialog: MatDialog,
    private itemService: ItemService
  ) { }

  ngOnInit(): void {
  }

  onUpdate(): void {
    const dialogRef = this.openDialog();
    dialogRef.afterClosed().subscribe(result => {
      if (result) {
        this.updateItem(result);
      }
    });
  }

  private openDialog(): MatDialogRef<any> {
    const item: Item = Object.assign({}, this.item);
    return this.matDialog.open(ItemDetailsFormComponent, {
      data: {
        item,
        title: 'Update Item',
        submitButtonLabel: 'Update'
      }
    });
  }

  private updateItem(result: Item): void {
    const url = localStorage.getItem('serverUrl') + '/items/' + this.item.id;
    const item: Item = {
      id: undefined,
      name: result.name,
      description: result.description,
      price: result.price,
      bid: result.bid,
      closeDateTime: result.closeDateTime,
      accessToken: result.accessToken
    };
    this.itemService.updateItem(url, item)
      .subscribe();
  }
}
