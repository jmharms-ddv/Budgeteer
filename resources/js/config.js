/*
    Defines the API route we are using.
*/
var api_url = '';

switch( process.env.NODE_ENV ){
  case 'development':
    api_url = 'http://budgeteer.homestead/api';
  break;
  case 'production':
    api_url = 'http://budgeteer.ironmthome.com/api';
  break;
}

export const BUDGETEER_CONFIG = {
  API_URL: api_url,
}
