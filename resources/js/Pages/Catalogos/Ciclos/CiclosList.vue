
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import {initializeDataTable} from "@/js/arjiapp.js";

import 'datatables.net-dt/css/dataTables.dataTables.min.css';
import {onBeforeUnmount, onMounted, ref, watch, nextTick, watchEffect} from "vue";
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import "/resources/css/general.css";
import FormModalCiclo from "@/Pages/Catalogos/Ciclos/FormModalCiclo.vue";


// Inicia - Bloque de librerias fundamentales
import { router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
// Finaliza - Bloque de librerias fundamentales

const ciclos = computed(() => usePage().props.ciclos);

const totalCiclos = computed(() => usePage().props.totalCiclos);

const textSearch = ref('');
const showModal = ref(false);
const selectedItem = ref({});
const modalKey = ref('nuevo');

onMounted(async () => {
    await nextTick();
});

// Recarga compatible con Inertia
const refreshData = () => {
    router.reload({
        only: ['totalCiclos', 'ciclos'],
        preserveScroll: true,
        onFinish: async () => {
            await nextTick();
                showModal.value = false;
                selectedItem.value = null;
        },
    });
};

const openModal = (item) => {
    selectedItem.value = item !== null ? { ...item } : {};
    showModal.value = true;
};

const handleClose = () => {
    selectedItem.value = null;
    showModal.value = false;
}


const reloadPage = function() {
    window.location.reload();
}

const destroy = (itemId) => {
    if (confirm('¿Estás seguro de que deseas eliminar el Item '+itemId+' ?')) {

        const url = route('ciclo.delete', itemId)

        const method = 'delete'

        router[method](url, {
            preserveScroll: true,
            onError: (err) => {
                errors.value = err
            },
            onFinish: () => {
                refreshData();
            }
        })

    }
}




</script>


<style>


</style>



<template>

    <AuthenticatedLayout>


        <div class="p-6 bg-white rounded-2xl shadow-sm border border-gray-100">
            <!-- Encabezado Principal -->
            <div class="mb-6 p-5 bg-indigo-50 rounded-xl border-l-4 border-indigo-600 shadow-xs flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="p-2.5 bg-indigo-100 rounded-lg">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">
                            📚 ({{ totalCiclos }}) Ciclos
                        </h1>
                        <p class="mt-1 text-sm text-gray-600 font-medium">
                            Catálogo general
                        </p>
                    </div>
                </div>

                <!-- Controles Superiores -->
                <div class="flex items-center gap-3">
                    <button @click="openModal(null)" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Agregar nueva ciclo">
                        <i class="fas fa-plus-circle fa-lg"></i>
                    </button>
                    <button @click="reloadPage" class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors" title="Recargar datos">
                        <i class="fas fa-sync-alt fa-lg"></i>
                    </button>
                    <FormModalCiclo
                        :key="selectedItem?.value?.id ?? 'nuevo'"
                        :show="showModal"
                        :ciclo="selectedItem"
                        @close="handleClose"
                        @success="refreshData"
                    />

                </div>
            </div>

            <!-- Barra de Búsqueda y Controles -->
            <div class="mb-6 flex flex-col md:flex-row gap-4 items-center justify-between">
                <!-- Formulario Búsqueda -->
                <form action="/ciclos" class="w-full md:w-auto flex gap-2">
                    <input
                        type="text"
                        v-model="textSearch"
                        name="search"
                        id="search"
                        placeholder="🔍 Buscar ciclo..."
                        class="w-full md:w-64 p-2.5 border-2 border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all"
                    >
                    <PrimaryButton
                        type="submit"
                        class="h-[42px] px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl shadow-md"
                        :show="textSearch.length === 0" :disabled="textSearch.length === 0" :type="'submit'"
                    >
                        Buscar
                    </PrimaryButton>
                </form>

                <!-- Paginación -->
                <nav class="flex gap-1">
                    <template v-for="(link, index) in ciclos.links">
                        <component
                            :is="link.url ? 'a' : 'span'"
                            :href="link.url"
                            class="px-3 py-1.5 rounded-lg transition-colors"
                            :class="{
                        'bg-indigo-600 text-white': link.active,
                        'text-gray-700 hover:bg-gray-100': !link.active && link.url,
                        'text-gray-400': !link.url
                    }"
                            v-html="link.label"
                        />
                    </template>
                </nav>
            </div>

            <!-- Tabla de Ciclos -->
            <div class="overflow-x-auto rounded-xl border border-gray-200 shadow-xs">
                <table class="w-full min-w-[600px]">
                    <thead class="bg-indigo-50">
                    <tr class="text-left text-sm font-semibold text-indigo-900">
                        <th class="px-5 py-4 border-b border-indigo-100">ID</th>
                        <th class="px-5 py-4 border-b border-indigo-100">Ciclo</th>
                        <th class="px-5 py-4 border-b border-indigo-100 flex items-center justify-center gap-2">Activo</th>
                        <th class="px-5 py-4 border-b border-indigo-100 w-48">Acciones</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                    <tr
                        v-for="item in ciclos.data"
                        :key="item.id"
                        class="hover:bg-indigo-50/30 transition-colors group"
                    >
                        <td class="px-5 py-4 text-sm font-semibold text-indigo-900">{{ item.id }}</td>
                        <td class="px-5 py-4 font-medium text-gray-900">{{ item.ciclo }}</td>
                        <td class="px-5 py-4 border-b border-indigo-100">
                            <div class="flex items-center justify-center gap-2">
                                <span
                                    class="relative inline-flex h-6 w-6 items-center justify-center rounded-full"
                                    :class="item.predeterminado ? 'bg-green-100' : 'bg-red-100'"
                                >
                                    {{ item.predeterminado ? '⭐' : '🚫' }}
                                    <span
                                        v-if="item.predeterminado"
                                        class="absolute -top-1 -right-1 inline-flex h-3 w-3 animate-ping rounded-full bg-green-400 opacity-75"
                                    ></span>
                                </span>
                                <span
                                    class="text-sm font-medium"
                                    :class="item.predeterminado ? 'text-green-700' : 'text-red-700'"
                                >
                                    {{ item.predeterminado ? 'Activo' : 'Inactivo' }}
                                </span>
                            </div>
                        </td>
                        <td class="px-5 py-4 flex items-center gap-2">
                            <button
                                @click="destroy(item.id)"
                                class="text-red-500 hover:text-red-700 p-2 rounded-lg hover:bg-red-50 transition-colors"
                                title="Eliminar"
                            >
                                🗑️
                            </button>
                            <button
                                @click="openModal(item)"
                                class="text-blue-500 hover:text-blue-700 p-2 rounded-lg hover:bg-blue-50 transition-colors"
                                title="Editar"
                            >
                                ✏️
                            </button>
                        </td>
                    </tr>
                    <tr v-if="ciclos.data.length === 0">
                        <td colspan="3" class="px-5 py-6 text-center text-gray-500 italic">
                            📭 No se encontraron ciclos
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <!-- Modal -->
        </div>

    </AuthenticatedLayout>


</template>
