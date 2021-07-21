import {GlobalVars} from "../../shared/globalVars";
import { Component, OnInit } from '@angular/core';
import {ResourceEntryDataInterface} from "../../shared/interfaces/resource-entry-data-interface";

@Component({
  selector: 'sf-resources',
  templateUrl: './resources.component.html',
  styleUrls: ['./resources.component.scss'],
})
export class ResourcesComponent implements OnInit {
  resources: ResourceEntryDataInterface;

  constructor(
    public globalVars: GlobalVars,
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
    this.globalVars.getResources().subscribe(resources => {
      if(resources) {
        this.resources = resources;
      } else {
        console.log('Error getting resources in resource component');
      }
    });
  }

  ngOnInit(): void {
  }
}
