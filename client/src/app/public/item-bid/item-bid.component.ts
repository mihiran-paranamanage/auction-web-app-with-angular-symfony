import {Component, Input, OnInit} from '@angular/core';
import { Router } from '@angular/router';
import {Item} from '../../interfaces/item';

@Component({
  selector: 'app-item-bid',
  templateUrl: './item-bid.component.html',
  styleUrls: ['./item-bid.component.sass']
})
export class ItemBidComponent implements OnInit {

  @Input() item!: Item;

  constructor(
    private router: Router
  ) { }

  ngOnInit(): void {
  }

  onBid(): void {
    this.router.navigate(['/items/bid']);
  }
}
