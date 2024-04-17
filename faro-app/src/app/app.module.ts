import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { HomeComponent } from './components/home/home.component';
import { FirstTimeComponent } from './components/first-time/first-time.component';
import { NavBarComponent } from './components/nav-bar/nav-bar.component';
import { HistoryComponent } from './components/history/history.component';
import { LoginComponent } from './components/login/login.component';
import { RegistroComponent } from './components/registro/registro.component';
import { DashboardComponent } from './components/dashboard/dashboard.component';
import { Utils } from './api/Utils';
import { LoginDao } from './api/dao/LoginDao';
import { AuthGuardService } from './services/guards/auth-guard.service';
import { AuthService } from './services/guards/auth.service';
import { RoleGuardService } from './services/guards/role-guard.service';
import { Router } from '@angular/router';
import { HttpClientModule } from '@angular/common/http';

@NgModule({
  declarations: [
    AppComponent,
    HomeComponent,
    FirstTimeComponent,
    NavBarComponent,
    HistoryComponent,
    LoginComponent,
    RegistroComponent,
    DashboardComponent
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    FormsModule,
    ReactiveFormsModule,
    HttpClientModule,
  ],
  providers: [
    {
      provide: 'router', useFactory: (rotuer: Router) => {
        return new Router();
      }
    },
    Utils,
    LoginDao,
    AuthGuardService,
    AuthService,
    RoleGuardService,
  ],
  bootstrap: [AppComponent]
})
export class AppModule { }
