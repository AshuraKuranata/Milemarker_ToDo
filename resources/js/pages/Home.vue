<script setup lang="ts">
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

// Check for user authenticated or not
const props = defineProps<{
    user: User | null;
}>();

if (props.user) {
    router.visit('/todolists');
}
</script>


<template>
    <div>
        <Head title="Home" />
        <GuestPage v-if="!user" />
    </div>
</template>