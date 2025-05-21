<template>
    <div class="flex items-start gap-4">
        <label class="relative inline-flex items-center cursor-pointer">
            <input
                type="checkbox"
                class="sr-only"
                :checked="modelValue"
                @change="$emit('update:modelValue', $event.target.checked)"
            >
            <div
                class="w-11 h-6 bg-gray-200 rounded-full transition-colors duration-300 peer"
                :class="[modelValue ? activeColor : 'bg-gray-300', 'focus-within:ring-2 focus-within:ring-indigo-200']"
            >
                <div
                    class="absolute top-0.5 left-0.5 bg-white w-5 h-5 rounded-full shadow-md transform transition-transform duration-300"
                    :class="modelValue ? 'translate-x-5' : 'translate-x-0'"
                ></div>
            </div>
        </label>
        <div v-if="showLabels" class="flex-1">
            <h5 class="text-sm font-semibold text-gray-800">{{ label }}</h5>
            <p class="text-xs text-gray-600 mt-1 leading-relaxed">{{ description }}</p>
        </div>
    </div>
</template>

<script setup>
defineProps({
    modelValue: Boolean,
    label: String,
    description: String,
    activeColor: {
        type: String,
        default: 'bg-indigo-600'
    },
    showLabels: {
        type: Boolean,
        default: true
    }
});

defineEmits(['update:modelValue']);
</script>
