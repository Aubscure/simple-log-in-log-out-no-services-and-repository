<template>
  <div>
    <!-- Navigation bar -->
    <div class="fixed top-0 left-0 z-50 w-full p-5">
      <nav class="p-5 bg-white shadow-md md:flex md:items-center md:justify-between rounded-2xl">
        <div class="flex items-center justify-between text-4xl">
          <p class="flex items-center justify-center p-5">Welcome!</p>
          <ion-icon @click="onToggleMenu" name="menu" class="text-3xl transition-transform duration-300 ease-in cursor-pointer md:hidden">M</ion-icon>
        </div>
        <div class="flex items-center justify-center pt-16 pb-5 md:pt-0 md:pb-0" :class="{ 'hidden': isMenuOpen && !$md }">
          <button @click="goTo('/login')" class="px-4 py-2 mr-4 text-xl text-white transition-colors duration-300 bg-blue-500 rounded-md shadow-md hover:bg-blue-900">Log In</button>
          <button @click="goTo('/register')" class="px-4 py-2 text-xl text-black transition-colors duration-300 bg-transparent rounded-md shadow-md hover:bg-blue-900 hover:text-white">Sign Up</button>
        </div>
      </nav>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      isMenuOpen: false,
      $md: false
    };
  },
  mounted() {
    this.$md = window.matchMedia('(min-width: 768px)').matches;
    window.addEventListener('resize', this.handleResize);
  },
  beforeDestroy() {
    window.removeEventListener('resize', this.handleResize);
  },
  methods: {
    goTo(path) {
      this.$router.push(path);
    },
    onToggleMenu() {
      this.isMenuOpen = !this.isMenuOpen;
    },
    handleResize() {
      this.$md = window.matchMedia('(min-width: 768px)').matches;
      if (this.$md) {
        this.isMenuOpen = false; // Close menu on resize to desktop
      }
    }
  }
};
</script>
