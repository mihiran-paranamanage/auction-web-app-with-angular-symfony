import { Component, OnInit } from '@angular/core';
import {ItemEventListenerService} from '../../services/item-event-listener/item-event-listener.service';

@Component({
  selector: 'app-main',
  templateUrl: './main.component.html',
  styleUrls: ['./main.component.sass']
})
export class MainComponent implements OnInit {

  authenticated = !!localStorage.getItem('accessToken');

  constructor(
    private itemEventListenerService: ItemEventListenerService
  ) {
    this.subscribeForItemEvents();
  }

  ngOnInit(): void {
    localStorage.setItem('serverUrl', 'http://localhost:8001/api');
  }

  subscribeForItemEvents(): void {
    this.itemEventListenerService.onChangeAuthenticationEventEmit$.subscribe(() => {
      this.onChangeAuthentication();
    });
  }

  onChangeAuthentication(): void {
    this.authenticated = !!localStorage.getItem('accessToken');
  }
}
