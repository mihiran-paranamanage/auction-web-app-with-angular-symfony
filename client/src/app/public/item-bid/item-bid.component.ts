import {Component, Input, OnInit} from '@angular/core';
import { Router } from '@angular/router';
import {Item} from '../../interfaces/item';

@Component({
  selector: 'app-item-bid',
  templateUrl: './item-bid.component.html',
  styleUrls: ['./item-bid.component.sass']
})
export class ItemBidComponent implements OnInit {

  buttonLabel = 'Bid Now';
  buttonColor?: string;
  @Input() item!: Item;

  constructor(
    private router: Router
  ) { }

  isBidClosed(item: Item): boolean {
    const closeDateTime = (item && item.closeDateTime) ? new Date(item.closeDateTime) : new Date();
    const diff = (closeDateTime.getTime() - Date.now()) / 1000;
    return diff <= 0;
  }

  ngOnInit(): void {
    if (this.item.isClosed || this.isBidClosed(this.item)) {
      this.buttonLabel = 'View Item';
      this.buttonColor = undefined;
    } else {
      this.buttonLabel = 'Bid Now';
      this.buttonColor = 'primary';
    }
  }

  onBid(): void {
    this.router.navigate(['/items/', this.item.id, 'details']);
  }
}
