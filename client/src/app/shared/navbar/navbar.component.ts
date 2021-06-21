import { Component, OnInit } from '@angular/core';
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
    private router: Router
  ) { }

  ngOnInit(): void {
    this.checkLoginStatus();
  }

  onLogout(): void {
    this.checkLoginStatus();
    this.router.navigate(['/login']);
  }

  checkLoginStatus(): void {
    this.showLogoutBtn = !!localStorage.getItem('accessToken');
  }
}
