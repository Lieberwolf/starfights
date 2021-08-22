import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import {HomeComponent} from "./home/home.component";
import {LoginComponent} from "./login/login.component";
import {RegisterComponent} from "./register/register.component";
import {OverviewComponent} from "./ingame/overview/overview.component";
import {ConstructionComponent} from "./ingame/construction/construction.component";
import {ShipyardComponent} from "./ingame/shipyard/shipyard.component";
import {DefenseComponent} from "./ingame/defense/defense.component";
import {ResearchComponent} from "./ingame/research/research.component";
import {MissionComponent} from "./ingame/mission/mission.component";
import {FleetlistComponent} from "./ingame/fleetlist/fleetlist.component";
import {ResourceviewComponent} from "./ingame/resourceview/resourceview.component";
import {MessagesComponent} from "./ingame/messages/messages.component";
import {UniverseComponent} from "./ingame/universe/universe.component";
import {SearchComponent} from "./ingame/search/search.component";
import {TechtreeComponent} from "./ingame/techtree/techtree.component";
import {DatabaseComponent} from "./ingame/database/database.component";
import {SimulationComponent} from "./ingame/simulation/simulation.component";
import {HighscoreComponent} from "./ingame/highscore/highscore.component";
import {SettingsComponent} from "./ingame/settings/settings.component";
import {AllianceComponent} from "./ingame/alliance/alliance.component";
import {AuthGuard} from "./auth.guard";

const routes: Routes = [
  {path: '', component: HomeComponent, pathMatch: 'full'},
  {path: 'login', component: LoginComponent},
  {path: 'register', component: RegisterComponent},
  {path: 'overview', component: OverviewComponent, canActivate: [AuthGuard], data: {role: 'PLAYER'}},
  {path: 'construction', component: ConstructionComponent, canActivate: [AuthGuard], data: {role: 'PLAYER'}},
  {path: 'construction/:building_id', component: ConstructionComponent, canActivate: [AuthGuard], data: {role: 'PLAYER'}},
  {path: 'shipyard', component: ShipyardComponent, canActivate: [AuthGuard], data: {role: 'PLAYER'}},
  {path: 'defense', component: DefenseComponent, canActivate: [AuthGuard], data: {role: 'PLAYER'}},
  {path: 'research', component: ResearchComponent, canActivate: [AuthGuard], data: {role: 'PLAYER'}},
  {path: 'mission', component: MissionComponent, canActivate: [AuthGuard], data: {role: 'PLAYER'}},
  {path: 'fleetlist', component: FleetlistComponent, canActivate: [AuthGuard], data: {role: 'PLAYER'}},
  {path: 'resources', component: ResourceviewComponent, canActivate: [AuthGuard], data: {role: 'PLAYER'}},
  {path: 'messages', redirectTo: 'messages/new'},
  {path: 'messages/new', component: MessagesComponent, canActivate: [AuthGuard], data: {role: 'PLAYER'}},
  {path: 'universe', component: UniverseComponent, canActivate: [AuthGuard], data: {role: 'PLAYER'}},
  {path: 'search', component: SearchComponent, canActivate: [AuthGuard], data: {role: 'PLAYER'}},
  {path: 'techtree', component: TechtreeComponent, canActivate: [AuthGuard], data: {role: 'PLAYER'}},
  {path: 'database', component: DatabaseComponent, canActivate: [AuthGuard], data: {role: 'PLAYER'}},
  {path: 'simulation', component: SimulationComponent, canActivate: [AuthGuard], data: {role: 'PLAYER'}},
  {path: 'highscore', component: HighscoreComponent, canActivate: [AuthGuard], data: {role: 'PLAYER'}},
  {path: 'settings', component: SettingsComponent, canActivate: [AuthGuard], data: {role: 'PLAYER'}},
  {path: 'alliance', component: AllianceComponent, canActivate: [AuthGuard], data: {role: 'PLAYER'}},
  ];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
