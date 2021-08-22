import { Injectable } from '@angular/core';
import {HttpClient} from "@angular/common/http";
import {BehaviorSubject, Observable} from "rxjs";
import {LocalStorageService} from "./local-storage.service";

export class PlanetBaseData {
  id?: number;
  galaxy?: number;
  system?: number;
  planet?: number;
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

  getUniverse(galaxy: number, system: number): Observable<any> {
    return this.http.get('http://127.0.0.1:8000/api/data/universePart/' + galaxy+'/'+system );
  }

  getAllUserPlanetsInit(user_id: number | undefined): Observable<any> {
    if(user_id) {
      return this.http.get('http://127.0.0.1:8000/api/data/getAllUserPlanets/' + user_id);
    } else {
      return this.http.get('');
    }
  }

  async getAllUserPlanets(): Promise<BehaviorSubject<any>> {
    return this.planet_all_subject;
  }

  getPlanetById(planet_id:number):Observable<PlanetBaseData>{
    return this.http.get('http://127.0.0.1:8000/api/data/getPlanetById/' +planet_id);
  }


}
