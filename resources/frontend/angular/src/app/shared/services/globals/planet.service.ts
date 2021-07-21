import { Injectable } from '@angular/core';
import {HttpClient} from "@angular/common/http";
import {BehaviorSubject, Observable} from "rxjs";
import {LocalStorageService} from "./local-storage.service";

export class PlanetBaseData {
  id?: Number;
  galaxy?: Number;
  system?: Number;
  planet?: Number;
}

@Injectable({
  providedIn: 'root'
})
export class PlanetService {
  planet_id_subject: BehaviorSubject<number>;
  planet_id: number;
  planet_all_subject: BehaviorSubject<any>;
  planet_all: any;

  constructor(
    private http: HttpClient,
    private localStorage: LocalStorageService,
  ) {
    this.planet_all = this.localStorage.getItem('allPlanets');
    this.planet_all_subject = new BehaviorSubject(this.planet_all);
    this.planet_id = this.localStorage.getItem('planet_id');
    this.planet_id_subject = new BehaviorSubject(this.planet_id);
  }

  async getAllUserPlanetsInit(): Promise<BehaviorSubject<any>> {

    return new Promise(resolve => {
      this.http.get('http://127.0.0.1:8000/api/data/getAllUserPlanets/' + JSON.parse(this.localStorage.getItem('user')).id).subscribe((data) => {
        this.planet_all_subject.next(data);
        this.planet_all = data;
        this.localStorage.setItem('allPlanets', JSON.stringify(data));
      });
    });
  }

  async getAllUserPlanets(): Promise<BehaviorSubject<any>> {
    return this.planet_all_subject;
  }

  async getActivePlanet(): Promise<BehaviorSubject<number>> {
    return this.planet_id_subject;
  }

  setActivePlanet(val: number): void {
    this.localStorage.setItem('planet_id', val);
    this.planet_id_subject.next(val);
  }
}
