import { Injectable } from '@angular/core';
import {HttpClient} from "@angular/common/http";
import {Observable} from "rxjs";

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

  constructor(private http: HttpClient) { }

  getAllUserPlanets(user_id: Number): Observable<any> {
    return this.http.get('http://127.0.0.1:8000/api/data/getAllUserPlanets/' + user_id);
  }
}
