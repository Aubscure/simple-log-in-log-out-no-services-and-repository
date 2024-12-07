<template>
  <div class="min-h-screen bg-slate-200">
    <!-- Logout Button -->
    <div class="flex items-center justify-center pb-10">
      <nuxt-link
        class="px-3 py-2 text-white duration-300 bg-blue-500 rounded-md hover:bg-red-500"
        to="/login"
        @click="logoutUser"
      >
        Logout
      </nuxt-link>
    </div>

    <h2 class="flex items-center justify-center pt-10 text-6xl">User Dashboard</h2>

    <div class="flex items-center justify-center">
      <table class="m-10 border-2 border-black">
        <thead class="text-3xl">
          <tr class="bg-blue-300">
            <th class="py-3 font-semibold border-2 border-black px-60">Name</th>
            <th class="py-3 font-semibold border-2 border-black px-60">Email</th>
          </tr>
        </thead>

        <tbody class="text-xl bg-slate-50">
          <!-- Loop over the users list to display their name and email -->
          <tr v-for="user in state.users" :key="user.id">
            <td class="py-3 border-black border-x-2 px-60 hover:bg-gray-200">{{ user.name }}</td>
            <td class="py-3 border-black border-x-2 px-60 hover:bg-gray-200">{{ user.email }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { reactive, onMounted } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

const state = reactive({
  users: [],  // Store the list of users here
  isLoading: false,
  errors: null,
})

const fetchUsers = async () => {
  const token = localStorage.getItem('_token')

  if (!token) {
    console.log('No token found')
    router.push('/login') // Redirect to login if no token is found
  }

  state.isLoading = true
  const headers = {
    Authorization: `Bearer ${token}`,
    Accept: 'application/json'
  }

  try {
    const response = await fetch('http://127.0.0.1:8000/api/auth/users', {
      method: 'GET',
      headers: headers
    })

    if (response.ok) {
      const data = await response.json()
      state.users = data.data // Assuming the response contains a 'data' property for the users
      console.log('Fetched users:', state.users) // Log the users array for verification
    } else {
      console.error('Failed to fetch users, status:', response.status)
      state.errors = 'Unable to fetch user data'
    }
  } catch (error) {
    console.error('Error fetching users:', error)
    state.errors = 'An error occurred while fetching users'
  } finally {
    state.isLoading = false
  }
}

const logoutUser = () => {
  localStorage.removeItem('_token')
  router.push('/login')  // Redirect to login page after logout
}

onMounted(() => {
  fetchUsers()
})
</script>
