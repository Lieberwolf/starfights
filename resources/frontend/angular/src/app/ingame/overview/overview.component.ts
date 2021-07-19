import { Component, OnInit } from '@angular/core';
import {OverviewData, OverviewService} from "../../shared/overview.service";

@Component({
  selector: 'sf-overview',
  templateUrl: './overview.component.html',
  styleUrls: ['./overview.component.scss']
})
export class OverviewComponent implements OnInit {
  date: String;
  totalPoints: number;

  constructor(
    private overviewService: OverviewService,
    public data: OverviewData) {
    let planet_id = JSON.parse(localStorage.getItem('planet_id') || '');

    if(planet_id) {
      this.overviewService.getOverview(planet_id).subscribe((data) => {
        this.data = data;
        this.totalPoints = data.points.allPlanetPoints + data.points.allResearchPoints;
      });
    }
    this.totalPoints = 0;
    this.date = new Date().toDateString();
  }

  ngOnInit(): void {
  }

}
