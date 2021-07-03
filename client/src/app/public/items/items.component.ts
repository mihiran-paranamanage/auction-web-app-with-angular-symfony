import { Component, OnInit } from '@angular/core';
import {ItemService} from '../../services/item/item.service';
import {Router} from '@angular/router';
import {UserService} from "../../services/user/user.service";

@Component({
  selector: 'app-items',
  templateUrl: './items.component.html',
  styleUrls: ['./items.component.sass']
})
export class ItemsComponent implements OnInit {

  constructor(
    private itemService: ItemService,
    private userService: UserService,
    private router: Router
  ) { }

  ngOnInit(): void {
    this.checkPermissions();
  }

  checkPermissions(): void {
    const isPermitted = this.userService.checkPermissions('admin_dashboard', 'canRead');
    if (!isPermitted) {
      this.router.navigate(['/forbidden']);
    }
  }
}
