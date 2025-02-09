import { boot } from 'quasar/wrappers';
import type { AxiosInstance } from 'axios';
import axios from 'axios';

declare module '@vue/runtime-core' {
  interface ComponentCustomProperties {
    $axios: AxiosInstance;
    $api: AxiosInstance;
  }
}

console.log(process.env?.API_URL);
const api = axios.create({
  baseURL: process.env?.API_URL,
  withCredentials: true,
});

export default boot(({ app }) => {
  // Globalne ustawienia dla axios
  axios.defaults.withCredentials = true; // 👈 Ustawienie domyślne dla wszystkich instancji axios

  app.config.globalProperties.$axios = axios;
  app.config.globalProperties.$api = api;
});

export { api };
