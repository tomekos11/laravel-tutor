<template>
  <div class="text-white text-3xl font-semibold flex gap-2">
    <span>Nasza strona opiera się na</span>

    <div
      v-motion 
      tag="span"
      :initial="{ opacity: 0, y: 10 }"
      :enter="{
        opacity: 1,
        y: 0,
        transition: { duration: 300 }
      }"
      :visible="{
        opacity: 1,
        y: 0
      }"
      :tapped="{ scale: 0.95 }"
      class="inline-flex items-center"
    >
      {{ displayedText }}
      <span class="ml-1 w-[1ch] animate-pulse">|</span>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'

const phrases = [
  'zaufaniu',
  'know‑how',
  'doświadczeniu'
]

const currentPhraseIndex = ref(0)
const displayedText = ref('')

const typingSpeed = 80      // ms na literę
const pauseBetween = 1200   // pauza po zakończeniu słowa
const erasingSpeed = 50     // ms na literę przy kasowaniu

function typePhrase () {
  const full = phrases[currentPhraseIndex.value]

  if(!full) return;
  
  if (displayedText.value.length < full.length) {
    displayedText.value = full.slice(0, displayedText.value.length + 1)
    setTimeout(typePhrase, typingSpeed)
  } else {
    // pauza, potem kasujemy
    setTimeout(erasePhrase, pauseBetween)
  }
}

function erasePhrase () {
  if (displayedText.value.length > 0) {
    displayedText.value = displayedText.value.slice(0, -1)
    setTimeout(erasePhrase, erasingSpeed)
  } else {
    // następny wariant
    currentPhraseIndex.value =
      (currentPhraseIndex.value + 1) % phrases.length
    setTimeout(typePhrase, typingSpeed)
  }
}

onMounted(() => {
  typePhrase()
})
</script>
