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

  constructor(
    private router: Router,
    private itemEventListenerService: ItemEventListenerService
  ) { }

  ngOnInit(): void {
  }

  onLogout(): void {
    localStorage.removeItem('accessToken');
    this.itemEventListenerService.onChangeAuthentication();
    this.router.navigate(['/login']);
  }
}
