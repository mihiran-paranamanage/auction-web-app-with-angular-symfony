import { ComponentFixture, TestBed } from '@angular/core/testing';

import { UserItemHistoryComponent } from './user-item-history.component';

describe('UserItemHistoryComponent', () => {
  let component: UserItemHistoryComponent;
  let fixture: ComponentFixture<UserItemHistoryComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ UserItemHistoryComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(UserItemHistoryComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
