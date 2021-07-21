import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { AuthService, AuthStateService, ProfileService, TokenService, PlanetBaseData, PlanetService} from '../shared/services/services.module';
import { FormBuilder, FormGroup } from "@angular/forms";
import {LocalStorageService} from "../shared/services/globals/local-storage.service";

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
    private localStorage: LocalStorageService,
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
        this.localStorage.setItem('user', JSON.stringify(result.user));
      },
      error => {
        this.errors = error.error;
      },() => {
        this.authState.setAuthState(true);
        this.loginForm.reset();

        let user = this.localStorage.getItem('user') || '';
        let user_id = JSON.parse(user).id;

        this.profileService.getProfile(user_id).subscribe(data => {
          this.planetService.setActivePlanet(data.start_planet);
          this.planetService.getAllUserPlanetsInit().then(resolve => {
            resolve.subscribe(data => {
              if(data) {
                data.forEach((value: any) => {
                  this.localStorage.setItem('p-' + value.id, JSON.stringify(value));
                });
                this.localStorage.setItem('allPlanets', JSON.stringify(data));
                this.router.navigate(['overview']);
              }
            });
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
