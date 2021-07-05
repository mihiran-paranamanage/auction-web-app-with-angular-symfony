import { ComponentFixture, TestBed } from '@angular/core/testing';

import { UserBidHistoryComponent } from './user-bid-history.component';

describe('UserBidHistoryComponent', () => {
  let component: UserBidHistoryComponent;
  let fixture: ComponentFixture<UserBidHistoryComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ UserBidHistoryComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(UserBidHistoryComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
