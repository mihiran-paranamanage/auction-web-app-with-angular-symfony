import { ComponentFixture, TestBed } from '@angular/core/testing';

import { UserAwardedItemHistoryComponent } from './user-awarded-item-history.component';

describe('UserAwardedItemHistoryComponent', () => {
  let component: UserAwardedItemHistoryComponent;
  let fixture: ComponentFixture<UserAwardedItemHistoryComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ UserAwardedItemHistoryComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(UserAwardedItemHistoryComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
