import { Component, OnInit } from '@angular/core';
import {ResourcesService} from "../../shared/resources.service";
import {ActivatedRoute} from "@angular/router";
import {ProfileService} from "../../shared/profile.service";

@Component({
  selector: 'sf-resources',
  templateUrl: './resources.component.html',
  styleUrls: ['./resources.component.scss']
})
export class ResourcesComponent implements OnInit {

  resources: any;

  constructor(
    private resourceService: ResourcesService,
    private route: ActivatedRoute,
    private profileService: ProfileService,
  ) {
    const params = this.route.snapshot.paramMap;
    console.log(params.keys);

    this.resources = '';

    let user = localStorage.getItem('user') || '';
    let user_id = JSON.parse(user).id;

    this.profileService.getStartPlanet(user_id).then((val) => {
      resourceService.getPlanetaryResourcesByPlanetId(val, user_id).subscribe(
        data => {
          this.resources = data
        },
        error => {},
        () => {
          this.enableResourceCounters(this.resources);
        });
    });


  }

  ngOnInit(): void {
  }

  enableResourceCounters(resourceData: any): void {

    let fe =  {
      max: resourceData[1].fe,
      rate: resourceData[0].rate_fe,
    };

    let lut =  {
      max: resourceData[1].lut,
      rate: resourceData[0].rate_lut,
    };

    let cry =  {
      max: resourceData[1].cry,
      rate: resourceData[0].rate_cry,
    };

    let h2o =  {
      max: resourceData[1].h2o,
      rate: resourceData[0].rate_h2o,
    };

    let h2 =  {
      max: resourceData[1].h2,
      rate: resourceData[0].rate_h2,
    };

    let feInterval = setInterval(() => {
      this.resources[0].fe += (fe.rate / (60 * 60 * 2));
      if(this.resources[0].fe > fe.max) {
        this.resources[0].fe = fe.max;
        clearInterval(feInterval);
      }
    }, 500);

    let lutInterval = setInterval(() => {
      this.resources[0].lut += (lut.rate / (60 * 60 * 2));
      if(this.resources[0].lut > lut.max) {
        this.resources[0].lut = lut.max;
        clearInterval(lutInterval);
      }
    }, 500);

    let cryInterval = setInterval(() => {
      this.resources[0].cry += (cry.rate / (60 * 60 * 2));
      if(this.resources[0].cry > cry.max) {
        this.resources[0].cry = cry.max;
        clearInterval(cryInterval);
      }
    }, 500);

    let h2oInterval = setInterval(() => {
      this.resources[0].h2o += (h2o.rate / (60 * 60 * 2));
      if(this.resources[0].h2o > h2o.max) {
        this.resources[0].h2o = h2o.max
        clearInterval(h2oInterval);
      }
    }, 500);

    let h2Interval = setInterval(() => {
      this.resources[0].h2 += (h2.rate / (60 * 60 * 2));
      if(this.resources[0].h2 > h2.max) {
        this.resources[0].h2 = h2.max;
        clearInterval(h2Interval);
      }
    }, 500);

  }

}
