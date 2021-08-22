import {GlobalVars} from "../shared/globalVars";
import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import {
  AuthService,
  AuthStateService,
  ProfileService,
  TokenService,
  PlanetBaseData,
  PlanetService,
  ResourcesService
} from '../shared/services/services.module';
import { FormBuilder, FormGroup } from "@angular/forms";

@Component({
  selector: 'sf-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss']
})

export class LoginComponent implements OnInit {
  loginForm: FormGroup;
  errors = null;

  constructor(
    public globalVars: GlobalVars,
    public router: Router,
    public fb: FormBuilder,
    public authService: AuthService,
    public profileService: ProfileService,
    private token: TokenService,
    private authState: AuthStateService,
    private planetService: PlanetService,
    private resourceService: ResourcesService,
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
        this.globalVars.setUser(result.user);
        this.globalVars.setRole("PLAYER");
      },
      error => {
        this.errors = error.error;
      },() => {
        this.authState.setAuthState(true);
        this.loginForm.reset();

        this.globalVars.getUser().subscribe(user => {
          this.profileService.getProfile(user.id).subscribe(profile => {
            if(profile) {
              this.globalVars.setProfile(profile);
              this.globalVars.setPlanetId(profile.start_planet);
              this.planetService.getAllUserPlanetsInit(user.id).subscribe(planets => {
                if(planets) {
                  this.globalVars.setPlanets(planets);
                  this.resourceService.getPlanetaryResourcesByPlanetId(profile.start_planet, user.id).subscribe(resources => {
                    if(resources) {
                      this.globalVars.setResources(resources);
                      this.router.navigate(['/overview']);
                    } else {
                      console.log('Error while getting resources in LoginComponent');
                    }
                  });
                } else {
                  console.log('Error while getting planets in LoginComponent');
                }
              });
            } else {
              console.log('Error while getting profile in LoginComponent');
            }
          })
        });
      }
    );
  }

  // Handle response
  responseHandler(token: string){
    this.token.handleData(token);
  }

}
