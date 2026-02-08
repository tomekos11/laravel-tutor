import type { RouteRecordRaw } from 'vue-router';

const routes: RouteRecordRaw[] = [
  {
    path: '/',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      {
        name: 'home',
        path: '',
        component: () => import('pages/HomePage.vue'),
      },
      {
        name: 'about-us',
        path: 'about-us',
        component: () => import('pages/AboutUsPage.vue'),
      },
      {
        name: 'become-tutor',
        path: 'become-tutor',
        component: () => import('pages/BecomeTutorPage.vue'),
      },
      {
        name: 'find-tutor',
        path: 'find-tutor',
        component: () => import('pages/FindTutorPage.vue'),
      },
      {
        name: 'login',
        path: 'login',
        component: () => import('pages/LoginPage.vue'),
      },
      {
        name: 'register',
        path: 'register',
        component: () => import('pages/LoginPage.vue'),
      },
      {
        name: 'profile',
        path: 'profile',
        component: () => import('pages/ProfilePage.vue'),
      },
    ],
  },

  // Always leave this as last one,
  // but you can also remove it
  {
    path: '/:catchAll(.*)*',
    component: () => import('pages/ErrorNotFound.vue'),
  },
];

export default routes;
