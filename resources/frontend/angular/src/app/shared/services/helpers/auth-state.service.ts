import { Injectable } from '@angular/core';
import { BehaviorSubject } from 'rxjs/internal/BehaviorSubject';
import { TokenService } from '../globals/token.service';
import { Inject} from "@angular/core";

@Injectable({
  providedIn: 'root'
})

export class AuthStateService {

  private userState = new BehaviorSubject<boolean>(this.token.isLoggedIn());
  userAuthState = this.userState.asObservable();

  constructor(
    @Inject(TokenService)
    public token: TokenService
  ) { }

  setAuthState(value: boolean) {
    this.userState.next(value);
  }

}
