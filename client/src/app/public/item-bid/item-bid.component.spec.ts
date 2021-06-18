import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ItemBidComponent } from './item-bid.component';

describe('ItemBidComponent', () => {
  let component: ItemBidComponent;
  let fixture: ComponentFixture<ItemBidComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ ItemBidComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(ItemBidComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
