import { Component, OnInit } from '@angular/core';
import {EventListenerService} from '../../services/event-listener/event-listener.service';

@Component({
  selector: 'app-main',
  templateUrl: './main.component.html',
  styleUrls: ['./main.component.sass']
})
export class MainComponent implements OnInit {

  authenticated = !!localStorage.getItem('accessToken');

  constructor(
    private eventListenerService: EventListenerService
  ) {
    this.subscribeForEvents();
  }

  ngOnInit(): void {
    localStorage.setItem('serverUrl', 'http://localhost:8001/api');
    localStorage.setItem('webSocketUrl', 'ws://localhost:5001/');
  }

  subscribeForEvents(): void {
    this.eventListenerService.onChangeAuthenticationEventEmit$.subscribe(() => {
      this.onChangeAuthentication();
    });
  }

  onChangeAuthentication(): void {
    this.authenticated = !!localStorage.getItem('accessToken');
  }
}
