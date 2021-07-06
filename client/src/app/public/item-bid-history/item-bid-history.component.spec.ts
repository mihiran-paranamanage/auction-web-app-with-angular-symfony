import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ItemBidHistoryComponent } from './item-bid-history.component';

describe('BidHistoryComponent', () => {
  let component: ItemBidHistoryComponent;
  let fixture: ComponentFixture<ItemBidHistoryComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ ItemBidHistoryComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(ItemBidHistoryComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
