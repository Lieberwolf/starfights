import { Injectable } from '@angular/core';
import {HttpClient} from "@angular/common/http";
import {Observable} from "rxjs";
import {ResourceEntryDataInterface} from "../../interfaces/resource-entry-data-interface";
import {GlobalVars} from "../../globalVars";

@Injectable({
  providedIn: 'root'
})

export class ResourcesService {
  resources: ResourceEntryDataInterface;
  planet_id: number;
  interval?: number;

  constructor(
    private http: HttpClient,
    public globalVars: GlobalVars,
  ) {
    this.planet_id = 0;
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
    this.globalVars.getUser().subscribe(user => {
      if(user) {
        this.globalVars.getPlanetId().subscribe(planet_id => {
          if(planet_id) {
            this.planet_id = planet_id;
            this.getPlanetaryResourcesByPlanetId(planet_id, user.id).subscribe(resources => {
              if(resources) {
                this.globalVars.setResources(resources);
                this.resources = resources;
                //this.enableResourceCounters(resources);
              } else {
                console.log('Error getting resources in ResourceService');
              }
            });
          } else {
            console.log('Error getting planet in ResourceService');
          }
        });
      } else {
        console.log('Error getting user in ResourceService');
      }
    });
  }

  getPlanetaryResourcesByPlanetId(planet_id: number | undefined, user_id: number | undefined): Observable<any> {
    return this.http.get('http://127.0.0.1:8000/api/data/getPlanetaryResourcesByPlanetId/' + planet_id + '/' + user_id);
  }

  enableResourceCounters(resourceData: ResourceEntryDataInterface): void {
    let fe =  {
      max: resourceData.storages.fe,
      rate: resourceData.data.rate_fe,
    };

    let lut =  {
      max: resourceData.storages.lut,
      rate: resourceData.data.rate_lut,
    };

    let cry =  {
      max: resourceData.storages.cry,
      rate: resourceData.data.rate_cry,
    };

    let h2o =  {
      max: resourceData.storages.h2o,
      rate: resourceData.data.rate_h2o,
    };

    let h2 =  {
      max: resourceData.storages.h2,
      rate: resourceData.data.rate_h2,
    };

    this.clearCounter();

    this.interval = setInterval(() => {
      this.resources.data.fe += (fe.rate / (60 * 60 * 2));
      if(this.resources.data.fe > fe.max) {
        this.resources.data.fe = fe.max;
      }
      this.resources.data.lut += (lut.rate / (60 * 60 * 2));
      if(this.resources.data.lut > lut.max) {
        this.resources.data.lut = lut.max;
      }
      this.resources.data.cry += (cry.rate / (60 * 60 * 2));
      if(this.resources.data.cry > cry.max) {
        this.resources.data.cry = cry.max;
      }
      this.resources.data.h2o += (h2o.rate / (60 * 60 * 2));
      if(this.resources.data.h2o > h2o.max) {
        this.resources.data.h2o = h2o.max;
      }
      this.resources.data.h2 += (h2.rate / (60 * 60 * 2));
      if(this.resources.data.h2 > h2.max) {
        this.resources.data.h2 = h2.max;
      }
      console.log('interval?');
      this.globalVars.setResources(this.resources);
    }, 500);
  }

  clearCounter(): void {
    clearInterval(this.interval);
  }
}
