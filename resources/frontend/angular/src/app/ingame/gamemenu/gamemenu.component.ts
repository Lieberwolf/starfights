import { Component, OnInit } from '@angular/core';
import {AuthStateService, PlanetService, ProfileService, TokenService} from "../../shared/services/services.module";
import {ActivatedRoute, Router} from "@angular/router";
import {LocalStorageService} from "../../shared/services/globals/local-storage.service";
import {FormBuilder, FormGroup} from "@angular/forms";

@Component({
  selector: 'sf-gamemenu',
  templateUrl: './gamemenu.component.html',
  styleUrls: ['./gamemenu.component.scss']
})
export class GamemenuComponent implements OnInit {
  isSignedIn: boolean;
  planet_id: Number;
  planets_all: any;
  selectorForm: FormGroup;

  constructor(
    private auth: AuthStateService,
    private route: ActivatedRoute,
    public router: Router,
    public token: TokenService,
    private localStorage: LocalStorageService,
    private planetService: PlanetService,
    public formBuilder: FormBuilder,
  ) {
    this.isSignedIn = false;
    this.planet_id = this.localStorage.getItem('planet_id');
    this.planets_all = JSON.parse(this.localStorage.getItem('allPlanets'));

    this.planetService.getActivePlanet().then(resolve => {
      resolve.subscribe(data => {
        this.planet_id = data;
        if(this.planets_all == null) {
          this.planetService.getAllUserPlanets().then(resolve => {
            resolve.subscribe(data => {
              if(data) {
                this.localStorage.setItem('allPlanets', JSON.stringify(data));
                this.planets_all = data;
              }
            });
          });
        }
      });
    });

    this.selectorForm = this.formBuilder.group({
      planetSelect: []
    });
    this.selectorForm.valueChanges.subscribe(data => {
      this.changePlanet(data.planetSelect);
    });
  }

  ngOnInit(): void {
    this.auth.userAuthState.subscribe(val => {
      this.isSignedIn = val;
    });
  }

  // move to prev planet
  prev(): void {
    let index = 0;
    for(let i = 0; i < this.planets_all.length; i++) {
      if(this.planets_all[i].id == this.planet_id) {
        index = i;
      }
    }

    // is there a prev planet?
    // else get to the last entry
    if(this.planets_all[(index-1)] != undefined) {
      this.planetService.setActivePlanet(this.planets_all[(index-1)].id);
    } else {
      this.planetService.setActivePlanet(this.planets_all[(this.planets_all.length-1)].id);
    }
  }

  // move to next planet
  next(): void {
    let index = 0;
    for(let i = 0; i < this.planets_all.length; i++) {
      if(this.planets_all[i].id == this.planet_id) {
        index = i;
      }
    }

    // is there a prev planet?
    // else get to the last entry
    if(this.planets_all[(index+1)] != undefined) {
      this.planetService.setActivePlanet(this.planets_all[(index+1)].id);
    } else {
      this.planetService.setActivePlanet(this.planets_all[0].id);
    }
  }

  //change Planet
  changePlanet(id: number): void {
    this.planetService.setActivePlanet(id);
  }

  // Signout
  signOut() {
    this.auth.setAuthState(false);
    this.token.removeToken();
    this.localStorage.clear();
    this.router.navigate(['']);
  }

}
