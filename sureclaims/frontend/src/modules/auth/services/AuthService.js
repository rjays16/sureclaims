import createAuth0Client from '@auth0/auth0-spa-js';
import jwt from 'jsonwebtoken';
import localforage from 'localforage';
import EventEmitter from 'eventemitter3';
import url from 'url';

import {
  AUTH_ACCESS_TOKEN,
  AUTH_EXPIRES_AT,
  AUTH_USER_INFO
} from '../index';

class AuthService {
  authNotifier = new EventEmitter();
  lastUpdated = null;

  // eslint-disable-next-line class-methods-use-this
  async init() {
    const endpoint = process.env.NODE_ENV === 'production' ?
      url.resolve(window.location.href, '/') :
      process.env.GRAPHQL_ENDPOINT;

    // eslint-disable-next-line no-console
    // const sureClaimsUrl = (url.parse(endpoint).hostname);
    const sureClaimsUrl = 'sureclaims.localhost:8080';

    const sureClaimsConnection = (url.parse(endpoint)
      .hostname
      .replace(/\./g, '-'));

    this.auth0 = await createAuth0Client({
      domain: process.env.AUTH0_DOMAIN,
      client_id: process.env.AUTH0_CLIENT_ID,
      redirect_uri: `http://${sureClaimsUrl}/#/access_token/`,
      scope: process.env.AUTH0_SCOPE,
      audience: process.env.AUTH0_AUDIENCE,
      connection: sureClaimsConnection
    });
  }

  loginAuth() {
    this.auth0.loginWithRedirect();
  }

  async checkAuth(loginCallback) {
    if (this.auth0) {
      try {
        const user = await this.auth0.getUser();
        const token = await this.auth0.getTokenSilently({
          audience: process.env.AUTH0_AUDIENCE,
          redirect_uri: 'http://localhost/auth0/access_token',
          scope: process.env.AUTH0_SCOPE
        });
        const claims = await this.auth0.getIdTokenClaims();
        // eslint-disable-next-line no-console
        const result = {
          expiresIn: claims.exp,
          accessToken: token,
          // eslint-disable-next-line no-underscore-dangle
          idToken: token,
          idTokenPayload: claims
        };
        const object3 = { ...user, ...claims, ...result };
        await this.setSession(object3);
        loginCallback(object3);
      } catch (e) {
        loginCallback(e);
      }
    }
  }

  // eslint-disable-next-line class-methods-use-this
  async setSession(token) {
    const expiresAt = (token.expiresIn * 1000);
    await localforage.setItem(AUTH_ACCESS_TOKEN, token.idToken);
    const benders = jwt.decode(token.idToken);
    await localforage.setItem(AUTH_EXPIRES_AT, expiresAt);

    const userInfo = { ...benders, ...token };
    this.authNotifier.emit('auth.change', {
      authenticated: true,
      userInfo
    });

    this.scheduleRenewToken();

    this.lastUpdated = new Date().getTime();

    await localforage.setItem(AUTH_USER_INFO, userInfo);
    return userInfo;
  }

  // eslint-disable-next-line class-methods-use-this
  async isAuthenticated() {
    // Check whether the current time is past the access token's expiry
    // eslint-disable-next-line no-unreachable
    const expiresAt = await localforage.getItem(AUTH_EXPIRES_AT);
    return Date.now() < expiresAt;
  }

  // eslint-disable-next-line class-methods-use-this
  async renewToken() {
    const token = await this.auth0.getTokenSilently({
      audience: process.env.AUTH0_AUDIENCE,
      redirect_uri: 'http://localhost/auth0/access_token',
      scope: process.env.AUTH0_SCOPE
    });
    this.authNotifier.emit('auth.change', {
      authenticated: true,
      token
    });
  }

  // eslint-disable-next-line class-methods-use-this
  async logout() {
    await this.auth0.logout();
    // Clear access token and ID token from local storage
    await this.clearSession();
    return true;
  }


  async clearSession() {
    await localforage.removeItem(AUTH_ACCESS_TOKEN);
    await localforage.removeItem(AUTH_EXPIRES_AT);
    await localforage.removeItem(AUTH_USER_INFO);
    this.authNotifier.emit('auth.change', {
      authenticated: false,
      userInfo: null
    });
  }

  // eslint-disable-next-line class-methods-use-this
  async accessToken() {
    return localforage.getItem(AUTH_ACCESS_TOKEN);
  }

  async userInfo() {
    const user = await localforage.getItem(AUTH_USER_INFO);
    return new Promise((resolve, reject) => {
      if (user) {
        this.authNotifier.emit('auth.change', {
          authenticated: true,
          user
        });
        resolve(user);
      } else {
        this.authNotifier.emit('auth.change', {
          authenticated: false,
          userInfo: null
        });
        reject(reject);
      }
    });
  }

  async scheduleRenewToken() {
    clearTimeout(this.tokenRenewalTimeout);
    const expiresAt = await localforage.getItem(AUTH_EXPIRES_AT);
    if (!expiresAt) {
      return true;
    }
    const delay = expiresAt - Date.now();
    if (delay > 0) {
      this.tokenRenewalTimeout = setTimeout(() => {
        this.renewToken()
          .catch(() => {
          });
      }, delay);
    }

    return true;
  }

  /**
   *
   * @param {Function} callback
   */
  onAuthChange(callback) {
    this.authNotifier.on('auth.change', callback);
  }
}

export default new AuthService();
