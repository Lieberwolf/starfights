import {Component, Inject, OnInit} from '@angular/core';
import { AuthStateService, TokenService } from './shared/services/services.module';
import {Router} from "@angular/router";

@Component({
  selector: 'sf-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss']
})
export class AppComponent implements OnInit {
  isSignedIn: boolean;
  hasNotifications: boolean;
  isUnderAttack: boolean;
  title = 'Starfights';

  constructor(
    private auth: AuthStateService,
    public token: TokenService,
    public router: Router,
  ) {
    this.isSignedIn = false;
    this.hasNotifications = true;
    this.isUnderAttack = true;
  }

  ngOnInit() {
    this.auth.userAuthState.subscribe(val => {
      this.isSignedIn = val;
    });
  }
}
