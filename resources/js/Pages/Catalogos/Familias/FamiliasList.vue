
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import {initializeDataTable} from "@/js/arjiapp";

import 'datatables.net-dt/css/dataTables.dataTables.min.css';
import {onBeforeUnmount, onMounted, ref, watch, nextTick, watchEffect} from "vue";
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import "/resources/css/general.css";
import FormModalFamilia from "@/Pages/Catalogos/Familias/FormModalFamilia.vue";


// Inicia - Bloque de librerias fundamentales
import { router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
// import {Inertia} from "@inertiajs/inertia";
// import {useDatatableReload} from "@/Components/useDatatableReload.js";
// import $, {data} from 'jquery';
// import DataTable from 'datatables.net-vue3';
import DataTablesLib from 'datatables.net-dt';
// Finaliza - Bloque de librerias fundamentales

const dataTable = ref(null);

const familias = computed(() => usePage().props.familias);

// const props = defineProps({
//     familias: {
//         type: Array
//     },
// })

const totalFamilias = computed(() => usePage().props.totalFamilias);

const textSearch = ref('');
const showModal = ref(false);
const selectedItem = ref({});
const modalKey = ref('nuevo');

onMounted(async () => {
    await nextTick();
    // dataTable.value = initializeDataTable( dataTable, [[0, 'desc']],10, "familias.index");
});

onBeforeUnmount(() => {
    // dataTable.value.destroy();
});

// Recarga compatible con Inertia
const refreshData = () => {
    router.reload({
        only: ['totalFamilias', 'familias'],
        preserveScroll: true,
        onFinish: async () => {
            await nextTick();
            // if (dataTable.value) {
                // dataTable.value.draw();
                // dataTable.value.destroy(); // Destruye la instancia
                // dataTable.value = initializeDataTable( dataTable, [[0, 'desc']],10);

                showModal.value = false;
                selectedItem.value = null;

            console.log('🔥 Datos actualizados (modo desarrollo)', familias.value.data);
            // }
        },
    });

    // dataTable.value.ajax.url("/familias_list");
    // dataTable.value.data(familias.value.data);
    // dataTable.value.ajax.reload();
};

const openModal = (item) => {
    selectedItem.value = item !== null ? { ...item } : {};
    console.log('Ventana open recibido', selectedItem.value);
    showModal.value = true;
};

const handleClose = () => {
    selectedItem.value = null;
    showModal.value = false;
    console.log('Ventana close recibido')
}


const reloadPage = function() {
    window.location.reload();
}

// watch(
//     () => JSON.parse(JSON.stringify(familias.value.data)), // Clonado profundo
//     (newVal, oldVal) => {
//         nextTick(() => {
//             if (dataTable.value) {
//                 dataTable.value.clear().rows.add(familias).draw(); // Usa props.familias
//                 console.log('🔁 DataTable reinicializado (watch JSON.stringify)');
//             }
//         });
//     },
//     { deep: true, immediate: true }
// );

const destroy = (itemId) => {
    if (confirm('¿Estás seguro de que deseas eliminar el Item '+itemId+' ?')) {

        const url = route('familia.delete', itemId)

        const method = 'delete'

        router[method](url, {
            preserveScroll: true,
            onSuccess: () => {
                // alert('El Item '+itemId+' fue eliminado correctamente');
                $("#tblData-"+itemId).remove();
            },
            onError: (err) => {
                errors.value = err
            },
            onFinish: () => {
                $("#tblData-"+itemId).remove();
            }
        })

    }
}


const gotoFamiliaElements = (item) => {
    selectedItem.value = item !== null ? { ...item } : {};
    window.location.href = '/familiaElements/' + selectedItem.value.id;
};

const gotoFamiliaRegFis = (item) => {
    selectedItem.value = item !== null ? { ...item } : {};
    window.location.href = '/familiaRegFis/' + selectedItem.value.id;
};



</script>


<style>
/* Personalización adicional */
.dataTables_wrapper .dataTables_paginate .paginate_button {
    @apply px-3 py-3 mx-1 rounded border bg-white hover:bg-gray-100 transition-colors;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    @apply bg-blue-500 text-white border-blue-500 hover:bg-blue-600;
}


.custom-easy-table {
    --easy-table-header-font-size: 0.875rem;
    --easy-table-body-row-height: 45px;
}

/* Fuerza la visualización de elementos */
.vue3-easy-data-table__body > tr > td {
    display: table-cell !important;
}


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
                            📚 ({{ totalFamilias }}) Familias
                        </h1>
                        <p class="mt-1 text-sm text-gray-600 font-medium">
                            Catálogo general
                        </p>
                    </div>
                </div>

                <!-- Controles Superiores -->
                <div class="flex items-center gap-3">
                    <button @click="openModal(null)" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Agregar nueva familia">
                        <i class="fas fa-plus-circle fa-lg"></i>
                    </button>
                    <button @click="reloadPage" class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors" title="Recargar datos">
                        <i class="fas fa-sync-alt fa-lg"></i>
                    </button>
                    <FormModalFamilia
                        :key="selectedItem?.value?.id ?? 'nuevo'"
                        :show="showModal"
                        :familia="selectedItem"
                        @close="handleClose"
                        @success="refreshData"
                    />

                </div>
            </div>

            <!-- Barra de Búsqueda y Controles -->
            <div class="mb-6 flex flex-col md:flex-row gap-4 items-center justify-between">
                <!-- Formulario Búsqueda -->
                <form action="/familias" class="w-full md:w-auto flex gap-2">
                    <input
                        type="text"
                        v-model="textSearch"
                        name="search"
                        id="search"
                        placeholder="🔍 Buscar familia..."
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
                    <template v-for="(link, index) in familias.links">
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

            <!-- Tabla de Familias -->
            <div class="overflow-x-auto rounded-xl border border-gray-200 shadow-xs">
                <table class="w-full min-w-[600px]">
                    <thead class="bg-indigo-50">
                    <tr class="text-left text-sm font-semibold text-indigo-900">
                        <th class="px-5 py-4 border-b border-indigo-100">ID</th>
                        <th class="px-5 py-4 border-b border-indigo-100">Familia</th>
                        <th class="px-5 py-4 border-b border-indigo-100 w-48">Acciones</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                    <tr
                        v-for="item in familias.data"
                        :key="item.id"
                        class="hover:bg-indigo-50/30 transition-colors group"
                    >
                        <td class="px-5 py-4 text-sm font-semibold text-indigo-900">{{ item.id }}</td>
                        <td class="px-5 py-4 font-medium text-gray-900">{{ item.familia }}</td>
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
                            <button
                                @click="gotoFamiliaElements(item)"
                                class="text-green-500 hover:text-green-700 p-2 rounded-lg hover:bg-green-50 transition-colors"
                                title="Miembros"
                            >
                                👥
                            </button>
                            <button
                                @click="gotoFamiliaRegFis(item)"
                                class="text-purple-500 hover:text-purple-700 p-2 rounded-lg hover:bg-purple-50 transition-colors"
                                title="Registros Fiscales"
                            >
                                💳
                            </button>
                        </td>
                    </tr>
                    <tr v-if="familias.data.length === 0">
                        <td colspan="3" class="px-5 py-6 text-center text-gray-500 italic">
                            📭 No se encontraron familias
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <!-- Modal -->
        </div>

    </AuthenticatedLayout>


</template>
