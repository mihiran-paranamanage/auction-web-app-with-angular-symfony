import {Component, Inject, OnInit} from '@angular/core';
import {AbstractControl, FormBuilder, FormGroup, Validators} from "@angular/forms";
import {MAT_DIALOG_DATA} from "@angular/material/dialog";
import {UserDetails} from "../../interfaces/user-details";

@Component({
  selector: 'app-user-details-form',
  templateUrl: './user-details-form.component.html',
  styleUrls: ['./user-details-form.component.sass']
})
export class UserDetailsFormComponent implements OnInit {

  constructor(
    private formBuilder: FormBuilder,
    @Inject(MAT_DIALOG_DATA) public data: {
      userDetails: UserDetails,
      title: string,
      submitButtonLabel: string
    }
  ) { }

  textInputValidators = [Validators.required, Validators.maxLength(100)];
  emailInputValidators = [Validators.required, Validators.maxLength(100), Validators.pattern(/^\w+@[a-zA-Z_]+?\.[a-zA-Z]{2,3}$/)];
  passwordInputValidators = [Validators.maxLength(100)];
  passwordConfirmationInputValidators = [Validators.maxLength(100), this.checkPasswordConfirmation];

  userDetailsForm = this.formBuilder.group({
    username: [this.data.userDetails.username, this.textInputValidators],
    userRoleName: [this.data.userDetails.userRoleName, this.textInputValidators],
    firstName: [this.data.userDetails.firstName, this.textInputValidators],
    lastName: [this.data.userDetails.lastName, this.textInputValidators],
    email: [this.data.userDetails.email, this.emailInputValidators],
    password: ['', this.passwordInputValidators],
    passwordConfirmation: ['', this.passwordConfirmationInputValidators],
    accessToken: [localStorage.getItem('accessToken')]
  });

  checkPasswordConfirmation(control: AbstractControl): { [key: string]: boolean } | null {
    // @ts-ignore
    if (control.parent && control.parent.controls && control.parent.controls.password && control.parent.controls.password.value) {
      // @ts-ignore
      return control.parent.controls.password.value === control.value ? null : { mismatch: true };
    } else {
      return !control.value ? null : { mismatch: true };
    }
  }

  ngOnInit(): void {
  }
}
