import {Component, OnDestroy, OnInit} from '@angular/core';
import {FormBuilder} from '@angular/forms';
import {ItemService} from '../../services/item/item.service';
import {SnackbarService} from '../../services/snackbar/snackbar.service';
import {Router} from '@angular/router';
import {AccessToken} from '../../interfaces/access-token';
import {UserService} from '../../services/user/user.service';
import {EventListenerService} from '../../services/event-listener/event-listener.service';
import {CommonService} from '../../services/common/common.service';
import {Subscription} from 'rxjs';

@Component({
  selector: 'app-login-page',
  templateUrl: './login-page.component.html',
  styleUrls: ['./login-page.component.sass']
})
export class LoginPageComponent implements OnInit, OnDestroy {

  title = 'Sign in';
  submitButtonLabel = 'Sign in';
  showLoginError = false;
  loginErrorMessage = 'Invalid username or password';
  subscriptionEventFailureEmit?: Subscription;

  accessToken: AccessToken = {
    id: undefined,
    userId: undefined,
    username: '',
    token: '',
  };

  constructor(
    private formBuilder: FormBuilder,
    private router: Router,
    private itemService: ItemService,
    private userService: UserService,
    private snackbarService: SnackbarService,
    private eventListenerService: EventListenerService,
    private commonService: CommonService
  ) {
    this.subscribeForEvents();
  }

  loginForm = this.formBuilder.group({
    username: ['', this.commonService.getTextInputValidators()],
    password: ['', this.commonService.getTextInputValidators()]
  });

  ngOnInit(): void {
    localStorage.removeItem('loggedInUserId');
    localStorage.removeItem('accessToken');
    localStorage.removeItem('permissions');
    this.eventListenerService.onChangeAuthentication();
  }

  ngOnDestroy(): void {
    this.subscriptionEventFailureEmit?.unsubscribe();
  }

  subscribeForEvents(): void {
    this.subscriptionEventFailureEmit = this.eventListenerService.eventFailureEmit$.subscribe(error => {
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
      // @ts-ignore
      localStorage.setItem('loggedInUserId', this.accessToken.userId);
      localStorage.setItem('accessToken', this.accessToken.token);
      this.eventListenerService.onChangeAuthentication();
      this.router.navigate(['/home']).then(() => {
        window.location.reload();
      });
    }
  }
}
