export const BASE_GESTION_API_URL = window.location.hostname.startsWith('localhost')
  ? 'http://localhost:8202/api/gestion'
  : 'http://gestion.r401.paillaugue.fr/api/gestion';


export const BASE_AUTH_API_URL = window.location.hostname.startsWith('localhost')
  ? 'http://localhost:8201/api/auth'
  : 'http://auth.r401.paillaugue.fr/api/auth';
