<script setup lang="ts">
// UPDATED: Todo list card component with tasks, edit, and filter functionality
import { ref, computed } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import TodoForm from './TodoForm.vue';
import TodoItem from './TodoItem.vue';

interface Todo {
    id: number;
    task_name: string;
    due_by: string;
    task_priority: string;
    task_status: boolean;
    todo_list_id: number;
    created_at?: string;
}

interface TodoList {
    id: number;
    list_name: string;
    user_id: number;
    tasks: Todo[];
}

interface Props {
    todoList: TodoList;
}

const props = defineProps<Props>();

// Display states
const isExpanded = ref(false);
const showAddTaskForm = ref(false);
const editingTodo = ref<Todo | null>(null);
const showEditListForm = ref(false);

// Sorting & filtering tasks
type SortOption = 'due_date' | 'priority' | 'created_date';
const sortBy = ref<SortOption>('due_date');
type FilterOption = 'all' | 'Complete' | 'Incomplete';
const filterBy = ref<FilterOption>('all');

const sortedTasks = computed(() => {
    let tasks = [...props.todoList.tasks];

    switch (filterBy.value) {
        case 'Complete':
            tasks = tasks.filter(task => task.task_status === true);
            break;
        case 'Incomplete':
            tasks = tasks.filter(task => task.task_status === false);
            break;
        case 'all':
        default:
            break;
    }

    switch (sortBy.value) {
        case 'due_date':
            return tasks.sort((a, b) => new Date(a.due_by).getTime() - new Date(b.due_by).getTime());

        case 'priority':
            const priorityOrder = { 'High': 1, 'Medium': 2, 'Low': 3 };
            return tasks.sort((a, b) => {
                const aPriority = priorityOrder[a.task_priority as keyof typeof priorityOrder] || 4;
                const bPriority = priorityOrder[b.task_priority as keyof typeof priorityOrder] || 4;
                return aPriority - bPriority;
            });

        case 'created_date':
            return tasks.sort((a, b) => {
                if (a.created_at && b.created_at) {
                    return new Date(a.created_at).getTime() - new Date(b.created_at).getTime();
                }
                return a.id - b.id;
            });

        default:
            return tasks;
    }
});

// Component Functions
const toggleAccordion = () => {
    isExpanded.value = !isExpanded.value;
};

const handleEditTask = (todo: Todo) => {
    editingTodo.value = todo;
    showAddTaskForm.value = false; 
};

const closeEditForm = () => {
    editingTodo.value = null;
};

const editListForm = useForm({
    list_name: props.todoList.list_name,
});

const toggleEditListForm = () => {
    showEditListForm.value = !showEditListForm.value;
    if (showEditListForm.value) {
        editListForm.list_name = props.todoList.list_name;
    }
};

const submitListEdit = () => {
    editListForm.put(`/todolists/${props.todoList.id}`, {
        onSuccess: () => {
            showEditListForm.value = false;
        },
    });
};

const deleteTodoList = (todolistId: number) => {
    if (confirm('Are you sure you want to delete this todo list? All tasks will be deleted.')) {
        router.delete(`/todolists/${todolistId}`);
    }
};
</script>

<template>
    <div class="bg-white border border-gray-300 rounded-lg mb-4 shadow hover:shadow-lg transition">
        
        <!-- List Header: handles list display -->
        <div
            @click="toggleAccordion"
            class="flex justify-between items-center p-4 cursor-pointer hover:bg-gray-50 transition rounded-t-lg"
        >
            <div class="flex items-center gap-3">
                <svg
                    class="w-5 h-5 text-gray-600 transition-transform duration-200"
                    :class="{ 'rotate-90': isExpanded }"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>

                <h3 class="text-xl font-bold text-gray-800">{{ todoList.list_name }}</h3>

                <!-- Edit List Name -->
                <button
                    @click.stop="toggleEditListForm"
                    class="text-gray-500 hover:text-blue-600 transition"
                    title="Edit list name"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                </button>

                <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                    {{ todoList.tasks.length }} {{ todoList.tasks.length === 1 ? 'task' : 'tasks' }}
                </span>
            </div>
        </div>

        <!-- Edit List Name Form -->
        <div v-if="showEditListForm" class="border-t border-gray-200 bg-gray-50 p-4">
            <form @submit.prevent="submitListEdit" class="flex gap-2 items-center">
                <input
                    v-model="editListForm.list_name"
                    type="text"
                    class="flex-1 shadow-sm border border-gray-300 rounded-lg py-2 px-3 text-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="List name"
                    required
                />
                <button
                    type="submit"
                    :disabled="editListForm.processing"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition disabled:opacity-50"
                >
                    {{ editListForm.processing ? 'Saving...' : 'Save' }}
                </button>
                <button
                    type="button"
                    @click="showEditListForm = false"
                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg transition"
                >
                    Cancel
                </button>
            </form>
            <div v-if="editListForm.errors.list_name" class="text-red-500 text-sm mt-1">
                {{ editListForm.errors.list_name }}
            </div>
        </div>

        <!-- Tasks list -->
        <div v-show="isExpanded" class="border-t border-gray-200">
            <div class="p-4">
                <!-- Filter, Sort, and Action Buttons -->
                <div class="flex justify-between items-center mb-4 flex-wrap gap-3">

                    <!-- Filter and Sort -->
                    <div class="flex items-center gap-4 flex-wrap">

                        <!-- Filter -->
                        <div class="flex items-center gap-2">
                            <label class="text-sm font-medium text-gray-700">Filter:</label>
                            <select
                                v-model="filterBy"
                                class="text-sm border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                                <option value="all">All Tasks</option>
                                <option value="Incomplete">Incomplete</option>
                                <option value="Complete">Complete</option>
                            </select>
                        </div>

                        <!-- Sorting -->
                        <div class="flex items-center gap-2">
                            <label class="text-sm font-medium text-gray-700">Sort by:</label>
                            <select
                                v-model="sortBy"
                                class="text-sm border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                                <option value="due_date">Due Date</option>
                                <option value="priority">Priority</option>
                                <option value="created_date">Created Date</option>
                            </select>
                        </div>
                    </div>

                    <!-- Actions for list: adding tasks or deleting the list -->
                    <div class="flex gap-2">
                        <!-- Add Task -->
                        <button
                            @click.stop="showAddTaskForm = !showAddTaskForm; editingTodo = null"
                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-3 rounded text-sm transition"
                        >
                            {{ showAddTaskForm ? 'Cancel' : '+ Add Task' }}
                        </button>

                        <!-- Delete List -->
                        <button
                            @click.stop="deleteTodoList(todoList.id)"
                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded text-sm transition"
                        >
                            Delete List
                        </button>
                    </div>
                </div>

                <!-- Add Task Form -->
                <TodoForm
                    v-if="!editingTodo"
                    :todo-list-id="todoList.id"
                    :show="showAddTaskForm"
                    @close="showAddTaskForm = false"
                />

                <!-- Edit Task Form -->
                <TodoForm
                    v-if="editingTodo"
                    :todo-list-id="todoList.id"
                    :todo="editingTodo"
                    :show="true"
                    @close="closeEditForm"
                />

                <!-- Tasks List handling -->
                <!-- If list has no tasks -->
                <div v-if="todoList.tasks.length === 0" class="text-gray-500 italic text-sm py-4 text-center">
                    No tasks yet. Add one to get started!
                </div>

                <!-- Filter has zero tasks -->
                <div v-else-if="sortedTasks.length === 0" class="text-gray-500 italic text-sm py-4 text-center">
                    No tasks match the current filter.
                </div>

                <!-- Show tasks based on sorting and filtering -->
                <div v-else class="space-y-2">
                    <TodoItem
                        v-for="task in sortedTasks"
                        :key="task.id"
                        :todo="task"
                        @edit="handleEditTask"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

