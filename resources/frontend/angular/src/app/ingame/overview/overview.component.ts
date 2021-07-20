import {Component, Inject, OnInit} from '@angular/core';
import {OverviewData, OverviewService, PlanetService} from "../../shared/services/services.module";
import {LocalStorageService} from "../../shared/services/globals/local-storage.service";

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
  planet_id: number;

  constructor(
    private overviewService: OverviewService,
    private localStorage: LocalStorageService,
    private planetService: PlanetService,
    @Inject(OverviewData)
    public data: OverviewData,
  ) {
    this.planet_id = 0;
    this.planetService.getActivePlanet().then(resolve => {
      resolve.subscribe(data => {
        this.planet_id = data;
        if(this.planet_id) {
          this.overviewService.getOverview(this.planet_id).subscribe((data) => {
            this.data = data;
            this.total_points = data.points.allPlanetPoints + data.points.allResearchPoints;
          });
        }
      });
    });

    this.user_name = JSON.parse(this.localStorage.getItem('user') || '').username;
    this.total_planets = JSON.parse(this.localStorage.getItem('allPlanets') || '').length;
    this.total_points = 0;
    this.date = new Date().toDateString();
  }

  ngOnInit(): void {
  }

}
