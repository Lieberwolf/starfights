import {Component, EventEmitter, Inject, Input, OnInit, NgModule} from '@angular/core';
import {OverviewData, OverviewService, PlanetService} from "../../shared/services/services.module";
import {LocalStorageService} from "../../shared/services/globals/local-storage.service";
import {OverviewBuildingProcessDataInterface} from "../../shared/interfaces/overview-building-process-data-interface";
import {Subject, Subscription, timer} from 'rxjs';
import {switchMap, take, tap} from "rxjs/operators";

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
  processes?:Array<OverviewBuildingProcessDataInterface>;

  milliseconds?:number;
  @Input('data-name') finished:string= "";


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
          /*
          // check if data in local storage exist, else get new
          let prefetchedData = JSON.parse(this.localStorage.getItem('p-' + this.planet_id));
          if(prefetchedData.data != null) {
            this.data = prefetchedData.data;
            this.total_points = prefetchedData.data.points.allPlanetPoints + prefetchedData.data.points.allResearchPoints;
          }
          else {
            this.overviewService.getOverview(this.planet_id).subscribe((data) => {
              prefetchedData.data = data;
              this.localStorage.setItem('p-' + this.planet_id, JSON.stringify(prefetchedData));
              this.data = data;
              this.total_points = data.points.allPlanetPoints + data.points.allResearchPoints;
            });
          }
          */
          this.overviewService.getOverview(this.planet_id).subscribe((data) => {
            this.data = data;
            this.processes = this.data.planet?.processes;
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
    console.log(this.finished)

    setInterval(()=>{
      this.milliseconds = Date.parse(this.finished) - Date.now()
    }, 1000);

  }


}
