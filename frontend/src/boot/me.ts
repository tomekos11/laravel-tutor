import { defineBoot } from '@quasar/app-vite/wrappers';
import { api } from './axios';
import { useUserStore } from 'src/stores/user-store';
import type { ApiResponse, UserFromApi } from 'src/types/api';
import { toUserStoreUser } from 'src/types/api';


export default defineBoot(async ctx => {
  // boot odpalany tylko w SSR (client: false w quasar.config)
  if (!ctx.ssrContext) {
    return;
  }

  const pinia = ctx.store;
  const userStore = pinia ? useUserStore(pinia) : useUserStore();

  try {
    // cookies zbindowane do requestu
    // const cookies = Cookies.parseSSR(ctx.ssrContext); // [web:1][web:5]

    const cookieHeader = ctx.ssrContext.req?.headers?.cookie;
    const config =
      typeof cookieHeader === 'string'
        ? { headers: { Cookie: cookieHeader } }
        : undefined;

    const { data } = await api.get<ApiResponse<UserFromApi>>('/api/me', config);

    if (data?.success && data?.data) {
      userStore.setUser(toUserStoreUser(data.data));
    }
  } catch (err) {
    if (typeof process !== 'undefined' && process.env?.DEV) {
      console.error('[SSR /me]', err instanceof Error ? err.message : err);
    }
  }
});
