import { Component, OnInit } from '@angular/core';
import {ItemEventListenerService} from '../../services/item-event-listener/item-event-listener.service';
import {Router} from '@angular/router';

@Component({
  selector: 'app-navbar',
  templateUrl: './navbar.component.html',
  styleUrls: ['./navbar.component.sass']
})
export class NavbarComponent implements OnInit {

  navbarMatIcon = 'phonelink';
  navbarTitle = 'AUCTION APPLICATION';
  logoutIcon = 'logout';
  logoutTitle = 'Sign out';
  showLogoutBtn = true;

  constructor(
    private router: Router,
    private itemEventListenerService: ItemEventListenerService
  ) { }

  ngOnInit(): void {
    this.checkLoginStatus();
  }

  onLogout(): void {
    localStorage.removeItem('accessToken');
    localStorage.removeItem('permissions');
    this.checkLoginStatus();
    this.itemEventListenerService.onChangeAuthentication();
    this.router.navigate(['/login']);
  }

  checkLoginStatus(): void {
    this.showLogoutBtn = !!localStorage.getItem('accessToken');
  }
}
