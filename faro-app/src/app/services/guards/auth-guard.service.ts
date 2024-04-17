import { Inject, Injectable } from '@angular/core';
import { Router } from '@angular/router';
import { AuthService } from './auth.service';

@Injectable()
export class AuthGuardService  {
  
  constructor(
    private auth: AuthService, 
    private router: Router) {}

  async canActivate(): Promise<boolean> {
    if (!await this.auth.isAuthenticated()) {
      this.router.navigate(['login']);
      return false;
    }
    return true;
  }

}
