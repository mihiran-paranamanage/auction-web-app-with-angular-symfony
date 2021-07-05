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
  profileIcon = 'person';
  logoutIcon = 'logout';
  profileTitle = 'My profile';
  logoutTitle = 'Sign out';
  showProfileBtn = true;
  showLogoutBtn = true;

  constructor(
    private router: Router
  ) { }

  ngOnInit(): void {
    this.checkLoginStatus();
  }

  onProfile(): void {
    this.router.navigate(['/profile']);
  }

  onLogout(): void {
    this.checkLoginStatus();
    this.router.navigate(['/login']).then(() => {
      window.location.reload();
    });
  }

  checkLoginStatus(): void {
    this.showLogoutBtn = !!localStorage.getItem('accessToken');
    this.showProfileBtn = this.showLogoutBtn;
  }
}
