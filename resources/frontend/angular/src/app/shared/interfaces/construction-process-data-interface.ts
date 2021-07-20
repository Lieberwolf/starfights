export interface ConstructionProcessDataInterface {
  building_name: String;
  infrastructure: {
    level: number
  }
  secondsRemaining: number;
  finished_at: string;
}
