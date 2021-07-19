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
  ],
  bootstrap: [AppComponent]
})
export class AppModule {
  constructor() {
    registerLocaleData(localeDe);
  }
}
