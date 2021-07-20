import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import {PlanetService} from "./planet.service";
import {ProfileService} from "./profile.service";
import {ResourcesService} from "./resources.service";
import {TokenService} from "./token.service";



@NgModule({
  declarations: [],
  imports: [
    CommonModule
  ],
  providers: [
    TokenService,

  ]
})
export class GlobalsModule {}
export * from './planet.service';
export * from './profile.service';
export * from './resources.service';
export * from './token.service';
