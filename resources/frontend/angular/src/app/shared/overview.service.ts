import { Injectable } from '@angular/core';
import {HttpClient} from "@angular/common/http";
import {Observable} from "rxjs";

export class ShipEntryData {
  ship_id?: Number;
  ship_name?: String;
  amount?: number;
}

export class TurretEntryData {
  turret_id?: Number;
  turret_name?: String;
  amount?: number;
}

export class OverviewData {
  planet?: {
    information?: {
      id?: Number,
      galaxy?: Number,
      system?: Number,
      planet?: Number,
      planet_name?: String,
      image?: String
    },
    maxPlanets?: number,
    shipsAtPlanet?: Array<ShipEntryData>,
    turretsAtPlanet?: Array<TurretEntryData>,
    processes?: Array<any>,
  };
  points?: {
    allPlanetPoints?: number,
    allResearchPoints?: number,
  };
}

@Injectable({
  providedIn: 'root'
})
export class OverviewService {

  constructor(private http: HttpClient) { }

  getOverview(planet_id: Number): Observable<any> {
    return this.http.get('http://127.0.0.1:8000/api/data/getOverview/' + planet_id);
  }
}
