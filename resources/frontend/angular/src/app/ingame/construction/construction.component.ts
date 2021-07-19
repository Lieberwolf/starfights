import { Component, OnInit } from '@angular/core';
import {ConstructionService} from "../../shared/construction.service";
import {ConstructionEntryDataInterface} from "../../shared/interfaces/construction-entry-data-interface";

@Component({
  selector: 'sf-construction',
  templateUrl: './construction.component.html',
  styleUrls: ['./construction.component.scss']
})
export class ConstructionComponent implements OnInit {
  planet_id: number;
  user_id: number;
  constructionEntries: Array<ConstructionEntryDataInterface>;

  constructor(
    private constructionService: ConstructionService,
  ) {
    this.planet_id = JSON.parse(localStorage.getItem('planet_id') || '');
    this.user_id = JSON.parse(localStorage.getItem('user') || '').id;
    this.constructionEntries = [];

    this.constructionService.getAllAvailableBuildings(this.planet_id, this.user_id).subscribe(data => {
      this.constructionEntries = data;
      console.log(data);
    })
  }

  ngOnInit(): void {
  }

}
