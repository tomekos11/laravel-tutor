import { defineBoot } from '#q-app/wrappers';
import axios, { type AxiosInstance } from 'axios';

declare module 'vue' {
  interface ComponentCustomProperties {
    $axios: AxiosInstance;
    $api: AxiosInstance;
  }
}

// Be careful when using SSR for cross-request state pollution
// due to creating a Singleton instance here;
// If any client changes this (global) instance, it might be a
// good idea to move this instance creation inside of the
// "export default () => {}" function below (which runs individually
// for each client)

// W SSR (Node) process.env.API_URL może być niezainicjowany – fallback dla dev
const getApiBaseURL = (): string => {
  const envUrl = typeof process !== 'undefined' && process.env?.API_URL;
  if (envUrl) return envUrl;
  if (import.meta.env?.DEV) return 'http://localhost:8000';
  return '';
};

const api = axios.create({
  baseURL: getApiBaseURL(),
  withCredentials: true,
});


export default defineBoot(({ app }) => {
  // for use inside Vue files (Options API) through this.$axios and this.$api
  axios.defaults.withCredentials = true;

  app.config.globalProperties.$axios = axios;
  // ^ ^ ^ this will allow you to use this.$axios (for Vue Options API form)
  //       so you won't necessarily have to import axios in each vue file

  app.config.globalProperties.$api = api;
  // ^ ^ ^ this will allow you to use this.$api (for Vue Options API form)
  //       so you can easily perform requests against your app's API
});

export { api };
