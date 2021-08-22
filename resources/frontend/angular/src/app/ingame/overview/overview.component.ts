import {GlobalVars} from "../../shared/globalVars";
import {Component, Inject, Input, OnInit} from '@angular/core';
import {OverviewData, OverviewService} from "../../shared/services/services.module";
import {OverviewBuildingProcessDataInterface} from "../../shared/interfaces/overview-building-process-data-interface";

@Component({
  selector: 'sf-overview',
  templateUrl: './overview.component.html',
  styleUrls: ['./overview.component.scss'],
  providers: [GlobalVars]
})
export class OverviewComponent implements OnInit {
  date?: String;
  user_name?: String;
  total_points?: number;
  total_planets?: number;
  planet_id?: number;
  processes:Array<OverviewBuildingProcessDataInterface>;

  milliseconds?:number;
  @Input('data-name') finished:string= "";


  constructor(
    private overviewService: OverviewService,
    @Inject(OverviewData)
    public data: OverviewData,
    public globalVars: GlobalVars,
  ) {
    this.processes = [];
    this.globalVars.getUser().subscribe(user => {
      if(user) {
        this.user_name = user.username;
        this.total_points = 0;
        this.date = new Date().toDateString();
        this.globalVars.getPlanetId().subscribe(planet_id => {
          if(planet_id) {
            this.planet_id = planet_id;
            this.globalVars.getPlanets().subscribe(planets => {
              if(planets) {
                this.total_planets = planets.length;
                this.overviewService.getOverview(this.planet_id).subscribe(data => {
                  if(data) {
                    this.data = data;
                    this.processes = data.planet.processes;
                    this.processes.forEach(function(process) {
                      process.timeleft = (Date.parse(process.finished_at)/1000) - (Date.now()/1000);
                    });
                    this.globalVars.setGlobalProcesses(this.processes);
                    this.total_points = data.points.allPlanetPoints + data.points.allResearchPoints;
                  } else {
                    console.log('Error getting overview data in overview component');
                  }
                });
              } else {
                console.log('Error getting planets in overview component');
              }
            });
          } else {
            console.log('Error getting planet_id in overview component');
          }
        });
      } else {
        console.log('Error getting user in overview component');
      }
    });
  }

  ngOnInit(): void {
  }
}
