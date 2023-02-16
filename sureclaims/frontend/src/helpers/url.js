export const url = (path) => {
  const myURL = new URL(window.location.href);
  const endpoint = process.env.NODE_ENV === 'production' ?
    myURL.origin : process.env.GRAPHQL_ENDPOINT;
  return `${endpoint}/${path}`;
};

export default {
  url
};
