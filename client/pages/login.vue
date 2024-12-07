<template>
  <div class="flex flex-col items-center justify-center min-h-screen bg-slate-200">
    <!-- Back button -->
    <div class="mb-4">
      <nuxt-link to="/" class="px-3 py-2 text-white duration-300 bg-blue-500 border rounded-lg hover:border-black hover:bg-white hover:text-black hover:border-1">Back</nuxt-link>
    </div>

    <!-- Login form -->
    <div class="w-full max-w-md">
      <h2 class="mb-4 text-2xl font-semibold">Login</h2>
      <form @submit.prevent="submitForm" class="space-y-4">
        <!-- Email field -->
        <div>
          <label for="email" class="block text-gray-600">Email</label>
          <div>
            <input type="text" name="email" id="email" placeholder="Email Address" v-model="state.user.email" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500">
            <p class="text-red-700">{{ $errors?.user?.email && $errors.user.email[0] }}</p>
          </div>
        </div>

        <!-- Password field -->
        <div>
          <label for="password" class="block text-gray-600">Password</label>
          <div>
            <input type="password" name="password" id="password" placeholder="Password" v-model="state.user.password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500">
            <p class="text-red-700">{{ $errors?.user?.password && $errors.user.password[0] }}</p>
          </div>
        </div>

        <!-- Submit button -->
        <button type="submit" class="w-full py-2 text-white transition-colors duration-300 bg-blue-500 rounded-md shadow-md hover:bg-blue-900">Submit</button>
      </form>
    </div>
  </div>
</template>

<script setup>
import { reactive } from 'vue'
import { useVuelidate } from '@vuelidate/core'
import { required, email } from '@vuelidate/validators'
import { useRouter } from 'vue-router'

const router = useRouter()

const state = reactive({
  errors: null,
  user: {
    email: '',
    password: ''
  }
})

const rules = {
  user: {
    email: { required, email },
    password: { required }
  }
}

const v$ = useVuelidate(rules)

async function submitForm() {
  v$.value.$touch()

  if (!v$.value.$error) {
    const params = {
      email: state.user.email,
      password: state.user.password
    }

    try {
      const response = await fetch('http://127.0.0.1:8000/api/auth/login', {
        method: 'POST',
        body: JSON.stringify(params),
        headers: {
          'Content-Type': 'application/json'
        }
      })

      if (response.ok) {
        const responseData = await response.json()
        console.log('Full response data:', responseData) // Log the full response data

        // Ensure that the token is correctly being returned by the API
        if (responseData.data && responseData.data.token) {
          const token = responseData.data.token
          localStorage.setItem('_token', token) // Save token to localStorage
          console.log('Bearer Token:', token) // Log token to ensure it's correctly saved
          alert('Successfully logged in!')
          router.push('/dashboard') // Redirect to the user dashboard
        } else {
          console.error('Token not found in response data')
        }
      } else {
        const errorData = await response.json()
        console.error('Login error:', errorData)
        state.errors = errorData
      }
    } catch (error) {
      console.error('Error during login:', error)
    }
  }
}

</script>
