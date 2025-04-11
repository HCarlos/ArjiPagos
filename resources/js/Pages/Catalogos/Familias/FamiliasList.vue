
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import {destroyDataTable, initializeDataTable} from "@/js/arjiapp";
import $ from 'jquery';
import 'datatables.net-dt/css/dataTables.dataTables.min.css';
import DataTable from 'datatables.net-dt';
import {onBeforeUnmount, onMounted, ref, watch} from "vue";

import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { router } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3'
import { computed } from 'vue';
import "/resources/css/general.css";
import FormModalFamilia from "@/Pages/Catalogos/Familias/FormModalFamilia.vue";

const familias = computed(() => usePage().props.familias);
const totalFamilias = computed(() => usePage().props.totalFamilias);

const textSearch = ref('');
const showModal = ref(false);
const selectedItem = ref({});

// Referencia para la instancia de DataTable
const dataTable = ref(null);

const openModal = (item = null) => {
    selectedItem.value = item ?? {};
    showModal.value = true;
};

const handleClose = () => {
    console.log('Evento close recibido') // Para depuración
    selectedItem.value = null
}
// Inicializar DataTable después de montar
onMounted(() => {
    initializeDataTable(dataTable, [[0, 'desc']],50);
});

// Destruir antes de desmontar
onBeforeUnmount(() => {
    destroyDataTable(dataTable);
});

watch(() => familias, (newVal) => {
    // Reinicia tabla aquí si lo necesitas automáticamente
    initializeDataTable(dataTable, [[0, 'desc']], 50);
});

// Destruir DataTable

const destroy = (itemId) => {
    if (confirm('¿Estás seguro de que deseas eliminar el Item '+itemId+' ?')) {

        const url = route('familia.delete', itemId)

        const method = 'delete'

        router[method](url, {
            preserveScroll: true,
            onSuccess: () => {
                alert('El Item '+itemId+' fue eliminado correctamente');
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



</script>

<style>
/* Personalización adicional */
.dataTables_wrapper .dataTables_paginate .paginate_button {
    @apply px-3 py-3 mx-1 rounded border bg-white hover:bg-gray-100 transition-colors;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    @apply bg-blue-500 text-white border-blue-500 hover:bg-blue-600;
}
</style>



<template>

    <AuthenticatedLayout>
        <div class="p-4 bg-white rounded-lg shadow-xs">
            <div class="inline-flex overflow-hidden mb-4 w-full bg-white rounded-lg shadow-md">
                <div class="flex justify-center items-center w-12 bg-blue-500">
                    <svg data-slot="icon" aria-hidden="true" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white ">
                        <path d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Zm6-10.125a1.875 1.875 0 1 1-3.75 0 1.875 1.875 0 0 1 3.75 0Zm1.294 6.336a6.721 6.721 0 0 1-3.17.789 6.721 6.721 0 0 1-3.168-.789 3.376 3.376 0 0 1 6.338 0Z" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </div>

                <div class="px-4 py-2 -mx-3 w-full">
					<span class="mx-3 float-start">
						<span class="font-semibold text-blue-500">({{ totalFamilias }}) Familias</span>
						<p class="text-sm text-gray-600">Catálago general</p>
					</span>

                    <SecondaryButton @click="openModal()">
                        <i class="mr-2 fas fa-plus"></i> Nuevo Registro
                    </SecondaryButton>

                    <FormModalFamilia
                        :show="showModal"
                        :familia="selectedItem"
                        @close="showModal = false"
                        @success="refreshData"
                    />

                    <span
                        class=" float-end px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase sm:grid-cols-9">
                        <form action="/familias_list">
                        <input
                            type="text"
                            name="search"
                            v-model="textSearch"
                            id="search"
                            placeholder="Buscar...">
                        <primary-button :class-btn="'float-end ml-2'" :show="textSearch.length === 0" :disabled="textSearch.length === 0" :type="'submit'" >Buscar</primary-button>
                        </form>
                    </span>
                    <span
                        class=" float-end px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase sm:grid-cols-9">
                        <pagination :links="familias.links" />
                    </span>

                </div>
            </div>

            <div class="mt-6 overflow-x-auto">
                    <table id="datasTable" class="w-full whitespace-no-wrap">
                        <thead>
                        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase bg-gray-50 border-b">
                            <th class="px-4 py-3">ID</th>
                            <th class="px-4 py-3">Familia</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y">
                        <tr v-for="item in familias.data" :key="item.id" class="text-gray-700 delete-button"  :id="'tblData-' + item.id" >
                            <td class="px-4 py-3 text-sm">{{ item.id }}</td>
                            <td class="px-4 py-3 text-sm">{{ item.familia }}</td>
                            <td class="px-4 py-3 text-sm td-buttons">
                                <SecondaryButton as="button" @click="destroy(item.id)" class="icon-button-trash"><i class="fa fa-trash"></i></SecondaryButton>
                                <SecondaryButton as="button" @click="openModal(item)"  class="icon-button-edit"><i class="fa fa-edit"></i></SecondaryButton>
                            </td>

                        </tr>
                        </tbody>
                    </table>
            </div>
        </div>
    </AuthenticatedLayout>


</template>
