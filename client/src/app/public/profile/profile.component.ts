import {AfterViewInit, Component} from '@angular/core';
import {Observable} from 'rxjs';
import {UserDetails} from '../../interfaces/user-details';
import {UserService} from "../../services/user/user.service";
import {ItemEventListenerService} from "../../services/item-event-listener/item-event-listener.service";
import {SnackbarService} from "../../services/snackbar/snackbar.service";
import {MatDialog, MatDialogRef} from "@angular/material/dialog";
import {Item} from "../../interfaces/item";
import {ItemDetailsFormComponent} from "../item-details-form/item-details-form.component";
import {ItemForm} from "../../interfaces/item-form";
import {UserDetailsForm} from "../../interfaces/user-details-form";
import {UserDetailsFormComponent} from "../user-details-form/user-details-form.component";

@Component({
  selector: 'app-profile',
  templateUrl: './profile.component.html',
  styleUrls: ['./profile.component.sass']
})
export class ProfileComponent implements AfterViewInit {

  title = 'My Profile';
  userDetails$!: Observable<UserDetails>;
  userDetails: UserDetails = {};

  constructor(
    private userService: UserService,
    private itemEventListenerService: ItemEventListenerService,
    private snackbarService: SnackbarService,
    private matDialog: MatDialog
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
    this.userService.getUserDetails(url)
      .subscribe(userDetails => {
        this.userDetails = userDetails;
      });
  }

  subscribeForItemEvents(): void {
    this.itemEventListenerService.itemEventUpdateEmit$.subscribe(item => {
      this.onUpdatedUserDetails(item);
    });
    this.itemEventListenerService.itemEventFailureEmit$.subscribe(error => {
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
      username: undefined,
      password: result.password ? result.password : undefined,
      userRoleName: undefined,
      email: result.email,
      firstName: result.firstName,
      lastName: result.lastName,
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
