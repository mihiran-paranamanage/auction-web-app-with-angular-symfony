import { Component, OnInit } from '@angular/core';
import {ItemService} from '../../services/item/item.service';
import {Router} from '@angular/router';

@Component({
  selector: 'app-items',
  templateUrl: './items.component.html',
  styleUrls: ['./items.component.sass']
})
export class ItemsComponent implements OnInit {

  constructor(
    private itemService: ItemService,
    private router: Router
  ) { }

  ngOnInit(): void {
    this.checkPermissions();
  }

  checkPermissions(): void {
    const isPermitted = this.itemService.checkPermissions('admin_dashboard', 'canRead');
    if (!isPermitted) {
      this.router.navigate(['/forbidden']);
    }
  }
}
