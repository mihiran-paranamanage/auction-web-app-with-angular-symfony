import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-forbidden',
  templateUrl: './forbidden.component.html',
  styleUrls: ['./forbidden.component.sass']
})
export class ForbiddenComponent implements OnInit {

  forbiddenTitle = 'Forbidden!';

  constructor() { }

  ngOnInit(): void {
  }
}
