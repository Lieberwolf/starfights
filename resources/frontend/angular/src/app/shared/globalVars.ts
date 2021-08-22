import {Injectable} from "@angular/core";
import {BehaviorSubject, Observable} from "rxjs";
import {User} from "./services/helpers/auth.service";
import {PlanetBaseData, PlanetService} from "./services/globals/planet.service";
import {ResourceEntryDataInterface} from "./interfaces/resource-entry-data-interface";
import {LocalStorageService} from "./services/globals/local-storage.service";
import {AuthStateService} from "./services/helpers/auth-state.service";

@Injectable()
export class GlobalVars {
  // user
  user: BehaviorSubject<User>;
  // profile
  profile: BehaviorSubject<object>;
  // planet_id -> this is the current active one
  planet_id: BehaviorSubject<number>;
  // planets
  planets: BehaviorSubject<Array<PlanetBaseData>>;
  // resources -> only for current active planet
  resource: ResourceEntryDataInterface;
  resources: BehaviorSubject<ResourceEntryDataInterface>;
  // processes
  processes: Array<any>;
  globalProcesses: BehaviorSubject<Array<any>>;
  constructor(
    private planetService:PlanetService,
    private localStorage: LocalStorageService,
    private authState: AuthStateService,
  ) {
    this.user = new BehaviorSubject<object>({});
    this.profile = new BehaviorSubject<object>({});
    this.planet_id = new BehaviorSubject<number>(0);
    this.planets = new BehaviorSubject<Array<PlanetBaseData>>([]);
    this.resource = {
      data: {
        fe: 0,
        lut: 0,
        cry: 0,
        h2o: 0,
        h2: 0,
        rate_fe: 0,
        rate_lut: 0,
        rate_cry: 0,
        rate_h2o: 0,
        rate_h2: 0,
      },
      storages: {
        fe: 0,
        lut: 0,
        cry: 0,
        h2o: 0,
        h2: 0,
      }
    };
    this.resources = new BehaviorSubject<ResourceEntryDataInterface>(this.resource);
    this.processes = [];
    this.globalProcesses = new BehaviorSubject<Array<any>>(this.processes);

    authState.userAuthState.subscribe(state => {
      // user os logged in, fill in the values from local storage
      if(state) {
        this.setUser(JSON.parse(this.localStorage.getItem('user')));
        this.setProfile(JSON.parse(this.localStorage.getItem('profile')));
        this.setPlanets(JSON.parse(this.localStorage.getItem('planets')));
        this.setPlanetId(JSON.parse(this.localStorage.getItem('planet_id')));
      }
    });
  }

  // GETTERS AND SETTERS
  getUser(): BehaviorSubject<User> {
    return this.user;
  }

  setUser(value: User): void {
    this.localStorage.setItem('user', JSON.stringify(value));
    this.user.next(value);
  }

  getRole(): string {
    return this.localStorage.getItem('ROLE')
  }

  setRole(val: string): void {
    this.localStorage.setItem('ROLE', val)
  }

  getProfile(): BehaviorSubject<object> {
    return this.profile;
  }

  setProfile(value: object): void {
    this.localStorage.setItem('profile', JSON.stringify(value));
    this.profile.next(value);
  }

  getPlanets(): BehaviorSubject<Array<PlanetBaseData>> {
    return this.planets;
  }

  setPlanets(values: Array<PlanetBaseData>): void {
    this.localStorage.setItem('planets', JSON.stringify(values));
    this.planets.next(values);
  }

  getPlanetId(): BehaviorSubject<number> {
    return this.planet_id;
  }

  setPlanetId(value: number): void {
    this.localStorage.setItem('planet_id', value);
    this.planet_id.next(value);
  }

  getResources(): BehaviorSubject<ResourceEntryDataInterface> {
    return this.resources;
  }

  setResources(values: ResourceEntryDataInterface) {
    this.localStorage.setItem('resources', JSON.stringify(values))
    this.resources.next(values);
  }

  getGlobalProcesses(): BehaviorSubject<Array<any>> {
    return this.globalProcesses;
  }

  setGlobalProcesses(values: Array<any>): void {
    this.localStorage.setItem('processes', JSON.stringify(values));
    this.globalProcesses.next(values);
  }

  getPlanetCoordinates(planet_id:number):Observable<PlanetBaseData> {
    return new Observable(observer => {
        const userPlanets = JSON.parse(this.localStorage.getItem('planets'));
        let planet: PlanetBaseData = {};
        userPlanets.forEach((item:any)=>{
          if(item.id === planet_id){
            observer.next(item)
          }
        });
        if(Object.keys(planet).length > 0){
          this.planetService.getPlanetById(planet_id).subscribe((e)=> {
            observer.next(e)
          })
        }
      }
    );

  }
  // GLOBAL HELPERS
  enableCounters(): void {
    this.globalProcesses.subscribe(data => {
      setInterval(() => {
        data.forEach(function(process) {
          process.timeleft--;
        });
        this.processes = data;
      }, 1000);
    });
  }
}
