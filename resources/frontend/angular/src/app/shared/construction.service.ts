import { Injectable } from '@angular/core';
import {HttpClient} from "@angular/common/http";
import {Observable} from "rxjs";

@Injectable({
  providedIn: 'root'
})
export class ConstructionService {

  constructor(private http: HttpClient) { }

  getAllAvailableBuildings(planet_id: Number, user_id: Number): Observable<any> {
    return this.http.get('http://127.0.0.1:8000/api/data/getAllAvailableBuildings/' + planet_id + '/' + user_id);
  }

  startConstruction(planet_id: Number, building_id: Number): Observable<any> {
    return this.http.get('http://127.0.0.1:8000/api/data/startConstruction/' + planet_id + '/' + building_id);
  }

  getConstruction(planet_id: Number): Observable<any> {
    return this.http.get('http://127.0.0.1:8000/api/data/getConstruction/' + planet_id);
  }

  cancelConstruction(planet_id: Number): Observable<any> {
    return this.http.get('http://127.0.0.1:8000/api/data/cancelConstruction/' + planet_id);
  }
}
