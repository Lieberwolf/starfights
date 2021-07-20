import { Component, OnInit } from '@angular/core';
import {ConstructionService} from "../../shared/services/views/views.module";
import {ConstructionEntryDataInterface} from "../../shared/interfaces/construction-entry-data-interface";
import {Router} from "@angular/router";
import {ConstructionProcessDataInterface} from "../../shared/interfaces/construction-process-data-interface";

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

  constructor(
    private constructionService: ConstructionService,
    public router: Router,
  ) {
    this.planet_id = JSON.parse(localStorage.getItem('planet_id') || '');
    this.user_id = JSON.parse(localStorage.getItem('user') || '').id;
    this.processing = false;

    this.constructionService.getConstruction(this.planet_id).subscribe(data => {
      if(data.id != null) {
        this.process = data;
        this.processing = false;
      }
      this.constructionService.getAllAvailableBuildings(this.planet_id, this.user_id).subscribe(data => {
        this.constructionEntries = data;
      });
    });
  }

  ngOnInit(): void {
  }

  start(building_id: Number): void {
    this.processing = true;
    this.constructionService.startConstruction(this.planet_id, building_id).subscribe(data => {
      this.constructionService.getConstruction(this.planet_id).subscribe(data => {
        this.processing = false;
        if(data.id != null) {
          this.process = data;
        }
      })
    });
  }

  cancel(): void {
    this.constructionService.cancelConstruction(this.planet_id).subscribe(data => {
      this.processing = false;
      this.process = undefined;
    });
  }

}
