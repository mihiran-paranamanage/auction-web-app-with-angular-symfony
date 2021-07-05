import {AfterViewInit, Component, Input, OnInit, ViewChild} from '@angular/core';
import {UserAwardedItem} from '../../interfaces/user-awarded-item';
import {MatPaginator} from "@angular/material/paginator";
import {MatSort} from "@angular/material/sort";
import {MatTableDataSource} from "@angular/material/table";
import {UserBid} from "../../interfaces/user-bid";

@Component({
  selector: 'app-user-awarded-item-history',
  templateUrl: './user-awarded-item-history.component.html',
  styleUrls: ['./user-awarded-item-history.component.sass']
})
export class UserAwardedItemHistoryComponent implements AfterViewInit {

  @Input() awardedItems?: UserAwardedItem[] = [];
  @ViewChild(MatPaginator) paginator!: MatPaginator;
  @ViewChild(MatSort) sort!: MatSort;

  dataSource = new MatTableDataSource<UserBid>(this.awardedItems);
  displayedColumns: string[] = ['item', 'bid', 'isAutoBid', 'dateTime'];

  constructor() {
    this.updateTableDataSource();
  }

  ngAfterViewInit(): void {
    this.updateTableDataSource();
  }

  updateTableDataSource(): void {
    this.dataSource = new MatTableDataSource<UserBid>(this.awardedItems);
    this.dataSource.paginator = this.paginator;
    this.dataSource.sort = this.sort;
  }
}
