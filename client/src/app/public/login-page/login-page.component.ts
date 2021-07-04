import { Component, OnInit } from '@angular/core';
import {FormBuilder, Validators} from '@angular/forms';
import {ItemService} from '../../services/item/item.service';
import {SnackbarService} from '../../services/snackbar/snackbar.service';
import {Router} from '@angular/router';
import {AccessToken} from '../../interfaces/access-token';
import {ItemEventListenerService} from '../../services/item-event-listener/item-event-listener.service';
import {UserService} from '../../services/user/user.service';

@Component({
  selector: 'app-login-page',
  templateUrl: './login-page.component.html',
  styleUrls: ['./login-page.component.sass']
})
export class LoginPageComponent implements OnInit {

  title = 'Sign in';
  submitButtonLabel = 'Sign in';
  showLoginError = false;
  loginErrorMessage = 'Invalid username or password';

  accessToken: AccessToken = {
    id: undefined,
    username: '',
    token: '',
  };

  constructor(
    private formBuilder: FormBuilder,
    private router: Router,
    private itemService: ItemService,
    private userService: UserService,
    private snackbarService: SnackbarService,
    private itemEventListenerService: ItemEventListenerService
  ) {
    this.subscribeForItemEvents();
  }

  textInputValidators = [Validators.required, Validators.maxLength(100)];

  loginForm = this.formBuilder.group({
    username: ['', this.textInputValidators],
    password: ['', this.textInputValidators]
  });

  ngOnInit(): void {
    localStorage.removeItem('accessToken');
    localStorage.removeItem('permissions');
    this.itemEventListenerService.onChangeAuthentication();
  }

  subscribeForItemEvents(): void {
    this.itemEventListenerService.itemEventFailureEmit$.subscribe(error => {
      this.onFailure(error);
    });
  }

  onLogin(): void {
    this.showLoginError = false;
    const urlQuery = '?username=' + this.loginForm.value.username + '&password=' + this.loginForm.value.password;
    const url = localStorage.getItem('serverUrl') + '/accessToken' + urlQuery;
    this.userService.getAccessToken(url)
      .subscribe(accessToken => {
        this.accessToken = accessToken;
        this.postLoginAction();
      });
  }

  onFailure(error: any): void {
    if (error.status === 401 || error.status === 404) {
      this.showLoginError = true;
    } else {
      this.snackbarService.openSnackBar('Request Failed!');
    }
  }

  postLoginAction(): void {
    if (this.accessToken.token) {
      localStorage.setItem('accessToken', this.accessToken.token);
      this.itemEventListenerService.onChangeAuthentication();
      this.router.navigate(['/home']).then(() => {
        window.location.reload();
      });
    }
  }
}
