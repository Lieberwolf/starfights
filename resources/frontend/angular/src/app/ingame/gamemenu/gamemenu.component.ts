import { Component, OnInit } from '@angular/core';
import {AuthStateService, ProfileService, TokenService} from "../../shared/services/services.module";
import {ActivatedRoute, Router} from "@angular/router";
import {LocalStorageService} from "../../shared/services/globals/local-storage.service";

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
    private localStorage: LocalStorageService,
  ) {
    this.isSignedIn = false;
    this.planet_id = this.localStorage.getItem('planet_id');
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
    this.localStorage.clear();
    this.router.navigate(['']);
  }

}
