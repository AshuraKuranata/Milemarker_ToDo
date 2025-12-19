<script setup lang="ts">
// UPDATED: Added auth support and logout functionality
import { Link, router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const auth = computed(() => page.props.auth);

// Handle logout
const logout = () => {
    router.post('/logout');
};
</script>

<template>
  <nav class="bg-gray-800 shadow-lg">
    <div class="container mx-auto px-4">
      <div class="flex justify-between items-center py-4">
        <!-- Logo -->
        <Link href="/" class="text-white text-2xl font-bold hover:text-blue-400 transition">
          📝 To-Do App
        </Link>

        <!-- Navigation Links -->
        <div class="flex items-center space-x-6">

          <!-- REFACTORED: Show different links based on authentication status -->
          <template v-if="auth.user">
            
            <!-- Authenticated User Links -->
            <span class="text-gray-400">
              Welcome, <span class="text-white font-semibold">{{ auth.user.name }}</span>
            </span>
            <button
              @click="logout"
              class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded transition"
            >
              Logout
            </button>
          </template>

          <template v-else>
            <!-- Guest Links -->
            <Link
              href="/login"
              class="text-gray-300 hover:text-white transition font-medium"
            >
              Login
            </Link>
            <Link
              href="/register"
              class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition"
            >
              Register
            </Link>
          </template>
        </div>
      </div>
    </div>
  </nav>
</template>
