
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

import 'datatables.net-dt/css/dataTables.dataTables.min.css';
import {onBeforeUnmount, onMounted, ref, watch, nextTick, watchEffect} from "vue";
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import "/resources/css/general.css";
import FormModalFamilia from "@/Pages/Catalogos/Familias/FormModalFamilia.vue";


// Inicia - Bloque de librerias fundamentales
import {router, useForm, usePage} from '@inertiajs/vue3';
import { computed } from 'vue';
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
// Finaliza - Bloque de librerias fundamentales

const familiaRegFis = computed(() => usePage().props.familiaRegFis);
const totalFamiliasRegFis = computed(() => usePage().props.totalFamiliasRegFis);
const familia = computed(() => usePage().props.familia);
const registrosfiscales = computed(() => usePage().props.registrosfiscales);

const processing = ref(false)
const errors = ref({})

const lblFam_id = ref(0);
const lblFam = ref('');

const form = useForm({
    familia_id: familia.value.id,
    registrofiscal_id: 0,
    predeterminado: false,
});

onMounted(async () => {
    await nextTick();

    lblFam_id.value = familia.value.id;
    lblFam.value = familia.value.familia;

});

// Recarga compatible con Inertia
const refreshData = () => {
    router.reload({
        only: ['familiaRegFis', 'totalFamiliasRegFis'],
        preserveScroll: true,
        onFinish: async () => {
            await nextTick();
            // selectedItem.value = null;
        },
    });
};

const reloadPage = function() {
    window.location.reload();
}

const gotoBackPage = function() {
    window.history.back();
}

const submit = () => {

    processing.value = true
    errors.value = {} // Limpiar errores anteriores

    const url = `/familiaRegFis.store`;

    console.log('form', form.predeterminado);

    form.transform(data => ({
        ...data
    })).submit('post', url, {
        preserveScroll: true,
        onError: (err) => {
            processing.value = false
            errors.value = err
        },
        onFinish: () => {
            processing.value = false
            refreshData()
        }

    });
};


const destroy = (itemId) => {
    if (confirm('¿Estás seguro de que deseas eliminar el Item '+itemId+' ?')) {

        const url = route('familiaRegFis.delete', itemId)

        const method = 'delete'

        router[method](url, {
            preserveScroll: true,
            onError: (err) => {
                errors.value = err
            },
            onFinish: () => {
                $("#tblData-"+itemId).remove();
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
            <!-- Encabezado Familia - Actualizado -->
            <div class="mb-8 p-5 bg-indigo-50 rounded-xl border-l-4 border-indigo-600 shadow-xs">
                <div class="flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-indigo-100 rounded-lg">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 5h1m4-5h1m-1 5h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">
                                🏠 Familia: (<span class="text-indigo-700">{{ lblFam_id }}</span>) {{ lblFam }}
                            </h2>
                            <p class="mt-1 text-sm text-gray-600 font-medium">
                                👥 {{ totalFamiliasRegFis }} RFC's asignados
                            </p>
                        </div>
                    </div>

                    <!-- Botón Regresar -->
                    <SecondaryButton
                        @click="gotoBackPage"
                        class="flex items-center gap-2 text-indigo-600 hover:bg-indigo-50 px-4 py-2 rounded-lg transition-colors"
                    >
                        <i class="fas fa-arrow-left"></i>
                        <span class="hidden sm:inline">Regresar</span>
                    </SecondaryButton>
                </div>
            </div>

            <!-- Formulario Agregar - Versión Completa -->
            <div class="mb-8 bg-gray-50 p-5 rounded-xl shadow-inner">
                <form @submit.prevent="submit" class="space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <!-- Select Usuario -->
                        <div class="md:col-span-2 lg:col-span-1">
                            <InputLabel value="👤 Seleccionar un RFC" class="block mb-2.5 font-semibold text-gray-800"/>
                            <select
                                v-model="form.registrofiscal_id"
                                class="w-full p-3 border-2 border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all"
                            >
                                <option :value="0" class="text-gray-400" selected="selected">-- Seleccione un RFC --</option>
                                <option
                                    v-for="regfis in registrosfiscales"
                                    :key="regfis.id"
                                    :value="regfis.id"
                                    class="text-gray-700"
                                >
                                    {{ regfis.rfc }} - {{ regfis.razon_social_cfdi_40 }}
                                </option>
                            </select>
                            <InputError :message="form.errors.registrofiscal_id" class="mt-1.5 text-red-600 text-sm font-medium"/>
                        </div>


                        <div class="md:col-span-2 lg:col-span-1">
                            <InputLabel value="👤 Predeterminado" class="block mb-2.5 font-semibold text-gray-800"/>

                            <div class="inline-flex rounded-xl bg-gray-100 p-1">
                                <button
                                    type="button"
                                    @click="form.predeterminado = true"
                                    class="px-4 py-2 rounded-lg text-sm font-medium transition-colors"
                                    :class="form.predeterminado ? 'bg-indigo-600 text-white' : 'text-gray-600 hover:bg-gray-200'"
                                >
                                    Sí
                                </button>
                                <button
                                    type="button"
                                    @click="form.predeterminado = false"
                                    class="px-4 py-2 rounded-lg text-sm font-medium transition-colors"
                                    :class="!form.predeterminado ? 'bg-indigo-600 text-white' : 'text-gray-600 hover:bg-gray-200'"
                                >
                                    No
                                </button>
                            </div>

                            <InputError :message="form.errors.predeterminado" class="mt-1.5 text-red-600 text-sm font-medium"/>
                        </div>


                        <!-- Botón -->
                        <div class="md:col-span-2 lg:col-span-1 flex items-end">
                            <PrimaryButton
                                type="submit"
                                class="w-full h-[52px] flex justify-center items-center bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl shadow-md transition-all"
                                :disabled="form.processing"
                            >
                                <template v-if="form.processing">
                                    <i class="fas fa-spinner fa-spin mr-2"></i> ⏳ Procesando...
                                </template>
                                <template v-else>
                                    ✅ Agregar RFC
                                </template>
                            </PrimaryButton>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Tabla de Registros - Versión Mejorada -->
            <div class="overflow-x-auto rounded-xl border border-gray-200 shadow-xs">
                <table class="w-full min-w-[800px]">
                    <thead class="bg-indigo-50">
                    <tr class="text-left text-sm font-semibold text-indigo-900">
                        <th class="px-5 py-4 border-b border-indigo-100">#ID</th>
                        <th class="px-5 py-4 border-b border-indigo-100">RFC</th>
                        <th class="px-5 py-4 border-b border-indigo-100">Razón Social</th>
                        <th class="px-5 py-4 border-b border-indigo-100">Predeterminado</th>
                        <th class="px-5 py-4 border-b border-indigo-100 w-28">Acciones</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                    <tr
                        v-for="item in familiaRegFis"
                        :key="item.id"
                        class="hover:bg-indigo-50/30 transition-colors group"
                    >
                        <td class="px-5 py-4 text-sm font-semibold text-indigo-900">{{ item.id }}</td>
                        <td class="px-5 py-4 font-medium text-gray-900">{{ item.rfc }}</td>
                        <td class="px-5 py-4">
                        <span class="px-3 py-1.5 bg-indigo-100 text-indigo-800 rounded-full text-sm font-semibold shadow-xs inline-flex items-center gap-1.5">
                            <span class="w-2 h-2 bg-indigo-400 rounded-full"></span>
                            {{ item.razon_social_cfdi_40 }}
                        </span>
                        </td>
                        <td class="px-5 py-4">
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
                                    {{ item.predeterminado ? 'Default' : 'Normal' }}
                                </span>
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <button
                                @click="destroy(item.id)"
                                class="text-red-500 hover:text-red-700 p-2 rounded-lg hover:bg-red-50 transition-colors group-hover:opacity-100"
                                title="Eliminar permanentemente"
                            >
                                🗑️
                            </button>
                        </td>
                    </tr>
                    <tr v-if="familiaRegFis.length === 0">
                        <td colspan="4" class="px-5 py-6 text-center text-gray-500 italic">
                            📭 No se encontraron registros
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>



    </AuthenticatedLayout>


</template>
