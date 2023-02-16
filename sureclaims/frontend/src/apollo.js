import url from 'url';
import Vue from 'vue';
import { ApolloClient } from 'apollo-client';
import { ApolloLink, from } from 'apollo-link';
import { setContext } from 'apollo-link-context';
import { HttpLink } from 'apollo-link-http';
import { InMemoryCache } from 'apollo-cache-inmemory';
import VueApollo from 'vue-apollo';

import authService from '@/modules/auth/services/AuthService';

// Install the vue plugin
Vue.use(VueApollo);

const cache = new InMemoryCache();

// -------------------------------------------------------------------------
// Create the default apollo client with auth middleware
// -------------------------------------------------------------------------

const endpoint = process.env.NODE_ENV === 'production' ?
  url.resolve(window.location.href, '/') :
  process.env.GRAPHQL_ENDPOINT;

const defaultLink = new HttpLink({
  // You should use an absolute URL here
  uri: `${endpoint}/graphql`,
});

const authMiddleware = setContext(() => authService.accessToken().then(accessToken => ({
  headers: {
    Authorization: `Bearer ${accessToken}` || null,
    accept: 'application/json',
  },
})));

const setHeadersMiddleWare = new ApolloLink((operation, forward) => {
  // set additional headers
  operation.setContext({
    headers: {
      accept: 'application/json',
    },
  });
  return forward(operation);
});

const defaultClient = new ApolloClient({
  link: from([
    setHeadersMiddleWare,
    authMiddleware,
    defaultLink,
  ]),
  connectToDevTools: true,
  addTypeName: true,
  cache,
});


// -------------------------------------------------------------------------
// Create the apollo client for the public link
// -------------------------------------------------------------------------
const publicLink = new HttpLink({
  uri: `${endpoint}/graphql/public`,
});

const publicClient = new ApolloClient({
  link: from([
    setHeadersMiddleWare,
    publicLink,
  ]),
  connectToDevTools: true,
  cache,
});

export default new VueApollo({
  clients: {
    default: defaultClient,
    public: publicClient,
  },
  defaultClient,
});
