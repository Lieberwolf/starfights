import { Injectable } from '@angular/core';
import {HttpClient} from "@angular/common/http";
import {BehaviorSubject, Observable} from "rxjs";
import {ResourceEntryDataInterface} from "../../interfaces/resource-entry-data-interface";

@Injectable({
  providedIn: 'root'
})

export class ResourcesService {
  subject: BehaviorSubject<ResourceEntryDataInterface>;
  resources: ResourceEntryDataInterface;

  constructor(
    private http: HttpClient,
  ) {
    const planet_id = parseInt(localStorage.getItem('planet_id') || '');
    const user = localStorage.getItem('user') || '';
    const user_id = JSON.parse(user).id;
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
    this.subject = new BehaviorSubject(this.resources);
    this.getPlanetaryResourcesByPlanetId(planet_id, user_id).subscribe(data => {
      this.enableResourceCounters(data);
      this.resources = data;
    })
  }

  getResources(): BehaviorSubject<ResourceEntryDataInterface> {
    return this.subject;
  }

  setResources(data: ResourceEntryDataInterface): void {
    this.subject.next(data);
    this.resources = data;
  }

  getPlanetaryResourcesByPlanetId(planet_id: Number, user_id: Number): Observable<any> {
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

    setInterval(() => {
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

      this.setResources(this.resources);
    }, 500);
  }
}
