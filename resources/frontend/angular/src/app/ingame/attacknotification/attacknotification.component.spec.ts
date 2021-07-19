import { ComponentFixture, TestBed } from '@angular/core/testing';

import { AttacknotificationComponent } from './attacknotification.component';

describe('AttacknotificationComponent', () => {
  let component: AttacknotificationComponent;
  let fixture: ComponentFixture<AttacknotificationComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ AttacknotificationComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(AttacknotificationComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
