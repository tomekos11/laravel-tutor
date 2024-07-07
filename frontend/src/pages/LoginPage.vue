<template>
  <q-page class="row items-center justify-evenly">
    <!-- <img src="/frontend/src/assets/login-background.jpg" /> -->
    <q-card
      square
      class="q-pa-sm relative-position q-pa-none bg-purple-3"
      style="width:100vw; height: calc(100vh - 50px); overflow: hidden"
    >
      <div
        class="absolute-center row flex-center text-white"
        style="max-width: 35%; min-width: 400px; background: rgba(0, 0, 0, 0.70); z-index: 3;"
      >
        <div class="col-10">
          <div class="text-h6 q-my-md">
            <span class="text-accent">Login</span> your account
          </div>
          <q-form @submit.prevent="loginRequest">
            <q-input
              v-model="username"
              style="border-radius: 4px;"
              class="q-mb-sm bg-white"
              outlined
              label="Username"
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
              <span class="text-hover-underline">
                Create an account
              </span>
              <span class="text-hover-underline">
                Forgot password?
              </span>
            </div>
          </q-form>
        </div>
      </div>
      <svg
        style="z-index: 2; filter: drop-shadow(0px 0px 12px black);"
        class="absolute-bottom"
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 1440 320"
      >
        <path
          fill="#6a1b9a"
          fill-opacity="1"
          d="M0,128L120,160C240,192,480,256,720,234.7C960,213,1200,107,1320,53.3L1440,0L1440,320L1320,320C1200,320,960,320,720,320C480,320,240,320,120,320L0,320Z"
        />
      </svg>
      <q-img
        v-if="$q.screen.gt.sm"
        :src="leftImgSrc"
        class="absolute-center"
        fit="fill"
      />
    </q-card>
  </q-page>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import leftImgSrc from '../assets/login-background.jpg';
import { api } from 'src/boot/axios';

const isPwd = ref(true);

const username = ref('');
const password = ref('');
const remember = ref(false);

const loginRequest = async () => {
  try {
    const res = await api.post('/api/login', {
      username: username.value,
      password: password.value
    });
    console.log(res.data);
  } catch (err) {
    console.error(err);
  }
};

</script>
