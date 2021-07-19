import {Component, OnInit} from '@angular/core';
import { Router } from '@angular/router';
import { TokenService } from './shared/token.service';
import { AuthStateService } from './shared/auth-state.service';

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
    public router: Router,
    public token: TokenService,
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

  // Signout
  signOut() {
    this.auth.setAuthState(false);
    this.token.removeToken();
    localStorage.clear();
    this.router.navigate(['login']);
  }
}
