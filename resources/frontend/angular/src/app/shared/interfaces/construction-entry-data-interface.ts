export interface ConstructionEntryDataInterface {
  id: number,
  buildable: Boolean,
  readableBuildtime: String,
  building_name: String,
  description: String,
  infrastructure: {
    level: number,
  }
  fe: number,
  lut: number,
  cry: number,
  h2o: number,
  h2: number,
}
