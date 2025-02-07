import { defineStore } from 'pinia';
import { ref } from 'vue';

interface User {
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
}

export const useUserStore = defineStore('user', () => {
  const id = ref(1)

  } 
);
