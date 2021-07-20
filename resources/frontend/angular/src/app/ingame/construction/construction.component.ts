import { Component, OnInit } from '@angular/core';
import {ConstructionService} from "../../shared/services/views/views.module";
import {ConstructionEntryDataInterface} from "../../shared/interfaces/construction-entry-data-interface";
import {ActivatedRoute, Router} from "@angular/router";
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
  building_id: any;
  constructionEntries?: Array<ConstructionEntryDataInterface>;

  constructor(
    private constructionService: ConstructionService,
    public activeRoute: ActivatedRoute,
    public router: Router,
  ) {
    this.planet_id = JSON.parse(localStorage.getItem('planet_id') || '');
    this.user_id = JSON.parse(localStorage.getItem('user') || '').id;
    this.processing = false;
    this.building_id = this.activeRoute.snapshot.paramMap.get('building_id');

    if(!this.building_id) {
      this.constructionService.getConstruction(this.planet_id).subscribe(data => {
        if(data.id != null) {
          this.process = data;
          this.processing = true;
        }
        this.constructionService.getAllAvailableBuildings(this.planet_id, this.user_id).subscribe(data => {
          this.constructionEntries = data;
        });
      });

    } else {
      if(this.building_id === 'edit') {
        this.constructionService.cancelConstruction(this.planet_id).subscribe(data => {
          this.processing = false;
          this.router.navigate(['/construction']);
        });
      } else {
        this.constructionService.startConstruction(this.planet_id, this.building_id).subscribe(data => {
          this.processing = true;
          this.router.navigate(['/construction']);
        });
      }

    }
  }

  ngOnInit(): void {
  }

}
