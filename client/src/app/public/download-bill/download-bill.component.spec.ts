import { ComponentFixture, TestBed } from '@angular/core/testing';

import { DownloadBillComponent } from './download-bill.component';

describe('DownloadBillComponent', () => {
  let component: DownloadBillComponent;
  let fixture: ComponentFixture<DownloadBillComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ DownloadBillComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(DownloadBillComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
