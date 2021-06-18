import { Component, OnInit } from '@angular/core';

import {MenuItem} from '../../interfaces/menu-item';
import {ItemService} from '../../services/item/item.service';
import {Permission} from '../../interfaces/permission';

@Component({
  selector: 'app-sidenav-menu-items',
  templateUrl: './sidenav-menu-items.component.html',
  styleUrls: ['./sidenav-menu-items.component.sass']
})
export class SidenavMenuItemsComponent implements OnInit {

  menuItems: MenuItem[] = [];
  permissions: Permission[] = [];

  constructor(
    private itemService: ItemService,
  ) { }

  ngOnInit(): void {
    localStorage.setItem('accessToken', 'af874ho9s8dfush6');
    localStorage.setItem('serverUrl', 'http://localhost:8001/api');
    this.fetchPermissions();
    this.menuItems = [
      {id: 1, name: 'Home', routerLink: ['/home'], matIcon: 'home'}
    ];
  }

  fetchPermissions(): void {
    const url = localStorage.getItem('serverUrl') + '/permissions?accessToken=' + localStorage.getItem('accessToken');
    this.itemService.getPermissions(url)
      .subscribe(permissions => {
        this.permissions = permissions;
        this.updateMenuItems();
      });
  }

  updateMenuItems(): void {
    // @ts-ignore
    if (this.permissions.admin_dashboard && this.permissions.admin_dashboard.canRead) {
      this.menuItems.push({id: 2, name: 'Admin Dashboard', routerLink: ['/items'], matIcon: 'dashboard'});
    }
    // @ts-ignore
    if (this.permissions.item && this.permissions.item.canCreate) {
      this.menuItems.push({id: 3, name: 'Add Item', routerLink: ['/items/add'], matIcon: 'playlist_add'});
    }
    // @ts-ignore
    if (this.permissions.configure_auto_bid && this.permissions.configure_auto_bid.canRead) {
      this.menuItems.push({id: 4, name: 'Configurations', routerLink: ['/autoBidConfig'], matIcon: 'settings'});
    }
  }
}
