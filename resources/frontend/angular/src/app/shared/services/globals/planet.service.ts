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

  constructor(
    private http: HttpClient,
    private localStorage: LocalStorageService,
  ) {
    this.planet_id = this.localStorage.getItem('planet_id');
    this.planet_id_subject = new BehaviorSubject(this.planet_id);
  }

  getAllUserPlanets(user_id: Number): Observable<any> {
    return this.http.get('http://127.0.0.1:8000/api/data/getAllUserPlanets/' + user_id);
  }

  async getActivePlanet(): Promise<BehaviorSubject<number>> {
    return this.planet_id_subject;
  }

  setActivePlanet(val: number): void {
    this.localStorage.setItem('planet_id', val);
    this.planet_id_subject.next(val);
  }
}
