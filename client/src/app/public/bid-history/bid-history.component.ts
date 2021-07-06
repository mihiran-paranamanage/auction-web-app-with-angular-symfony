import {AfterViewInit, Component, ViewChild} from '@angular/core';
import {ItemService} from '../../services/item/item.service';
import {SnackbarService} from '../../services/snackbar/snackbar.service';
import {MatTableDataSource} from '@angular/material/table';
import {MatPaginator} from '@angular/material/paginator';
import {ActivatedRoute, Params, Router} from '@angular/router';
import {UserService} from '../../services/user/user.service';
import {Bid} from '../../interfaces/bid';
import {MatSort} from '@angular/material/sort';
import {UserBid} from '../../interfaces/user-bid';

@Component({
  selector: 'app-bid-history',
  templateUrl: './bid-history.component.html',
  styleUrls: ['./bid-history.component.sass']
})
export class BidHistoryComponent implements AfterViewInit {

  title = 'Bid History';
  itemId?: number;
  bids: Bid[] = [];
  dataSource = new MatTableDataSource<UserBid>(this.bids);
  displayedColumns: string[] = ['itemName', 'bid', 'isAutoBid', 'dateTime'];

  @ViewChild(MatPaginator) paginator!: MatPaginator;
  @ViewChild(MatSort) sort!: MatSort;

  constructor(
    private itemService: ItemService,
    private userService: UserService,
    private snackbarService: SnackbarService,
    private route: ActivatedRoute,
    private router: Router
  ) { }

  ngAfterViewInit(): void {
    this.checkPermissions();
    this.route.params
      .subscribe(
        (params: Params) => {
          this.itemId = +params.id;
          this.fetchBids();
        }
      );
  }

  fetchBids(): void {
    let url = localStorage.getItem('serverUrl') + '/bids?accessToken=' + localStorage.getItem('accessToken');
    url += '&filter[itemId]=' + this.itemId;
    this.itemService.getBids(url)
      .subscribe(bids => {
        this.bids = bids;
        this.updateTableDataSource();
      });
  }

  updateTableDataSource(): void {
    this.dataSource = new MatTableDataSource<UserBid>(this.bids);
    this.dataSource.paginator = this.paginator;
    this.dataSource.sort = this.sort;
  }

  checkPermissions(): void {
    const isPermitted = this.userService.checkPermissions('bid_history', 'canRead');
    if (!isPermitted) {
      this.router.navigate(['/forbidden']);
    }
  }
}
