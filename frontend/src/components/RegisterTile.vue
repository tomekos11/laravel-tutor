<template>
  <div class="text-h6 q-my-md">
    <span class="text-accent">Register</span> new account
  </div>
  <q-form @submit.prevent="loginRequest">
    <q-input
      v-model="username"
      standout="bg-white text-accent"
      style="border-radius: 4px;"
      class="q-mb-sm bg-grey-5"
      outlined
      label="Username"
      color="accent"
      autofocus
      hide-bottom-space
      :rules="[val => !!val || 'Field is required']"
      no-error-icon
    />
    <q-input
      v-model="password"
      standout="bg-white text-accent"
      :type="isPwd ? 'password' : 'text'"
      style="border-radius: 4px;"
      class="q-mb-sm bg-grey-5"
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
    <q-input
      v-model="passwordConfirmation"
      standout="bg-white text-accent"
      :type="isPwd ? 'password' : 'text'"
      style="border-radius: 4px;"
      class="q-mb-sm bg-grey-5"
      outlined
      label="Confirm Password"
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
    <q-btn-group spread>
      <q-btn
        :color="confirmMethod.type === 'email' ? 'purple-5' :''"
        label="email"
        glossy
        @click="confirmMethod.type = 'email'"
      />
      <q-btn
        :color="confirmMethod.type === 'phone' ? 'purple-5' :''"
        label="telefone"
        glossy
        @click="confirmMethod.type = 'phone'"
      />
    </q-btn-group>
    <q-input
      v-model="confirmMethod.value"
      standout="bg-white text-accent"
      square
      :type="confirmMethod.type === 'email' ? 'email' : 'tel'"
      style="border-radius: 4px;"
      class="bg-grey-5"
      outlined
      label=""
      color="accent"
    >
      <template #label>
        <div class="row items-center all-pointer-events">
          <q-icon
            class="q-mr-xs"
            color="deep-orange"
            size="24px"
            name="mail"
          />
          {{ confirmMethod.type === 'email' ? 'Email' : 'Phone' }}
        </div>
      </template>
    </q-input>
    <div class="bg-dark-10">
      <span v-if="confirmMethod.type === 'email'">
        Remember about checking your mail box and confirming an account
      </span>
      <span v-else-if="confirmMethod.type === 'phone'">
        Remember about checking your SMS and confirming an account
      </span>
    </div>
    <q-btn
      type="submit"
      class="full-width"
      color="purple-9"
    >
      Submit
    </q-btn>
    <div
      class="column justify-center text-center q-my-lg text-bold text-grey-8"
      style="line-height: 28px;"
    >
      <span class="text-hover-underline">
        Already have an account?
      </span>
    </div>
  </q-form>
</template>

<script setup lang="ts">
import { api } from 'src/boot/axios';
import { Ref, ref } from 'vue';

const isPwd = ref(true);

const username = ref('');
const password = ref('');
const passwordConfirmation = ref('');

interface ConfirmMethod {
  type: 'email' | 'phone',
  value: string
}

const confirmMethod: Ref<ConfirmMethod> = ref({
  type: 'email',
  value: ''
});

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

<style scoped>
  :deep(.q-field__bottom) {
    background: transparent;
  }
</style>
