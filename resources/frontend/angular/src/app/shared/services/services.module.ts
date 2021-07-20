import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import {GlobalsModule} from "./globals/globals.module";
import {HelpersModule} from "./helpers/helpers.module";
import {ViewsModule} from "./views/views.module";



@NgModule({
  declarations: [],
  imports: [
    CommonModule,
    GlobalsModule,
    HelpersModule,
    ViewsModule
  ],
})
export class ServicesModule { }
export * from './globals/globals.module';
export * from './helpers/helpers.module';
export * from './views/views.module';


