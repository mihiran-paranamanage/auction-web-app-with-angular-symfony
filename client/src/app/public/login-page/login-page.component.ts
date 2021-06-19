import { Component, OnInit } from '@angular/core';
import {FormBuilder, Validators} from '@angular/forms';
import {ItemService} from '../../services/item/item.service';
import {SnackbarService} from '../../services/snackbar/snackbar.service';
import {Router} from '@angular/router';
import {AccessToken} from '../../interfaces/access-token';
import {ItemEventListenerService} from '../../services/item-event-listener/item-event-listener.service';

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
  }

  subscribeForItemEvents(): void {
    this.itemEventListenerService.itemEventFailureEmit$.subscribe(error => {
      this.onFailure(error);
    });
  }

  onLogin(): void {
    if (this.isCredentialsValid()) {
      this.showLoginError = false;
      const url = localStorage.getItem('serverUrl') + '/accessToken?username=' + this.loginForm.value.username;
      this.itemService.getAccessToken(url)
        .subscribe(accessToken => {
          this.accessToken = accessToken;
          this.postLoginAction();
        });
    } else {
      this.showLoginError = true;
    }
  }

  onFailure(error: any): void {
    this.snackbarService.openSnackBar('Request Failed!');
  }

  postLoginAction(): void {
    localStorage.setItem('accessToken', this.accessToken.token ? this.accessToken.token : '');
    this.itemEventListenerService.onChangeAuthentication();
    this.router.navigate(['/home']).then(() => {
      window.location.reload();
    });
  }

  isCredentialsValid(): boolean {
    const credentials = {
      admin1: 'admin1',
      admin2: 'admin2',
      user1: 'user1',
      user2: 'user2'
    };
    const username = this.loginForm.value.username;
    const password = this.loginForm.value.password;
    // @ts-ignore
    return (username in credentials) && (credentials[username] === password);
  }
}
