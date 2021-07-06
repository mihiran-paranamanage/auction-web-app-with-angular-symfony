import {AfterViewInit, Component, Input} from '@angular/core';
import { Router } from '@angular/router';
import {Item} from '../../interfaces/item';

@Component({
  selector: 'app-item-bid',
  templateUrl: './item-bid.component.html',
  styleUrls: ['./item-bid.component.sass']
})
export class ItemBidComponent implements AfterViewInit {

  buttonLabel = 'Bid Now';
  @Input() item!: Item;

  constructor(
    private router: Router
  ) { }

  ngAfterViewInit(): void {
    this.buttonLabel = this.item.isClosed ? 'View Item' : 'Bid Now';
  }

  onBid(): void {
    this.router.navigate(['/items/', this.item.id, 'details']);
  }
}
