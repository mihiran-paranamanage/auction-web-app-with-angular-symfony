import {AfterViewInit, Component, OnDestroy} from '@angular/core';
import {Observable, Subscription} from 'rxjs';
import {UserDetails} from '../../interfaces/user-details';
import {UserService} from '../../services/user/user.service';
import {SnackbarService} from '../../services/snackbar/snackbar.service';
import {MatDialog, MatDialogRef} from '@angular/material/dialog';
import {UserDetailsForm} from '../../interfaces/user-details-form';
import {UserDetailsFormComponent} from '../user-details-form/user-details-form.component';
import {EventListenerService} from '../../services/event-listener/event-listener.service';

@Component({
  selector: 'app-profile',
  templateUrl: './profile.component.html',
  styleUrls: ['./profile.component.sass']
})
export class ProfileComponent implements AfterViewInit, OnDestroy {

  title = 'My Profile';
  userDetails$!: Observable<UserDetails>;
  userDetails: UserDetails = {};
  subscriptionEventUpdateEmit?: Subscription;
  subscriptionEventFailureEmit?: Subscription;

  constructor(
    private userService: UserService,
    private eventListenerService: EventListenerService,
    private snackbarService: SnackbarService,
    private matDialog: MatDialog
  ) {
    this.subscribeForEvents();
  }

  ngAfterViewInit(): void {
    this.fetchUserDetails();
  }

  ngOnDestroy(): void {
    this.subscriptionEventUpdateEmit?.unsubscribe();
    this.subscriptionEventFailureEmit?.unsubscribe();
  }

  fetchUserDetails(): void {
    const urlQuery = '?accessToken=' + localStorage.getItem('accessToken') + '&include=bids,awardedItems';
    const url = localStorage.getItem('serverUrl') + '/users/userDetails' + urlQuery;
    this.userDetails$ = this.userService.getUserDetails(url);
    this.userService.getUserDetails(url)
      .subscribe(userDetails => {
        this.userDetails = userDetails;
      });
  }

  subscribeForEvents(): void {
    this.subscriptionEventUpdateEmit = this.eventListenerService.eventUpdateEmit$.subscribe(item => {
      this.onUpdatedUserDetails(item);
    });
    this.subscriptionEventFailureEmit = this.eventListenerService.eventFailureEmit$.subscribe(error => {
      this.onFailure(error);
    });
  }

  onFailure(error: any): void {
    this.snackbarService.openSnackBar('Request Failed!');
  }

  onEdit(): void {
    const dialogRef = this.openDialog();
    dialogRef.afterClosed().subscribe(result => {
      if (result) {
        this.editProfile(result);
      }
    });
  }

  private openDialog(): MatDialogRef<any> {
    const userDetails: UserDetails = Object.assign({}, this.userDetails);
    return this.matDialog.open(UserDetailsFormComponent, {
      data: {
        userDetails,
        title: 'Edit Profile',
        submitButtonLabel: 'Save'
      }
    });
  }

  private editProfile(result: UserDetailsForm): void {
    const url = localStorage.getItem('serverUrl') + '/users/userDetails';
    const userDetails: UserDetails = {
      id: undefined,
      userRoleId: undefined,
      username: undefined,
      password: result.password ? result.password : undefined,
      userRoleName: undefined,
      email: result.email,
      firstName: result.firstName,
      lastName: result.lastName,
      bids: undefined,
      awardedItems: undefined,
      accessToken: result.accessToken
    };
    this.userService.updateUserDetails(url, userDetails)
      .subscribe();
  }

  onUpdatedUserDetails(userDetails: UserDetails): void {
    this.fetchUserDetails();
    this.snackbarService.openSnackBar('Profile Details Saved Successfully!');
  }
}
