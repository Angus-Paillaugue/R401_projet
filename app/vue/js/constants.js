const baseLocalUrl = new URL(window.location.href);
baseLocalUrl.port = 8202;
const localGestionURL = new URL(baseLocalUrl);
localGestionURL.pathname = '/api/gestion';
const localAuthURL = new URL(baseLocalUrl);
localAuthURL.port = 8201;
localAuthURL.pathname = '/api/auth';

export const BASE_GESTION_API_URL = window.location.hostname.includes(
  'paillaugue.fr'
)
  ? 'https://gestion-r401.paillaugue.fr'
  : localGestionURL.href;

export const BASE_AUTH_API_URL = window.location.hostname.includes(
  'paillaugue.fr'
)
  ? 'https://auth-r401.paillaugue.fr'
  : localAuthURL.href;
