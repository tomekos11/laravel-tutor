import { defineBoot } from "@quasar/app-vite/wrappers";
import { api } from "./axios";
import { useUserStore } from "src/stores/user-store";

export default defineBoot(async () => {
  const userStore = useUserStore();
  try {
    const res = await api.get('/api/me');
    if (res.data.success) {
      userStore.setUser(res.data.user);
    }
  } catch (err) {
    console.error(err);
  }
});
