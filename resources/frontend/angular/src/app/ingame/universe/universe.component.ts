import {Component, OnInit, Output, EventEmitter} from '@angular/core';
import {GlobalVars} from "../../shared/globalVars";
import {BehaviorSubject, Observable} from "rxjs";
import {FormControl, FormGroup, Validators} from "@angular/forms";
import {PlanetBaseData, PlanetService} from "../../shared/services/globals/planet.service";



@Component({
  selector: 'sf-universe',
  templateUrl: './universe.component.html',
  styleUrls: ['./universe.component.scss']
})
export class UniverseComponent implements OnInit {
  public profile:any;
  public planets:any;
  public data:any;
  public system: number = 1;
  public galaxy: number = 1;
  universeForm: FormGroup;

  public newGalaxy: any;

  @Output() submitForm = new EventEmitter<any>();

  constructor(public gv: GlobalVars, public ps: PlanetService) {

    this.gv.getProfile().subscribe((e)=>{
      this.profile = e
    });
    this.gv.getPlanetCoordinates(this.profile.start_planet).subscribe((e)=>{
      if(e.galaxy){
        this.galaxy = e.galaxy;
      }
      if(e.system){
        this.system = e.system;
      }
      this.getPlanets(this.galaxy, this.system);
    });

    this.universeForm = new FormGroup({
      galaxy: new FormControl(this.galaxy, [
        Validators.min(1),
        Validators.max(30)
      ]),
      system: new FormControl(this.system, [
        Validators.min(1),
        Validators.max(300)
      ])
    });
  }

  ngOnInit(): void {

  }

  onSubmit(): void {
    if(this.universeForm.invalid){
      this.universeForm.markAllAsTouched();
      return
    }

    this.newGalaxy = this.universeForm.value;
    this.galaxy = this.newGalaxy.galaxy;
    this.system = this.newGalaxy.system;
    this.getPlanets(this.galaxy, this.system);
  }

  getPlanets(galaxy:number, system:number){
    this.ps.getUniverse(galaxy, system).subscribe((e) =>{
      this.planets = e
    })
  }

  prevGalaxy(){
    this.galaxy--;
    if (this.galaxy < 1){
      this.galaxy = 1
    }

    this.getPlanets(this.galaxy, this.system);
  }
  nextGalaxy(){
    this.galaxy++;
    if (this.galaxy > 30){
      this.galaxy = 30
    }

    this.getPlanets(this.galaxy, this.system);
  }
  prevSystem(){
    this.system--;
    if (this.system < 1){
      this.system = 1
    }

    this.getPlanets(this.galaxy, this.system);
  }
  nextSystem(){
    this.system++;
    if (this.system > 300){
      this.system = 300
    }

    this.getPlanets(this.galaxy, this.system);
  }

}
