<template>
  <div>
    <!-- NavigationButtons component -->
    <NavigationButtons />
    <nuxt />
  </div>
  <div class="flex flex-col items-center justify-center min-h-screen bg-slate-200">
    <!-- Back button -->
    <div class="mb-4">
      <nuxt-link
        to="/"
        class="px-3 py-2 text-white duration-300 bg-blue-500 border rounded-lg hover:border-black hover:bg-white hover:text-black hover:border-1"
      >Back</nuxt-link>
    </div>

    <!-- Sign Up form -->
    <div class="w-full max-w-md">
      <h2 class="mb-4 text-2xl font-semibold">Register</h2>
      <form @submit.prevent="submitForm" class="space-y-4">
        <!-- Name field -->
        <div>
          <label for="name" class="block text-gray-600">Name</label>
          <div>
            <input
              type="text"
              name="name"
              id="name"
              placeholder="Name"
              v-model="state.user.name"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500"
            />
            <!-- Display validation error if exists -->
            <p class="text-red-700">{{ v$?.value?.user?.name?.$error && v$.value.user.name.$errors[0] }}</p>
          </div>
        </div>

        <!-- Email field -->
        <div>
          <label for="email" class="block text-gray-600">Email</label>
          <div>
            <input
              type="text"
              name="email"
              id="email"
              placeholder="Email Address"
              v-model="state.user.email"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500"
            />
            <!-- Display validation error if exists -->
            <p class="text-red-700">{{ v$?.value?.user?.email?.$error && v$.value.user.email.$errors[0] }}</p>
          </div>
        </div>

        <!-- Password field -->
        <div>
          <label for="password" class="block text-gray-600">Password</label>
          <div>
            <input
              type="password"
              name="password"
              id="password"
              placeholder="Password"
              v-model="state.user.password"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500"
            />
            <!-- Display validation error if exists -->
            <p class="text-red-700">{{ v$?.value?.user?.password?.$error && v$.value.user.password.$errors[0] }}</p>
          </div>
        </div>

        <!-- Submit button -->
        <button
          type="submit"
          class="w-full py-2 text-white transition-colors duration-300 bg-blue-500 rounded-md shadow-md hover:bg-blue-900"
        >Submit</button>
      </form>
    </div>
  </div>
</template>

<script setup>
import { reactive } from 'vue'
import NavigationButtons from '~/components/default.vue'
import { useVuelidate } from '@vuelidate/core'
import { required, email } from '@vuelidate/validators'

// Reactive state
const state = reactive({
  user: {
    name: '',
    email: '',
    password: ''
  }
})

// Validation rules
const rules = {
  user: {
    name: { required },
    email: { required, email },
    password: { required }
  }
}

// Vuelidate instance
const v$ = useVuelidate(rules)

// Form submission function
async function submitForm() {
  // Validate form
  v$.value.$touch()

  // If form is valid
  if (!v$.value.$error) {
    const params = {
      name: state.user.name,
      email: state.user.email,
      password: state.user.password
    }

    try {
      // Make API request to register user 
      const response = await fetch('http://127.0.0.1:8000/api/auth/register', {
        method: 'POST',
        body: JSON.stringify(params),
        headers: {
          'Content-Type': 'application/json'
        }
      })

      // If registration successful
      if (response.ok) {
        const responseData = await response.json()
        localStorage.setItem('_token', responseData.token)
        alert('Successfully registered!')
        // Redirect user to home page
        // Example: navigateTo('/')
      } else {
        // Handle registration errors
        const errorData = await response.json()
        console.error('Registration error:', errorData)
        // Update state with error message
        // state.errors = errorData
      }
    } catch (error) {
      console.error('Error during registration:', error)
      // Handle network or other errors
      // state.errors = { message: 'An error occurred. Please try again later.' }
    }
  }
}
</script>
