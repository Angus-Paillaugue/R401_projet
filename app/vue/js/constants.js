export const BASE_GESTION_API_URL = window.location.hostname.startsWith('localhost')
  ? 'http://localhost:8202/api/gestion'
  : 'https://gestion-r401.paillaugue.fr';


export const BASE_AUTH_API_URL = window.location.hostname.startsWith('localhost')
  ? 'http://localhost:8201/api/auth'
  : 'https://auth-r401.paillaugue.fr';
