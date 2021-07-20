import { Component, OnInit } from '@angular/core';
import {ConstructionService} from "../../shared/services/views/views.module";
import {ConstructionEntryDataInterface} from "../../shared/interfaces/construction-entry-data-interface";
import {Router} from "@angular/router";
import {ConstructionProcessDataInterface} from "../../shared/interfaces/construction-process-data-interface";
import {BehaviorSubject} from "rxjs";
import {ResourceEntryDataInterface} from "../../shared/interfaces/resource-entry-data-interface";
import {PlanetService, ResourcesService} from "../../shared/services/globals/globals.module";
import {LocalStorageService} from "../../shared/services/globals/local-storage.service";

@Component({
  selector: 'sf-construction',
  templateUrl: './construction.component.html',
  styleUrls: ['./construction.component.scss']
})
export class ConstructionComponent implements OnInit {
  planet_id: number;
  user_id: number;
  process?: ConstructionProcessDataInterface;
  processing: boolean;
  constructionEntries?: Array<ConstructionEntryDataInterface>;
  resourcesBS: BehaviorSubject<ResourceEntryDataInterface>;
  resources: ResourceEntryDataInterface;

  constructor(
    private constructionService: ConstructionService,
    private resourceService: ResourcesService,
    private localStorage: LocalStorageService,
    private planetService: PlanetService,
    public router: Router,
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
    this.planet_id = this.localStorage.getItem('planet_id');
    this.user_id = JSON.parse(this.localStorage.getItem('user') || '').id;
    this.processing = false;
    this.resourcesBS = this.resourceService.getResources();
    this.resourcesBS.subscribe((data) => {
      this.resources = data;
    });

    this.planetService.getActivePlanet().then(resolve => {
      resolve.subscribe(data => {
        this.planet_id = data;
        this.constructionService.getConstruction(this.planet_id).subscribe(data => {
          if(data.id != null) {
            this.process = data;
            this.processing = false;
          }
          this.constructionService.getAllAvailableBuildings(this.planet_id, this.user_id).subscribe(data => {
            this.constructionEntries = data;
          });
        });
      });
    });
  }

  ngOnInit(): void {
  }

  start(building_id: Number): void {
    this.processing = true;
    this.constructionService.startConstruction(this.planet_id, building_id).subscribe(() => {
      this.resourceService.getPlanetaryResourcesByPlanetId(this.planet_id, this.user_id).subscribe(data => {
        this.resourceService.setResources(data);
        this.constructionService.getConstruction(this.planet_id).subscribe(data => {
          this.processing = false;
          if(data.id != null) {
            this.process = data;
          }
        })
      });
    });
  }

  cancel(): void {
    this.constructionService.cancelConstruction(this.planet_id).subscribe(() => {
      this.resourceService.getPlanetaryResourcesByPlanetId(this.planet_id, this.user_id).subscribe(data => {
        this.resourceService.setResources(data);
          this.processing = false;
          this.process = undefined;
      });
    });
  }
}
