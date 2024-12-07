<template>
  <div>
    <NavigationButtons />
    <nuxt />
  </div>
      <!-- Centered greeting -->
    <div class="flex items-center justify-center min-h-screen bg-slate-200">
      <p class="text-6xl xs:text-4xl md:text-8xl animate-pulse ">Hello there!</p>

    </div>
</template>

<script>
import NavigationButtons from '~/components/default.vue'

export default {
  components: {
    NavigationButtons
  }
}
</script>