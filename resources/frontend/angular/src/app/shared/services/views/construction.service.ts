import { Injectable } from '@angular/core';
import {HttpClient} from "@angular/common/http";
import {Observable} from "rxjs";

@Injectable({
  providedIn: 'root'
})
export class ConstructionService {

  constructor(private http: HttpClient) { }

  getAllAvailableBuildings(planet_id: Number, user_id: Number): Observable<any> {
    return this.http.get('http://127.0.0.1:8000/api/data/' + planet_id + '/' + user_id);
  }
}
