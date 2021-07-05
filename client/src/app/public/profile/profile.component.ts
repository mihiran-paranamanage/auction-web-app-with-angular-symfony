import {AfterViewInit, Component} from '@angular/core';
import {Observable} from 'rxjs';
import {UserDetails} from '../../interfaces/user-details';
import {UserService} from "../../services/user/user.service";
import {ItemEventListenerService} from "../../services/item-event-listener/item-event-listener.service";
import {SnackbarService} from "../../services/snackbar/snackbar.service";

@Component({
  selector: 'app-profile',
  templateUrl: './profile.component.html',
  styleUrls: ['./profile.component.sass']
})
export class ProfileComponent implements AfterViewInit {

  title = 'My Profile';
  userDetails$!: Observable<UserDetails>;

  constructor(
    private userService: UserService,
    private itemEventListenerService: ItemEventListenerService,
    private snackbarService: SnackbarService
  ) {
    this.subscribeForItemEvents();
  }

  ngAfterViewInit(): void {
    this.fetchUserDetails();
  }

  fetchUserDetails(): void {
    const urlQuery = '?accessToken=' + localStorage.getItem('accessToken') + '&include=bids,awardedItems';
    const url = localStorage.getItem('serverUrl') + '/users/userDetails' + urlQuery;
    this.userDetails$ = this.userService.getUserDetails(url);
  }

  subscribeForItemEvents(): void {
    this.itemEventListenerService.itemEventFailureEmit$.subscribe(error => {
      this.onFailure(error);
    });
  }

  onFailure(error: any): void {
    this.snackbarService.openSnackBar('Request Failed!');
  }
}
