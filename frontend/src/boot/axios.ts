import { boot } from 'quasar/wrappers';
import axios, { AxiosInstance } from 'axios';

declare module '@vue/runtime-core' {
  interface ComponentCustomProperties {
    $axios: AxiosInstance;
    $api: AxiosInstance;
  }
}

console.log(process.env?.API_URL);
const api = axios.create({
  baseURL: process.env?.API_URL,
  withCredentials: true // 👈 Dodano globalne ustawienie withCredentials
});

export default boot(({ app }) => {
  // Globalne ustawienia dla axios
  axios.defaults.withCredentials = true; // 👈 Ustawienie domyślne dla wszystkich instancji axios
  
  app.config.globalProperties.$axios = axios;
  app.config.globalProperties.$api = api;
});

export { api };