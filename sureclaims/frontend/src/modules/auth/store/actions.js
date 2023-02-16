/* eslint-disable */

import Apollo from '@/apollo';

import AUTHENTICATE_MUTATION from '../graphql/queries/Authenticate.gql';
import {
  LOGIN,
  LOGIN_FAILED,
  LOGOUT
} from './mutations';

import { AUTH_ACCESS_TOKEN } from '@/modules/auth';

const authenticate = async ({ commit }, credentials) => {
  const { username, password, scope } = credentials;
  try {

    await Apollo.clients.public.mutate({
      mutation: AUTHENTICATE_MUTATION,
      variables: {
        grantType: 'password',
        clientId: process.env.OAUTH_CLIENT_ID,
        clientSecret: process.env.OAUTH_CLIENT_SECRET,
        username,
        password,
        scope: scope || '*'
      },

      update(store, { data }) {
        const {
          authenticate: {
            tokenType,
            accessToken,
            refreshToken,
            expiresIn,
            errorCode,
            errorMessage,
            errorHint
          }
        } = data;

        const payload = {
          pending: false,
          error: {
            code: errorCode,
            message: errorMessage,
            hint: errorHint
          }
        };

        localStorage.setItem(AUTH_ACCESS_TOKEN, JSON.stringify({
          tokenType,
          accessToken,
          refreshToken,
          expiresIn: expiresIn + Date.now()
        }));

        commit(LOGIN_SUCCESS, payload);

        return true;
      }
    });

  } catch (error) {
    let errorObject = {
      errorCode: 'unknown_error',
      errorMessage: 'Oops! Something went wrong!',
      errorHint: ''
    };

    if (error.response) {
      const { errorCode, errorMessage, errorHint } = error.response.data;
      errorObject = {
        errorCode,
        errorMessage,
        errorHint
      };
    } else if (error.request) {
      errorObject = {
        errorCode: 'no_response',
        errorMessage: 'No response from the authentication server',
        errorHint: ''
      };
    }

    commit(LOGIN_FAILED, errorObject);
    throw new Error(errorObject.errorMessage);
  }
};

const login = ({ commit }, user) => {
  const {
    name,
    nickname,
    picture
  } = user;
  commit(LOGIN, {
    name,
    nickname,
    picture
  });
};

const loginFailed = ({ commit }, error) => {
  commit(LOGIN_FAILED, error);
};

const logout = ({ commit }) => {
  commit(LOGOUT);
};

export default {
  authenticate,
  login,
  loginFailed,
  logout
};
