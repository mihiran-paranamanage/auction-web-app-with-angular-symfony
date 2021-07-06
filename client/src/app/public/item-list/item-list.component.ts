import {AfterViewInit, Component, OnDestroy, ViewChild} from '@angular/core';
import {MatPaginator} from '@angular/material/paginator';
import {MatSort} from '@angular/material/sort';
import {MatTableDataSource} from '@angular/material/table';
import {SnackbarService} from '../../services/snackbar/snackbar.service';
import {Item} from '../../interfaces/item';
import {ItemService} from '../../services/item/item.service';
import {EventListenerService} from '../../services/event-listener/event-listener.service';
import {Subscription} from 'rxjs';

@Component({
  selector: 'app-item-list',
  templateUrl: './item-list.component.html',
  styleUrls: ['./item-list.component.sass']
})
export class ItemListComponent implements AfterViewInit, OnDestroy {

  title = 'Admin Dashboard';
  items: Item[] = [];
  dataSource = new MatTableDataSource<Item>(this.items);
  displayedColumns: string[] = ['name', 'description', 'price', 'bid', 'closeDateTime', 'actions'];
  subscriptionEventUpdateEmit?: Subscription;
  subscriptionEventDeleteEmit?: Subscription;
  subscriptionEventFailureEmit?: Subscription;

  @ViewChild(MatPaginator) paginator!: MatPaginator;
  @ViewChild(MatSort) sort!: MatSort;

  constructor(
    private itemService: ItemService,
    private snackbarService: SnackbarService,
    private eventListenerService: EventListenerService
  ) {
    this.subscribeForEvents();
  }

  ngAfterViewInit(): void {
    this.fetchItems();
  }

  ngOnDestroy(): void {
    this.subscriptionEventUpdateEmit?.unsubscribe();
    this.subscriptionEventDeleteEmit?.unsubscribe();
    this.subscriptionEventFailureEmit?.unsubscribe();
  }

  subscribeForEvents(): void {
    this.subscriptionEventUpdateEmit = this.eventListenerService.eventUpdateEmit$.subscribe(item => {
      this.onUpdated(item);
    });
    this.subscriptionEventDeleteEmit = this.eventListenerService.eventDeleteEmit$.subscribe(() => {
      this.onDeleted();
    });
    this.subscriptionEventFailureEmit = this.eventListenerService.eventFailureEmit$.subscribe(error => {
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
