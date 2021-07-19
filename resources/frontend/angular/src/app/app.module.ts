import { NgModule, LOCALE_ID } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import { registerLocaleData } from "@angular/common";
import localeDe from '@angular/common/locales/de';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { MenuComponent } from './menu/menu.component';
import { HomeComponent } from './home/home.component';
import { LoginComponent } from './login/login.component';
import { RegisterComponent } from './register/register.component';
import {HTTP_INTERCEPTORS, HttpClientModule} from "@angular/common/http";
import { ReactiveFormsModule, FormsModule } from '@angular/forms';
import {AuthInterceptor} from "./shared/auth.interceptor";
import { OverviewComponent } from './ingame/overview/overview.component';
import {ProfileService} from "./shared/profile.service";
import { GamemenuComponent } from './ingame/gamemenu/gamemenu.component';
import { ResourcesComponent } from './ingame/resources/resources.component';
import { NotificationComponent } from './ingame/notification/notification.component';
import { AttacknotificationComponent } from './ingame/attacknotification/attacknotification.component';
import { ConstructionComponent } from './ingame/construction/construction.component';
import { ShipyardComponent } from './ingame/shipyard/shipyard.component';
import { DefenseComponent } from './ingame/defense/defense.component';
import { ResearchComponent } from './ingame/research/research.component';
import { MissionComponent } from './ingame/mission/mission.component';
import { FleetlistComponent } from './ingame/fleetlist/fleetlist.component';
import { ResourceviewComponent } from './ingame/resourceview/resourceview.component';
import { MessagesComponent } from './ingame/messages/messages.component';
import { UniverseComponent } from './ingame/universe/universe.component';
import { SearchComponent } from './ingame/search/search.component';
import { TechtreeComponent } from './ingame/techtree/techtree.component';
import { DatabaseComponent } from './ingame/database/database.component';
import { SimulationComponent } from './ingame/simulation/simulation.component';
import { HighscoreComponent } from './ingame/highscore/highscore.component';
import { SettingsComponent } from './ingame/settings/settings.component';
import { AllianceComponent } from './ingame/alliance/alliance.component';
import {OverviewData} from "./shared/overview.service";
import {NgxSpinnerModule} from "ngx-spinner";

@NgModule({
  declarations: [
    AppComponent,
    MenuComponent,
    HomeComponent,
    LoginComponent,
    RegisterComponent,
    OverviewComponent,
    GamemenuComponent,
    ResourcesComponent,
    NotificationComponent,
    AttacknotificationComponent,
    ConstructionComponent,
    ShipyardComponent,
    DefenseComponent,
    ResearchComponent,
    MissionComponent,
    FleetlistComponent,
    ResourceviewComponent,
    MessagesComponent,
    UniverseComponent,
    SearchComponent,
    TechtreeComponent,
    DatabaseComponent,
    SimulationComponent,
    HighscoreComponent,
    SettingsComponent,
    AllianceComponent,
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    HttpClientModule,
    ReactiveFormsModule,
    FormsModule,
  ],
  providers: [
    {
      provide: LOCALE_ID, useValue: 'de'
    },
    {
      provide: HTTP_INTERCEPTORS,
      useClass: AuthInterceptor,
      multi: true,
    },
    ProfileService,
    OverviewData
  ],
  bootstrap: [AppComponent]
})
export class AppModule {
  constructor() {
    registerLocaleData(localeDe);
  }
}
