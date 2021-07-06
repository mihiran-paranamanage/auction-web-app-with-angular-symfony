import {AfterViewInit, Component, Input, ViewChild} from '@angular/core';
import {UserBid} from '../../interfaces/user-bid';
import {MatTableDataSource} from '@angular/material/table';
import {MatPaginator} from '@angular/material/paginator';
import {MatSort} from '@angular/material/sort';

@Component({
  selector: 'app-user-bid-history',
  templateUrl: './user-bid-history.component.html',
  styleUrls: ['./user-bid-history.component.sass']
})
export class UserBidHistoryComponent implements AfterViewInit {

  @Input() bids?: UserBid[] = [];
  @ViewChild(MatPaginator) paginator!: MatPaginator;
  @ViewChild(MatSort) sort!: MatSort;

  dataSource = new MatTableDataSource<UserBid>(this.bids);
  displayedColumns: string[] = ['itemName', 'bid', 'isAutoBid', 'dateTime', 'itemCloseDateTime', 'itemStatus'];

  constructor() { }

  ngAfterViewInit(): void {
    this.updateTableDataSource();
  }

  updateTableDataSource(): void {
    this.dataSource = new MatTableDataSource<UserBid>(this.bids);
    this.dataSource.paginator = this.paginator;
    this.dataSource.sort = this.sort;
  }
}
