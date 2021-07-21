import { Pipe, PipeTransform } from '@angular/core';

@Pipe({
  name: 'timecounter'
})
export class TimecounterPipe implements PipeTransform {

  transform(timestamp: number, ...args: unknown[]): string {

    let days: string | number    = this.component(timestamp, 24 * 60 * 60),
      hours: string | number   = this.component(timestamp,      60 * 60) % 24,
      minutes: string | number = this.component(timestamp,           60) % 60,
      seconds: string | number = this.component(timestamp,            1) % 60,
      suffix: string  = ':';

    if(days > 0)
    {
      days =  days < 10 ? '0' + days +" d, " : days +" d, ";
    } else {
      days = '';
    }

    hours = hours < 10 ? '0' + hours + suffix : hours + suffix;
    minutes = minutes < 10 ? '0' + minutes + suffix : minutes + suffix;
    seconds = seconds < 10 ? '0' + seconds : seconds;

    if(timestamp <= 0)
    {
      return "-";
    } else {
      return days + hours + minutes + seconds;
    }
  }

  // helpers
  component(x: number, v: number): number {
    return Math.floor(x / v);
  }

}
