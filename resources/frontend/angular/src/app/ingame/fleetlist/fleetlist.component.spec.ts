import { ComponentFixture, TestBed } from '@angular/core/testing';

import { FleetlistComponent } from './fleetlist.component';

describe('FleetlistComponent', () => {
  let component: FleetlistComponent;
  let fixture: ComponentFixture<FleetlistComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ FleetlistComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(FleetlistComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
