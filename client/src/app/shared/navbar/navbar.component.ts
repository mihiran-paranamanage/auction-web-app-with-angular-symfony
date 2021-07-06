import { Component, OnInit } from '@angular/core';
import {Router} from '@angular/router';
import {Observable} from 'rxjs';
import {UserDetails} from '../../interfaces/user-details';
import {UserService} from '../../services/user/user.service';

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
  showUserDetails = true;
  userDetails$!: Observable<UserDetails>;

  constructor(
    private router: Router,
    private userService: UserService
  ) { }

  ngOnInit(): void {
    this.checkLoginStatus();
    this.fetchUserDetails();
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
    this.showUserDetails = this.showLogoutBtn;
  }

  fetchUserDetails(): void {
    const urlQuery = '?accessToken=' + localStorage.getItem('accessToken');
    const url = localStorage.getItem('serverUrl') + '/users/userDetails' + urlQuery;
    this.userDetails$ = this.userService.getUserDetails(url);
  }
}
