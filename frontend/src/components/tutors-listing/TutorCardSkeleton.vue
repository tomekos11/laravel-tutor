<template>
  <q-card
    flat
    bordered
    class="bg-gray-800! border-slate-800 rounded-2xl shadow-sm overflow-hidden"
  >
    <q-card-section class="row items-center q-gutter-md">
      <q-skeleton type="QAvatar" size="72px" dark />
      <div class="col">
        <q-skeleton type="text" width="60%" dark />
        <q-skeleton type="text" width="40%" class="q-mt-xs" dark />
        <div class="row items-center q-mt-sm gap-2">
          <q-skeleton type="rect" width="100px" height="20px" dark />
          <q-skeleton type="text" width="40px" dark />
        </div>
      </div>
    </q-card-section>

    <q-card-section>
      <q-skeleton type="text" dark />
      <q-skeleton type="text" width="90%" dark />
      <q-skeleton type="text" width="70%" dark />
    </q-card-section>

    <q-separator color="slate-800" />

    <q-card-section class="row items-center justify-between">
      <div class="column">
        <q-skeleton type="text" width="80px" height="14px" dark />
        <q-skeleton type="text" width="60px" height="24px" class="q-mt-xs" dark />
      </div>
      <q-skeleton type="rect" width="80px" height="28px" class="rounded-full" dark />
    </q-card-section>
  </q-card>
</template>

<script setup lang="ts">
defineOptions({ name: 'TutorCardSkeleton' })
</script>
