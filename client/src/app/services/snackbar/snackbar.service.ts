import { Injectable } from '@angular/core';
import {
  MatSnackBar,
  MatSnackBarHorizontalPosition,
  MatSnackBarVerticalPosition,
} from '@angular/material/snack-bar';

@Injectable({
  providedIn: 'root'
})
export class SnackbarService {

  constructor(
    private matSnackBar: MatSnackBar
  ) { }

  horizontalPosition: MatSnackBarHorizontalPosition = 'start';
  verticalPosition: MatSnackBarVerticalPosition = 'bottom';

  notificationHorizontalPosition: MatSnackBarHorizontalPosition = 'end';
  notificationVerticalPosition: MatSnackBarVerticalPosition = 'top';

  openSnackBar(message: string): void {
    this.matSnackBar.open(message, 'Dismiss', {
      duration: 2000,
      horizontalPosition: this.horizontalPosition,
      verticalPosition: this.verticalPosition
    });
  }

  openSnackBarNotification(message: string): void {
    this.matSnackBar.open(message, 'Dismiss', {
      horizontalPosition: this.notificationHorizontalPosition,
      verticalPosition: this.notificationVerticalPosition
    });
  }
}
