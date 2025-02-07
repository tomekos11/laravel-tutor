<template>
  <div class="text-h6 q-my-md">
    <span class="text-accent">Login</span> your account
  </div>
  <q-form @submit.prevent="loginRequest">
    <q-input
      v-model="login"
      style="border-radius: 4px;"
      class="q-mb-sm bg-white"
      outlined
      label="Login"
      color="accent"
    />
    <q-input
      v-model="password"
      :type="isPwd ? 'password' : 'text'"
      style="border-radius: 4px;"
      class="q-mb-sm bg-white"
      outlined
      label="Password"
      color="accent"
    >
      <template #append>
        <q-icon
          :name="isPwd ? 'visibility_off' : 'visibility'"
          class="cursor-pointer"
          @click="isPwd = !isPwd"
        />
      </template>
    </q-input>
    <div>
      <q-checkbox
        v-model="remember"
        label="Remember me"
        class="q-mb-sm"
        color="purple-6"
      />
    </div>
    <q-btn
      type="submit"
      class="full-width"
      color="purple-9"
    >
      Submit
    </q-btn>
    <div
      class="column justify-center text-center q-my-md text-bold text-grey-8"
      style="line-height: 28px;"
    >
      <router-link
        class="text-hover-underline text-grey-8"
        :to="{ name: 'register' }"
      >
        Create an account
      </router-link>
      <span class="text-hover-underline">
        Forgot password?
      </span>
    </div>
  </q-form>
</template>

<script setup lang="ts">
import { api } from 'src/boot/axios';
import { ref } from 'vue';

const isPwd = ref(true);

const login = ref('');
const password = ref('');
const remember = ref(false);

const loginRequest = async () => {
  try {
    const res = await api.post('/api/login', {
      login: login.value,
      password: password.value
    });
    console.log(res.data);
  } catch (err) {
    console.error(err);
  }
};
</script>
