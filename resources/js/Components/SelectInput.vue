<template>
    <select
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
    >
        <option value="" disabled>{{ placeholder }}</option>
        <option
            v-for="option in options"
            :key="option[optionValue]"
            :value="option[optionValue]"
        >
            {{ option[optionLabel] }}
        </option>
    </select>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue';

const props = defineProps({
    modelValue: [String, Number, null],
    options: {
        type: Array,
        required: true,
    },
    optionValue: {
        type: String,
        default: 'id',
    },
    optionLabel: {
        type: String,
        default: 'name', // Cambia a 'nombre' o 'descripcion' según tus modelos
    },
    placeholder: {
        type: String,
        default: 'Selecciona una opción',
    },
});

defineEmits(['update:modelValue']);
</script>
