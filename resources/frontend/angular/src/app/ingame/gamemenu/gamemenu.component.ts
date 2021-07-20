import { Component, OnInit } from '@angular/core';
import {AuthStateService, ProfileService, TokenService} from "../../shared/services/services.module";
import {ActivatedRoute, Router} from "@angular/router";

@Component({
  selector: 'sf-gamemenu',
  templateUrl: './gamemenu.component.html',
  styleUrls: ['./gamemenu.component.scss']
})
export class GamemenuComponent implements OnInit {
  isSignedIn: boolean;
  planet_id: Number;

  constructor(
    private auth: AuthStateService,
    private route: ActivatedRoute,
    public router: Router,
    public token: TokenService,
    public profileService: ProfileService,
  ) {
    let user = localStorage.getItem('user') || '';
    this.isSignedIn = false;
    this.planet_id = 0;
    this.profileService.getStartPlanet(JSON.parse(user).id).then((data) => {
      this.planet_id = data;
      console.log(this.route.snapshot.paramMap.get('planet_id'))
    });
  }

  ngOnInit(): void {
    this.auth.userAuthState.subscribe(val => {
      this.isSignedIn = val;
    })
  }

  // Signout
  signOut() {
    this.auth.setAuthState(false);
    this.token.removeToken();
    localStorage.clear();
    this.router.navigate(['']);
  }

}
