<script setup lang="ts">
// UPDATED: Individual todo item component with edit functionality
import { router } from '@inertiajs/vue3';

interface Todo {
    id: number;
    task_name: string;
    due_by: string;
    task_priority: string;
    task_status: boolean;
    todo_list_id: number;
}

interface Props {
    todo: Todo;
}

defineProps<Props>();

const emit = defineEmits<{
    edit: [todo: Todo];
}>();

const toggleStatus = (todoId: number) => {
    router.patch(`/todos/${todoId}/toggle`);
};

const deleteTodo = (todoId: number) => {
    if (confirm('Are you sure you want to delete this task?')) {
        router.delete(`/todos/${todoId}`);
    }
};

const getPriorityClasses = (priority: string) => {
    switch (priority) {
        case 'High':
            return 'border-red-500 bg-red-50';
        case 'Medium':
            return 'border-orange-400 bg-orange-50';
        case 'Low':
            return 'border-green-500 bg-green-50';
        default:
            return 'border-gray-300 bg-gray-50';
    }
};

const getPriorityTextColor = (priority: string) => {
    switch (priority) {
        case 'High':
            return 'text-red-700';
        case 'Medium':
            return 'text-orange-600';
        case 'Low':
            return 'text-green-600';
        default:
            return 'text-gray-600';
    }
};
</script>

<template>
    <div 
        class="border-l-4 p-3 mb-2 rounded"
        :class="getPriorityClasses(todo.task_priority)"
    >
        <div class="flex justify-between items-start">
            <div class="flex-1">
                <div class="flex items-center gap-2">

                    <!-- Checkbox to toggle task status -->
                    <input
                        type="checkbox"
                        :checked="todo.task_status"
                        @change="toggleStatus(todo.id)"
                        class="w-5 h-5 cursor-pointer"
                    />
                    <h4
                        class="text-lg font-semibold"
                        :class="{ 'line-through text-gray-500': todo.task_status }"
                    >
                        {{ todo.task_name }}
                    </h4>
                </div>
                <p class="text-sm text-gray-600 ml-7">Due: {{ todo.due_by }}</p>
                <p class="text-sm font-bold ml-7">
                    Priority:
                    <span :class="getPriorityTextColor(todo.task_priority)">
                        {{ todo.task_priority }}
                    </span>
                </p>
                <p 
                    class="text-sm ml-7" 
                    :class="todo.task_status ? 'text-green-600 font-semibold' : 'text-gray-500'"
                >
                    {{ todo.task_status ? '✓ Complete' : 'Incomplete' }}
                </p>
            </div>

            <!-- Edit and Delete -->
            <div class="flex gap-2">

                <button
                    @click="emit('edit', todo)"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded text-xs transition"
                >
                    Edit
                </button>

                <button
                    @click="deleteTodo(todo.id)"
                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded text-xs transition"
                >
                    Delete
                </button>

            </div>
        </div>
    </div>
</template>

