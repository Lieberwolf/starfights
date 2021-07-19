import { ComponentFixture, TestBed } from '@angular/core/testing';

import { TechtreeComponent } from './techtree.component';

describe('TechtreeComponent', () => {
  let component: TechtreeComponent;
  let fixture: ComponentFixture<TechtreeComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ TechtreeComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(TechtreeComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
