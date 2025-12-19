<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';

interface Props {
    userId: number;
    show: boolean;
}

const props = defineProps<Props>();

const emit = defineEmits<{
    close: [];
}>();

const form = useForm({
    list_name: '',
});

const submit = () => {
    form.post(`/users/${props.userId}/todolists`, {
        onSuccess: () => {
            form.reset();
            emit('close');
        },
    });
};
</script>

<template>
    <div v-if="show" class="bg-white border-2 border-blue-200 rounded-lg p-6 mb-8 shadow-lg">
        <h3 class="text-xl font-semibold mb-4">Create New Todo List</h3>
        <form @submit.prevent="submit">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    List Name
                </label>
                <input
                    v-model="form.list_name"
                    type="text"
                    placeholder="Enter list name (e.g., Work Tasks, Shopping List)"
                    class="shadow-sm border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    required
                />
                <div v-if="form.errors.list_name" class="text-red-500 text-sm mt-1">
                    {{ form.errors.list_name }}
                </div>
            </div>
            <div class="flex justify-end gap-2">
                <button
                    type="button"
                    @click="emit('close')"
                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-6 rounded-lg transition"
                >
                    Cancel
                </button>
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg transition disabled:opacity-50"
                >
                    {{ form.processing ? 'Creating...' : 'Create List' }}
                </button>
            </div>
        </form>
    </div>
</template>

