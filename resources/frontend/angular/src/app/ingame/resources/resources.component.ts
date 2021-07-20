import { Component, OnInit } from '@angular/core';
import {ResourcesService} from "../../shared/services/services.module";
import {ResourceEntryDataInterface} from "../../shared/interfaces/resource-entry-data-interface";
import {BehaviorSubject} from "rxjs";

@Component({
  selector: 'sf-resources',
  templateUrl: './resources.component.html',
  styleUrls: ['./resources.component.scss']
})
export class ResourcesComponent implements OnInit {
  resourcesBS: BehaviorSubject<ResourceEntryDataInterface>;
  resources: ResourceEntryDataInterface;

  constructor(
    private resourceService: ResourcesService,
  ) {
    this.resources = {
      data: {
        fe: 0,
        lut: 0,
        cry: 0,
        h2o: 0,
        h2: 0,
        rate_fe: 0,
        rate_lut: 0,
        rate_cry: 0,
        rate_h2o: 0,
        rate_h2: 0,
      },
      storages: {
        fe: 0,
        lut: 0,
        cry: 0,
        h2o: 0,
        h2: 0,
      }
    };
    this.resourcesBS = this.resourceService.getResources();
    this.resourcesBS.subscribe((data) => {
      this.resources = data;
    });
  }

  ngOnInit(): void {
  }


}
