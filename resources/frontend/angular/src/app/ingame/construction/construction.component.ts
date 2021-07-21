import { Component, OnInit } from '@angular/core';
import {ConstructionService} from "../../shared/services/views/views.module";
import {ConstructionEntryDataInterface} from "../../shared/interfaces/construction-entry-data-interface";
import {Router} from "@angular/router";
import {ConstructionProcessDataInterface} from "../../shared/interfaces/construction-process-data-interface";
import {ResourceEntryDataInterface} from "../../shared/interfaces/resource-entry-data-interface";
import {PlanetBaseData, PlanetService, ResourcesService} from "../../shared/services/globals/globals.module";
import {LocalStorageService} from "../../shared/services/globals/local-storage.service";
import {GlobalVars} from "../../shared/globalVars";

@Component({
  selector: 'sf-construction',
  templateUrl: './construction.component.html',
  styleUrls: ['./construction.component.scss']
})
export class ConstructionComponent implements OnInit {
  planet_id?: number;
  planet?: PlanetBaseData;
  user_id: number | undefined;
  process?: ConstructionProcessDataInterface;
  processing: boolean;
  constructionEntries?: Array<ConstructionEntryDataInterface>;
  resources: ResourceEntryDataInterface;

  constructor(
    private constructionService: ConstructionService,
    private resourceService: ResourcesService,
    public router: Router,
    public globalVars: GlobalVars
  ) {
    this.resources = {
      data: {
        fe: 0,
        lut: 0,
        cry: 0,
        h2o: 0,
        h2: 0,
        rate_fe: 0,
        rate_lut: 0,
        rate_cry: 0,
        rate_h2o: 0,
        rate_h2: 0,
      },
      storages: {
        fe: 0,
        lut: 0,
        cry: 0,
        h2o: 0,
        h2: 0,
      }
    };
    this.processing = false;

    this.globalVars.getUser().subscribe(user => {
      if(user) {
        this.user_id = user.id;
        this.globalVars.getResources().subscribe(resources => {
          if(resources) {
            this.resources = resources;
            this.globalVars.getPlanetId().subscribe(planet_id => {
              if(planet_id) {
                this.planet_id = planet_id;
                this.constructionService.getConstruction(planet_id).subscribe(construction => {
                  /**
                   * if a building is currently build, this states true
                   */
                  if(Object.keys(construction).length) {
                    this.process = construction;
                    this.processing = false;
                  }
                  this.constructionService.getAllAvailableBuildings(this.planet_id, this.user_id).subscribe(data => {
                    this.constructionEntries = data;
                  });
                });
              } else {
                console.log('Error getting planet_id in construction component');
              }
            });
          } else {
            console.log('Error getting resources in construction component');
          }
        });
      } else {
        console.log('Error getting user in construction component');
      }
    });
  }

  ngOnInit(): void {
  }

  start(building_id: number): void {
    this.processing = true;
    this.constructionService.startConstruction(this.planet_id, building_id).subscribe(() => {
      this.resourceService.getPlanetaryResourcesByPlanetId(this.planet_id, this.user_id).subscribe(resources => {
        this.globalVars.setResources(resources);
        this.constructionService.getConstruction(this.planet_id).subscribe(construction => {
          this.processing = false;
          if(construction.id != null) {
            this.process = construction;
          }
        })
      });
    });
  }

  cancel(): void {
    this.constructionService.cancelConstruction(this.planet_id).subscribe(() => {
      this.resourceService.getPlanetaryResourcesByPlanetId(this.planet_id, this.user_id).subscribe(resources => {
        this.globalVars.setResources(resources);
          this.processing = false;
          this.process = undefined;
      });
    });
  }
}
