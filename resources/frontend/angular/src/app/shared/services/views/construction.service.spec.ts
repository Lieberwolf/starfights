import { TestBed } from '@angular/core/testing';

import { ConstructionService } from './construction.service';

describe('ConstructionService', () => {
  let service: ConstructionService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(ConstructionService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
