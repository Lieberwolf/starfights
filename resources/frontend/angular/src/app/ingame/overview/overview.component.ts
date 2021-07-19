import { Component, OnInit } from '@angular/core';
import {OverviewData, OverviewService} from "../../shared/overview.service";

@Component({
  selector: 'sf-overview',
  templateUrl: './overview.component.html',
  styleUrls: ['./overview.component.scss']
})
export class OverviewComponent implements OnInit {
  date: String;
  user_name: String;
  total_points: number;
  total_planets: number;

  constructor(
    private overviewService: OverviewService,
    public data: OverviewData,
  ) {
    let planet_id = JSON.parse(localStorage.getItem('planet_id') || '');

    this.user_name = JSON.parse(localStorage.getItem('user') || '').username;
    this.total_planets = JSON.parse(localStorage.getItem('allPlanets') || '').length;
    this.total_points = 0;
    this.date = new Date().toDateString();

    if(planet_id) {
      this.overviewService.getOverview(planet_id).subscribe((data) => {
        this.data = data;
        this.total_points = data.points.allPlanetPoints + data.points.allResearchPoints;
      });
    }

  }

  ngOnInit(): void {
  }

}
