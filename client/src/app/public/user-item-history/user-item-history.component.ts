import {AfterViewInit, Component, Input, ViewChild} from '@angular/core';
import {MatPaginator} from '@angular/material/paginator';
import {MatSort} from '@angular/material/sort';
import {MatTableDataSource} from '@angular/material/table';
import {UserItem} from '../../interfaces/user-item';

@Component({
  selector: 'app-user-item-history',
  templateUrl: './user-item-history.component.html',
  styleUrls: ['./user-item-history.component.sass']
})
export class UserItemHistoryComponent implements AfterViewInit {

  @Input() items?: UserItem[] = [];
  @ViewChild(MatPaginator) paginator!: MatPaginator;
  @ViewChild(MatSort) sort!: MatSort;

  dataSource = new MatTableDataSource<UserItem>(this.items);
  displayedColumns: string[] = ['name', 'description', 'price', 'bid', 'closeDateTime', 'itemStatus'];

  constructor() { }

  ngAfterViewInit(): void {
    this.updateTableDataSource();
  }

  updateTableDataSource(): void {
    this.dataSource = new MatTableDataSource<UserItem>(this.items);
    this.dataSource.paginator = this.paginator;
    this.dataSource.sort = this.sort;
  }
}
