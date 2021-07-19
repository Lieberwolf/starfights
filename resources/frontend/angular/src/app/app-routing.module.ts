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

const routes: Routes = [
  {path: '', component: HomeComponent, pathMatch: 'full'},
  {path: 'login', component: LoginComponent},
  {path: 'register', component: RegisterComponent},
  {path: 'overview', component: OverviewComponent},
  {path: 'construction', component: ConstructionComponent},
  {path: 'shipyard', component: ShipyardComponent},
  {path: 'defense', component: DefenseComponent},
  {path: 'research', component: ResearchComponent},
  {path: 'mission', component: MissionComponent},
  {path: 'fleetlist', component: FleetlistComponent},
  {path: 'resources', component: ResourceviewComponent},
  {path: 'messages', redirectTo: 'messages/new'},
  {path: 'messages/new', component: MessagesComponent},
  {path: 'universe', component: UniverseComponent},
  {path: 'search', component: SearchComponent},
  {path: 'techtree', component: TechtreeComponent},
  {path: 'database', component: DatabaseComponent},
  {path: 'simulation', component: SimulationComponent},
  {path: 'highscore', component: HighscoreComponent},
  {path: 'settings', component: SettingsComponent},
  {path: 'alliance', component: AllianceComponent},
  ];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
