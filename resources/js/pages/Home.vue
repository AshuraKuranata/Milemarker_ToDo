<script setup lang="ts">
// REFACTORED: Simplified Home.vue to use component-based architecture
import { Head, router } from '@inertiajs/vue3';
import GuestPage from './components/GuestPage.vue';

interface Todo {
    id: number;
    task_name: string;
    due_by: string;
    task_priority: string;
    task_status: boolean;
    todo_list_id: number;
}

interface TodoList {
    id: number;
    list_name: string;
    user_id: number;
    tasks: Todo[];
}

interface User {
    id: number;
    name: string;
    email: string;
    todolists: TodoList[];
}

// Props: user is null for guests, User object for authenticated users
const props = defineProps<{
    user: User | null;
}>();

// REFACTORED: Redirect authenticated users to /todolists route
// This makes Home.vue purely a landing page for guests
if (props.user) {
    router.visit('/todolists');
}
</script>

<!-- REFACTORED: Simplified template using component-based architecture -->

<template>
    <div>
        <Head title="Home" />

        <!-- Show Hero Section for guests only -->
        <GuestPage v-if="!user" />
    </div>
</template>