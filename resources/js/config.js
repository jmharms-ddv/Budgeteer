/*
    Defines the API route we are using.
*/
var url = process.env.MIX_APP_URL;
var api_url = url + '/api';

switch( process.env.NODE_ENV ){
  case 'development':
    api_url = url + '/api';
  break;
  case 'production':
    api_url = url + '/api';
  break;
}

export const BUDGETEER_CONFIG = {
  URL: url,
  API_URL: api_url,
}
