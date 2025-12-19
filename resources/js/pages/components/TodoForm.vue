<script setup lang="ts">
// UPDATED: Todo (task) creation/edit form component
import { useForm } from '@inertiajs/vue3';
import { watch } from 'vue';

// UPDATED: Added todo prop for editing
interface Todo {
    id: number;
    task_name: string;
    due_by: string;
    task_priority: string;
    task_status: boolean;
    todo_list_id: number;
}

interface Props {
    todoListId: number;
    show: boolean;
    todo?: Todo; // UPDATED: Optional todo for editing
}

const props = defineProps<Props>();

const emit = defineEmits<{
    close: [];
}>();

// UPDATED: Form state - populate with existing todo if editing
const form = useForm({
    task_name: props.todo?.task_name || '',
    due_by: props.todo?.due_by || '',
    task_priority: props.todo?.task_priority || 'Medium',
    task_status: props.todo?.task_status || false,
});

// UPDATED: Watch for todo changes to populate form when editing
watch(() => props.todo, (newTodo) => {
    if (newTodo) {
        form.task_name = newTodo.task_name;
        form.due_by = newTodo.due_by;
        form.task_priority = newTodo.task_priority;
        form.task_status = newTodo.task_status;
    } else {
        form.reset();
    }
}, { immediate: true });

// UPDATED: Submit - create or update based on whether todo exists
const submit = () => {
    if (props.todo) {
        // Update existing todo
        form.put(`/todos/${props.todo.id}`, {
            onSuccess: () => {
                emit('close');
            },
        });
    } else {
        // Create new todo
        form.post(`/todolists/${props.todoListId}/todos`, {
            onSuccess: () => {
                form.reset();
                emit('close');
            },
        });
    }
};
</script>

<template>
    <div v-if="show" class="bg-gray-50 p-4 rounded mb-4 border border-gray-200">
        <!-- UPDATED: Dynamic title based on edit/create mode -->
        <h4 class="text-lg font-semibold mb-3">{{ todo ? 'Edit Task' : 'Add New Task' }}</h4>
        <form @submit.prevent="submit">
            <div class="mb-3">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    Task Name
                </label>
                <input
                    v-model="form.task_name"
                    type="text"
                    placeholder="Enter task name"
                    class="shadow-sm border border-gray-300 rounded-lg w-full py-2 px-3 text-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    required
                />
                <div v-if="form.errors.task_name" class="text-red-500 text-sm mt-1">
                    {{ form.errors.task_name }}
                </div>
            </div>
            <div class="mb-3">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    Due Date
                </label>
                <input
                    v-model="form.due_by"
                    type="date"
                    class="shadow-sm border border-gray-300 rounded-lg w-full py-2 px-3 text-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    required
                />
                <div v-if="form.errors.due_by" class="text-red-500 text-sm mt-1">
                    {{ form.errors.due_by }}
                </div>
            </div>
            <div class="mb-3">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    Priority
                </label>
                <select
                    v-model="form.task_priority"
                    class="shadow-sm border border-gray-300 rounded-lg w-full py-2 px-3 text-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                    <option value="Low">Low</option>
                    <option value="Medium">Medium</option>
                    <option value="High">High</option>
                </select>
            </div>
            <div class="flex justify-end gap-2">
                <button
                    type="button"
                    @click="emit('close')"
                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg transition"
                >
                    Cancel
                </button>
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition disabled:opacity-50"
                >
                    <!-- UPDATED: Dynamic button text based on edit/create mode -->
                    {{ form.processing ? (todo ? 'Updating...' : 'Adding...') : (todo ? 'Update Task' : 'Add Task') }}
                </button>
            </div>
        </form>
    </div>
</template>

