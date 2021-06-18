import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-navbar',
  templateUrl: './navbar.component.html',
  styleUrls: ['./navbar.component.sass']
})
export class NavbarComponent implements OnInit {

  navbarMatIcon = 'phonelink';
  navbarTitle = 'AUCTION APPLICATION';

  constructor() { }

  ngOnInit(): void {
  }

}
