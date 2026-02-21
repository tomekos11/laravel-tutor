<template>
  <q-page class="q-pa-md">
    <div class="row q-col-gutter-md">
      <div class="col-12 col-md-4 flex flex-center">
        <q-avatar size="200px" class="bg-grey-4">
          <q-img
            v-if="form.image"
            :src="form.image"
            fit="cover"
            style="width: 200px; height: 200px"
          />
          <q-icon v-else name="person" size="80px" color="grey-7" />
        </q-avatar>
      </div>
      <div class="col-12 col-md-8">
        <q-form @submit="onSubmit" class="q-gutter-md">
          <q-input
            v-model="form.username"
            outlined
            label="Login (username)"
            readonly
            hint="Nie można zmienić"
          />
          <q-input
            v-model="form.email"
            outlined
            label="Email"
            type="email"
            :rules="[val => !val || /^[^\s@]+@[^\s@]+\.[^\s@]+/.test(val) || 'Nieprawidłowy email']"
          />
          <q-input
            v-model="form.phone"
            outlined
            label="Telefon"
          />
          <q-input
            v-model="form.name"
            outlined
            label="Imię"
          />
          <q-input
            v-model="form.surname"
            outlined
            label="Nazwisko"
          />
          <q-input
            v-model="form.birthday"
            outlined
            label="Data urodzenia"
            hint="Format: RRRR-MM-DD"
          >
            <template #append>
              <q-icon name="event" class="cursor-pointer">
                <q-popup-proxy cover transition-show="scale" transition-hide="scale">
                  <q-date v-model="form.birthday" mask="YYYY-MM-DD">
                    <div class="row items-center justify-end">
                      <q-btn v-close-popup label="OK" color="primary" flat />
                    </div>
                  </q-date>
                </q-popup-proxy>
              </q-icon>
            </template>
          </q-input>
          <q-input
            v-model="form.image"
            outlined
            label="URL zdjęcia"
            hint="Link do obrazka (avatar)"
          />
          <div class="row q-gutter-sm">
            <q-btn type="submit" color="primary" :loading="loading">
              Zapisz zmiany
            </q-btn>
            <q-btn flat label="Anuluj" :to="{ name: 'home' }" />
          </div>
        </q-form>
      </div>
    </div>
  </q-page>
</template>

<script setup lang="ts">
import { useUserStore } from 'src/stores/user-store';
import { reactive, ref, watch } from 'vue';
import { api } from 'src/boot/axios';
import type { ApiResponse, UserFromApi } from 'src/types/api';
import { toUserStoreUser } from 'src/types/api';

const userStore = useUserStore();

const form = reactive({
  username: userStore.username ?? '',
  email: userStore.email ?? '',
  phone: userStore.phone ?? '',
  name: userStore.name ?? '',
  surname: userStore.surname ?? '',
  birthday: userStore.birthday ?? '',
  image: userStore.image ?? '',
});

watch(
  () => ({
    username: userStore.username,
    email: userStore.email,
    phone: userStore.phone,
    name: userStore.name,
    surname: userStore.surname,
    birthday: userStore.birthday,
    image: userStore.image,
  }),
  (next) => {
    form.username = next.username ?? '';
    form.email = next.email ?? '';
    form.phone = next.phone ?? '';
    form.name = next.name ?? '';
    form.surname = next.surname ?? '';
    form.birthday = next.birthday ?? '';
    form.image = next.image ?? '';
  },
  { deep: true }
);

const loading = ref(false);

async function onSubmit() {
  loading.value = true;
  try {
    const { data } = await api.patch<ApiResponse<UserFromApi>>('/api/me', {
      name: form.name || null,
      surname: form.surname || null,
      email: form.email || null,
      phone: form.phone || null,
      birthday: form.birthday || null,
      image: form.image || null,
    });
    if (data.success && data.data) {
      userStore.setUser(toUserStoreUser(data.data));
    }
  } finally {
    loading.value = false;
  }
}
</script>
