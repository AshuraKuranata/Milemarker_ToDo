<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import Navbar from './components/Navbar.vue';
import TodoListForm from './components/TodoListForm.vue';
import TodoListCard from './components/TodoListCard.vue';

interface Todo {
    id: number;
    task_name: string;
    due_by: string;
    task_priority: string;
    task_status: boolean;
    todo_list_id: number;
    created_at?: string;
    updated_at?: string;
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

interface Props {
    user: User;
}

const props = defineProps<Props>();

// Track if create list form is shown
const showCreateListForm = ref(false);
</script>

<template>
    <Navbar />
    <Head title="My Todo Lists" />
    
    <main class="container mx-auto p-4 max-w-6xl">
        <!-- Page Header -->
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-4xl font-bold text-gray-900">My Todo Lists</h1>
                <p class="text-gray-600 mt-2">Manage your tasks and stay organized</p>
            </div>
            
            <!-- New List Button -->
            <button 
                @click="showCreateListForm = !showCreateListForm"
                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition shadow-lg hover:shadow-xl"
            >
                {{ showCreateListForm ? 'Cancel' : '+ New List' }}
            </button>
        </div>

        <!-- Create Todo List Form -->
        <TodoListForm 
            :user-id="user.id" 
            :show="showCreateListForm"
            @close="showCreateListForm = false"
        />

        <!-- Empty State -->
        <div v-if="!user.todolists || user.todolists.length === 0" class="text-center py-16">
            <div class="text-6xl mb-4">📝</div>
            <h3 class="text-2xl font-semibold text-gray-700 mb-2">No todo lists yet</h3>
            <p class="text-gray-500 mb-6">Create your first list to get started!</p>
            <button 
                @click="showCreateListForm = true"
                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition inline-block"
            >
                Create Your First List
            </button>
        </div>

        <!-- Todo Lists Grid -->
        <div v-else class="space-y-4">
            <TodoListCard 
                v-for="todolist in user.todolists" 
                :key="todolist.id" 
                :todo-list="todolist"
            />
        </div>
    </main>
</template>

