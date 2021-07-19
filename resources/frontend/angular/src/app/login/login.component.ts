import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { AuthService } from './../shared/auth.service';
import { FormBuilder, FormGroup } from "@angular/forms";
import { TokenService } from '../shared/token.service';
import { AuthStateService } from '../shared/auth-state.service';
import {ProfileService} from "../shared/profile.service";
import {PlanetBaseData, PlanetService} from "../shared/planet.service";

@Component({
  selector: 'sf-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss']
})

export class LoginComponent implements OnInit {
  loginForm: FormGroup;
  errors = null;

  constructor(
    public router: Router,
    public fb: FormBuilder,
    public authService: AuthService,
    public profileService: ProfileService,
    private token: TokenService,
    private authState: AuthStateService,
    private planetService: PlanetService,
  ) {
    this.loginForm = this.fb.group({
      username: [],
      password: []
    })
  }

  ngOnInit() { }

  onSubmit() {
    this.authService.login(this.loginForm.value).subscribe(
      result => {
        this.responseHandler(result.access_token);
        localStorage.setItem('user', JSON.stringify(result.user));
      },
      error => {
        this.errors = error.error;
      },() => {
        this.authState.setAuthState(true);
        this.loginForm.reset();

        let user = localStorage.getItem('user') || '';
        let user_id = JSON.parse(user).id;

        this.profileService.getProfile(user_id).subscribe(data => {
          localStorage.setItem('planet_id', data.start_planet);
          this.planetService.getAllUserPlanets(user_id).subscribe(data => {
            localStorage.setItem('allPlanets', JSON.stringify(data));
            this.router.navigate(['overview']);
          });
        });
      }
    );
  }

  // Handle response
  responseHandler(token: string){
    this.token.handleData(token);
  }

}
