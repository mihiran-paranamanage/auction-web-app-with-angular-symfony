import {AfterViewInit, Component, ViewChild} from '@angular/core';
import {MatPaginator} from '@angular/material/paginator';
import {MatSort} from '@angular/material/sort';
import {MatTableDataSource} from '@angular/material/table';
import {SnackbarService} from '../../services/snackbar/snackbar.service';

import {Item} from '../../interfaces/item';
import {ItemService} from '../../services/item/item.service';
import {ItemEventListenerService} from '../../services/item-event-listener/item-event-listener.service';

@Component({
  selector: 'app-item-list',
  templateUrl: './item-list.component.html',
  styleUrls: ['./item-list.component.sass']
})
export class ItemListComponent implements AfterViewInit {

  private items: Item[] = [];
  dataSource = new MatTableDataSource<Item>(this.items);
  displayedColumns: string[] = ['name', 'price', 'bid', 'closeDateTime', 'actions'];

  @ViewChild(MatPaginator) paginator!: MatPaginator;
  @ViewChild(MatSort) sort!: MatSort;

  constructor(
    private itemService: ItemService,
    private snackbarService: SnackbarService,
    private itemEventListenerService: ItemEventListenerService
  ) {
    this.subscribeForItemEvents();
  }

  ngAfterViewInit(): void {
    this.fetchItems();
  }

  subscribeForItemEvents(): void {
    this.itemEventListenerService.itemEventUpdateEmit$.subscribe(item => {
      this.onUpdated(item);
    });
    this.itemEventListenerService.itemEventDeleteEmit$.subscribe(() => {
      this.onDeleted();
    });
    this.itemEventListenerService.itemEventFailureEmit$.subscribe(error => {
      this.onFailure(error);
    });
  }

  fetchItems(): void {
    const url = localStorage.getItem('serverUrl') + '/items?accessToken=' + localStorage.getItem('accessToken');
    this.itemService.getItems(url)
      .subscribe(items => {
        this.items = items;
        this.updateTableDataSource();
      });
  }

  updateTableDataSource(): void {
    this.dataSource = new MatTableDataSource<Item>(this.items);
    this.dataSource.paginator = this.paginator;
    this.dataSource.sort = this.sort;
  }

  applyFilter(event: Event): void {
    const filterValue = (event.target as HTMLInputElement).value;
    this.dataSource.filter = filterValue.trim().toLowerCase();

    if (this.dataSource.paginator) {
      this.dataSource.paginator.firstPage();
    }
  }

  onUpdated(item: Item): void {
    this.snackbarService.openSnackBar('Item Updated Successfully!');
    this.fetchItems();
  }

  onDeleted(): void {
    this.snackbarService.openSnackBar('Item Deleted Successfully!');
    this.fetchItems();
  }

  onFailure(error: any): void {
    this.snackbarService.openSnackBar('Request Failed!');
  }
}
