import {AfterViewInit, Component, OnInit, ViewChild} from '@angular/core';
import {ItemService} from '../../services/item/item.service';
import {SnackbarService} from '../../services/snackbar/snackbar.service';
import {ItemEventListenerService} from '../../services/item-event-listener/item-event-listener.service';
import {ItemBid} from '../../interfaces/item-bid';
import {MatTableDataSource} from '@angular/material/table';
import {Item} from '../../interfaces/item';
import {MatPaginator} from '@angular/material/paginator';
import {ActivatedRoute, Params, Router} from '@angular/router';

@Component({
  selector: 'app-bid-history',
  templateUrl: './bid-history.component.html',
  styleUrls: ['./bid-history.component.sass']
})
export class BidHistoryComponent implements AfterViewInit {

  title = 'Bid History';

  itemId?: number;
  private bids: ItemBid[] = [];
  dataSource = new MatTableDataSource<Item>(this.bids);
  displayedColumns: string[] = ['user', 'bid', 'dateTime'];

  @ViewChild(MatPaginator) paginator!: MatPaginator;

  constructor(
    private itemService: ItemService,
    private snackbarService: SnackbarService,
    private itemEventListenerService: ItemEventListenerService,
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
    this.dataSource = new MatTableDataSource<Item>(this.bids);
    this.dataSource.paginator = this.paginator;
  }

  checkPermissions(): void {
    const isPermitted = this.itemService.checkPermissions('bid_history', 'canRead');
    if (!isPermitted) {
      this.router.navigate(['/forbidden']);
    }
  }
}
