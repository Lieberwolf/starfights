import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ResourceviewComponent } from './resourceview.component';

describe('ResourceviewComponent', () => {
  let component: ResourceviewComponent;
  let fixture: ComponentFixture<ResourceviewComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ ResourceviewComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(ResourceviewComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
