import {Component, OnInit} from '@angular/core';
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
