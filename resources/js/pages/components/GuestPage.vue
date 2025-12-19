<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import Navbar from './Navbar.vue';
</script>

<template>
    <main class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50">
        <Navbar />
        <div class="container mx-auto px-4 py-20">
            <div class="text-center max-w-4xl mx-auto">

                <!-- Hero Content -->
                <h1 class="text-6xl font-bold text-gray-900 mb-6">
                    Organize Life with
                    <span class="text-blue-600">To-Do Lists</span>
                </h1>
                <p class="text-xl text-gray-600 mb-12">
                    Use this to-do list app to stay on track with your tasks
                </p>

                <div class="flex justify-center gap-4 mb-16">
                    <Link 
                        href="/register" 
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-8 rounded-lg text-lg transition shadow-lg hover:shadow-xl"
                    >
                        Free Registration
                    </Link>
                    <Link 
                        href="/login" 
                        class="bg-white hover:bg-gray-50 text-blue-600 font-bold py-4 px-8 rounded-lg text-lg transition border-2 border-blue-600"
                    >
                        Sign In
                    </Link>
                </div>

                <!-- Features Grid -->
                <div class="grid md:grid-cols-3 gap-8 mt-20">
                    <div class="bg-white p-8 rounded-xl shadow-lg">
                        <div class="text-4xl mb-4">✅</div>
                        <h3 class="text-xl font-bold mb-2">Easy Task Management</h3>
                        <p class="text-gray-600">Create and organize your tasks with a simple, intuitive interface.</p>
                    </div>
                    <div class="bg-white p-8 rounded-xl shadow-lg">
                        <div class="text-4xl mb-4">🎯</div>
                        <h3 class="text-xl font-bold mb-2">Priority Levels</h3>
                        <p class="text-gray-600">Set priorities to focus on what matters most.</p>
                    </div>
                    <div class="bg-white p-8 rounded-xl shadow-lg">
                        <div class="text-4xl mb-4">📅</div>
                        <h3 class="text-xl font-bold mb-2">Due Dates</h3>
                        <p class="text-gray-600">Never miss a deadline with due date tracking.</p>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>

