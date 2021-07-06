import {Component, Input, OnInit} from '@angular/core';
import {UserAwardedItem} from '../../interfaces/user-awarded-item';

@Component({
  selector: 'app-download-bill',
  templateUrl: './download-bill.component.html',
  styleUrls: ['./download-bill.component.sass']
})
export class DownloadBillComponent implements OnInit {

  buttonLabel = 'Download Bill';
  @Input() awardedItem!: UserAwardedItem;

  constructor() { }

  ngOnInit(): void {
  }

  onDownload(): void {
    const urlQuery = '?accessToken=' + localStorage.getItem('accessToken') + '&itemId=' + this.awardedItem.id;
    const url = localStorage.getItem('serverUrl') + '/items/downloadBill' + urlQuery;
    window.open(url);
  }
}
