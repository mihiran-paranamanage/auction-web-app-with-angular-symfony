import {Component, Inject, OnInit} from '@angular/core';
import {FormBuilder} from '@angular/forms';
import {MAT_DIALOG_DATA} from '@angular/material/dialog';
import {UserDetails} from '../../interfaces/user-details';
import {CommonService} from '../../services/common/common.service';

@Component({
  selector: 'app-user-details-form',
  templateUrl: './user-details-form.component.html',
  styleUrls: ['./user-details-form.component.sass']
})
export class UserDetailsFormComponent implements OnInit {

  constructor(
    private commonService: CommonService,
    private formBuilder: FormBuilder,
    @Inject(MAT_DIALOG_DATA) public data: {
      userDetails: UserDetails,
      title: string,
      submitButtonLabel: string
    }
  ) { }

  userDetailsForm = this.formBuilder.group({
    username: [this.data.userDetails.username, this.commonService.getTextInputValidators()],
    userRoleName: [this.data.userDetails.userRoleName, this.commonService.getTextInputValidators()],
    firstName: [this.data.userDetails.firstName, this.commonService.getTextInputValidators()],
    lastName: [this.data.userDetails.lastName, this.commonService.getTextInputValidators()],
    email: [this.data.userDetails.email, this.commonService.getEmailInputValidators()],
    password: ['', this.commonService.getPasswordInputValidators()],
    passwordConfirmation: ['', this.commonService.getPasswordConfirmationInputValidators()],
    accessToken: [localStorage.getItem('accessToken')]
  });

  ngOnInit(): void {
  }
}
