import { Injectable } from '@angular/core';
import {HttpClient} from "@angular/common/http";
import {Observable} from "rxjs";

@Injectable({
  providedIn: 'root'
})

export class ProfileService {

  constructor(private http: HttpClient) { }

  getProfile(user_id: Number): Observable<any> {
    return this.http.get('http://127.0.0.1:8000/api/data/getProfile/' + user_id);
  }

  getStartPlanet(user_id: Number): Promise<any> {

    return new Promise(resolve => {
      this.getProfile(user_id).subscribe(data => {
        resolve(data.start_planet);
      });
    });

  }

}
