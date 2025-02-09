import { defineStore } from 'pinia';
import { computed, ref } from 'vue';

export const useUserStore = defineStore('user', () => {
  const id = ref<number | null>(null);
  const email = ref<string | null>(null);
  const username = ref<string | null>(null);
  const phone = ref<string | null>(null);
  const name = ref<string | null>(null);
  const surname = ref<string | null>(null);
  const birthday = ref<string | null>(null);
  const image = ref<string | null>(null);
  const created_at = ref<string | null>(null);
  const updated_at = ref<string | null>(null);

  const isDataFetched = ref(false);

  function setUser(userData: {
    id: number;
    email: string;
    username: string;
    phone: string | null;
    name: string | null;
    surname: string | null;
    birthday: string | null;
    image: string | null;
    created_at: string;
    updated_at: string;
  }) {
    id.value = userData.id;
    email.value = userData.email;
    username.value = userData.username;
    phone.value = userData.phone;
    name.value = userData.name;
    surname.value = userData.surname;
    birthday.value = userData.birthday;
    image.value = userData.image;
    created_at.value = userData.created_at;
    updated_at.value = userData.updated_at;

    isDataFetched.value = true;
  }

  const clearUser = () => {
    id.value = null;
    email.value = null;
    username.value = null;
    phone.value = null;
    name.value = null;
    surname.value = null;
    birthday.value = null;
    image.value = null;
    created_at.value = null;
    updated_at.value = null;
  }

  return {
    id,
    email,
    username,
    phone,
    name,
    surname,
    birthday,
    image,
    created_at,
    updated_at,
    setUser,
    clearUser,
    isDataFetched,
    isUserLogged: computed(() => id.value !== null)
  };
});
