import localforage from 'localforage';

export default {
  driver: localforage.LOCALSTORAGE, // Force WebSQL; same as using setDriver()
  name: 'sureclaims',
  version: 1.0,
  size: 4980736, // Size of database, in bytes. WebSQL-only for now.
  storeName: 'primary', // Should be alphanumeric, with underscores.
  description: '',
};
