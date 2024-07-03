<template>
  <q-page class="row items-center justify-evenly">
    <!-- <img src="/frontend/src/assets/login-background.jpg" /> -->
    <q-card
      class="q-pa-sm"
      style="width:80vw; height: 80vh"
    >
      <div class="row">
        <div class="col-7 bg-accent">
          x
        </div>
        <div class="col-5 q-px-lg">
          <div class="text-h6 q-my-md">
            <span class="text-accent">Login</span> your account
          </div>
          <q-form>
            <q-input
              v-model="username"
              class="q-mb-sm"
              outlined
              label="Username"
              color="accent"
            />
            <q-input
              v-model="password"
              class="q-mb-sm"
              outlined
              label="Password"
              color="accent"
            />
            <div>
              <q-checkbox
                v-model="remember"
                label="Remember me"
                class="q-mb-sm"
                color="purple-6"
              />
            </div>
            <q-btn
              class="full-width"
              color="purple-9"
            >
              Submit
            </q-btn>
            <div
              class="column justify-center text-center q-my-md text-bold text-grey-8"
              style="line-height: 28px;"
            >
              <span>
                Create an account
              </span>
              <span>
                Forgot password?
              </span>
            </div>
          </q-form>
        </div>
      </div>

      <!-- <svg
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 1440 320"
      >
        <path
          fill="#0099ff"
          fill-opacity="1"
          d="M0,128L120,160C240,192,480,256,720,234.7C960,213,1200,107,1320,53.3L1440,0L1440,320L1320,320C1200,320,960,320,720,320C480,320,240,320,120,320L0,320Z"
        />
      </svg> -->
    </q-card>
  </q-page>
</template>

<script setup lang="ts">
import { ref } from 'vue';

const username = ref('');
const password = ref('');
const remember = ref(false);

</script>
