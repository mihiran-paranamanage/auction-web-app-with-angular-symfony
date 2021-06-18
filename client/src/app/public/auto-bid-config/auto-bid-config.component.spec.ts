import { ComponentFixture, TestBed } from '@angular/core/testing';

import { AutoBidConfigComponent } from './auto-bid-config.component';

describe('AutoBidConfigComponent', () => {
  let component: AutoBidConfigComponent;
  let fixture: ComponentFixture<AutoBidConfigComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ AutoBidConfigComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(AutoBidConfigComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
