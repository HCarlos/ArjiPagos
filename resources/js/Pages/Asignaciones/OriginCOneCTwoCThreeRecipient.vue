<script setup>
import {ref, computed, onMounted, watch} from 'vue'
import {Head, router, usePage} from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { useToast } from 'vue-toast-notification'
const toast = useToast()

// const props = defineProps({
//     titulo: String
// })

// const props = defineProps({
//     leftDisponibles: Array,
//     rightAsignados: Array,
//     titulo: String
// })

const combo1 = computed(() => usePage().props.combo1 ?? []);
const combo2 = computed(() => usePage().props.combo2 ?? []);
const combo3 = computed(() => usePage().props.combo3 ?? []);
const leftDisponibles = computed(() => usePage().props.leftDisponibles ?? []);
const rightAsignados = computed(() => usePage().props.rightAsignados ?? []);
const titulo = ref( computed(() => usePage().props.titulo) );
const urlAdd = ref( computed(() => usePage().props.urlAdd) );
const urlDelete = ref( computed(() => usePage().props.urlDelete) );
const urlAsignados = ref( computed(() => usePage().props.urlAsignados) ); // urlAsignados

// — Estados reactivos —
const selectedCombo1 = ref(combo1.value[0] ?? 0)
const selectedCombo2 = ref(combo2.value[0] ?? 0)
const selectedCombo3 = ref(combo3.value[0] ?? 0)
const leftSelected  = ref([])
const rightSelected = ref([])
const isLoading     = ref(false)

// — Método para recargar “rightAsignados” sin recargar toda la página —

const fetchGrupos = async () => {

    console.log(selectedCombo1.value, selectedCombo2.value);

    if (!selectedCombo1.value || !selectedCombo2.value) return
    isLoading.value = true
    combo3.value = [];
    rightAsignados.value = [];
    try {
        await router.get(
            route(urlAsignados.value, {combo1_id: selectedCombo1.value, combo2_id: selectedCombo2.value, combo3_id: selectedCombo3.value}),
            {},
            {
                preserveState: true,
                preserveScroll: false,
                only: ['combo3']
            }
        )
    } finally {
        isLoading.value = false
    }
}


const fetchAlumnos = async () => {

    console.log(selectedCombo1.value, selectedCombo2.value);

    if (!selectedCombo1.value || !selectedCombo2.value || !selectedCombo3.value) return
    isLoading.value = true
    rightAsignados.value = [];
    try {
        await router.get(
            route(urlAsignados.value, {combo1_id: selectedCombo1.value, combo2_id: selectedCombo2.value, combo3_id: selectedCombo3.value}),
            {},
            {
                preserveState: true,
                preserveScroll: false,
                only: ['rightAsignados']
            }
        )
    } finally {
        isLoading.value = false
    }
}


onMounted(async () => {

    const combo1Keys  = Object.keys(combo1.value)
    const combo2Keys  = Object.keys(combo2.value)
    const combo3Keys  = Object.keys(combo3.value)

    if (combo1Keys.length > 0 && selectedCombo1.value === 0 ) {
        selectedCombo1.value = combo1Keys[0]
    }
    if (combo2Keys.length > 0 && selectedCombo2.value === 0 ) {
        selectedCombo2.value = combo2Keys[0]
    }

    if (combo3Keys.length > 0 && selectedCombo3.value === 0 ) {
        selectedCombo3.value = combo3Keys[0]
    }

    await fetchGrupos();

    // console.log(selectedCombo1.value, selectedCombo2.value); // Imprime selectedCombo1 y   selectedCombo2
});

// — Asignar/grabar cambios —
const agregarItems = () => {
    const ids   = leftSelected.value
    const combo1 = selectedCombo1.value
    const combo2 = selectedCombo2.value
    const combo3 = selectedCombo3.value

    if (!ids.length || !combo1 || !combo2 || !combo3) return

    isLoading.value = true

    router.post(
        route(urlAdd.value),
        { ids, combo1_id: combo1, combo2_id: combo2, combo3_id: combo3 },
        {
            preserveScroll: true,
            onSuccess: () => {
                fetchGrupos()
                leftSelected.value = []
            },
            onError: (errors) => {
                console.error('Error asignando grupos:', errors)
                toast.error(errors.message);
            },
            onFinish: () => {
                isLoading.value = false
            },
        }
    );
};


const quitarItems = () => {
    const ids   = rightSelected.value;
    const combo1 = selectedCombo1.value;
    const combo2 = selectedCombo2.value;
    const combo3 = selectedCombo3.value;

    // Validación temprana
    if (!ids.length || !combo1 || !combo2 || !combo3) return;

    isLoading.value = true;

    router.post(
        route(urlDelete.value),
        { ids, combo1_id: combo1, combo2_id: combo2, combo3_id: combo3 },
        {
            preserveScroll: true,
            onSuccess: () => {
                fetchGrupos();
                rightSelected.value = [];
            },
            onError: (errors) => {
                console.error('Error quitando grupos:', errors);
                // aquí podrías mostrar un mensaje de error al usuario
                toast.error(errors.message);
            },
            onFinish: () => {
                isLoading.value = false;
            },
        }
    );
};


</script>

<template>

    <Head :title="titulo" />

    <AuthenticatedLayout  >

        <template #header>
            {{ titulo }}
        </template>

        <div class="h-screen flex flex-col md:flex-row gap-4 p-4 bg-gray-50 overflow-hidden">

            <!-- Panel Disponibles -->
            <section class="flex-1 flex flex-col bg-white rounded-xl shadow-lg overflow-hidden md:min-w-[320px]">
                <header class="p-4 border-b border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wide">
                        Disponibles
                        <span class="text-blue-500 ml-1">({{ Object.keys(leftDisponibles).length }})</span>
                    </h3>
                </header>

                <div class="flex-1 overflow-y-auto relative">
                    <div class="absolute inset-0">
                        <template v-if="Object.keys(leftDisponibles).length">
                            <label
                                v-for="item in leftDisponibles"
                                :key="item.id"
                                class="flex items-center px-4 py-3 space-x-3 hover:bg-blue-50/50 group transition-colors cursor-pointer"
                            >
                                <input
                                    type="checkbox"
                                    :value="item.id"
                                    v-model="leftSelected"
                                    class="h-4 w-4 text-blue-500 border-gray-300 rounded focus:ring-blue-500 group-hover:border-blue-400 transition-colors"
                                />
                                <span class="text-sm font-medium text-gray-700">{{ item.descripcion }}</span>
                            </label>
                        </template>
                        <p v-else class="p-6 text-center text-sm text-gray-400">
                            ✨ No hay grupos disponibles
                        </p>
                    </div>
                </div>

                <footer class="p-3 border-t border-gray-100 bg-gray-50">
                    <span class="text-xs font-medium text-gray-500">
                        {{ Object.keys(leftDisponibles).length }} elementos listados
                    </span>
                </footer>
            </section>

            <!-- Panel de Controles -->
            <div class="flex flex-row md:flex-col items-center justify-center gap-3 py-4 md:py-0 md:px-4">
                <button
                    @click="agregarItems"
                    :disabled="isLoading || !leftSelected.length"
                    class="w-full md:w-40 px-6 py-2.5 flex items-center justify-center gap-2 rounded-xl font-medium
                   bg-gradient-to-b from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700
                   text-white shadow-sm hover:shadow-md disabled:opacity-50 disabled:grayscale
                   transition-all duration-200"
                >
                    <svg v-if="isLoading" class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="hidden md:inline">Asignar</span>
                    <span class="md:hidden">▶</span>
                </button>

                <button
                    @click="quitarItems"
                    :disabled="isLoading || !rightSelected.length"
                    class="w-full md:w-40 px-6 py-2.5 flex items-center justify-center gap-2 rounded-xl font-medium
                   bg-gradient-to-b from-red-500 to-red-600 hover:from-red-600 hover:to-red-700
                   text-white shadow-sm hover:shadow-md disabled:opacity-50 disabled:grayscale
                   transition-all duration-200"
                >
                    <svg v-if="isLoading" class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="hidden md:inline">Quitar</span>
                    <span class="md:hidden">◀</span>
                </button>
            </div>

            <!-- Panel Asignados -->
            <section class="flex-1 flex flex-col bg-white rounded-xl shadow-lg overflow-hidden md:min-w-[360px]">
                <header class="p-4 border-b border-gray-100 space-y-3">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <select
                            v-model="selectedCombo1"
                            @change="fetchGrupos"
                            class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-lg bg-white
                           text-gray-700 focus:ring-2 focus:ring-blue-400 focus:border-blue-400
                           hover:border-gray-300 transition-all"
                        >
                            <option v-for="(descripcion, id) in combo1" :key="id" :value="id">
                                {{ descripcion }}
                            </option>
                        </select>

                        <select
                            v-model="selectedCombo2"
                            @change="fetchGrupos"
                            class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-lg bg-white
                           text-gray-700 focus:ring-2 focus:ring-blue-400 focus:border-blue-400
                           hover:border-gray-300 transition-all"
                        >
                            <option v-for="(descripcion, id) in combo2" :key="id" :value="id">
                                {{ descripcion }}
                            </option>
                        </select>

                        <select
                            v-model="selectedCombo3"
                            @change="fetchAlumnos"
                            class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-lg bg-white
                           text-gray-700 focus:ring-2 focus:ring-blue-400 focus:border-blue-400
                           hover:border-gray-300 transition-all"
                        >
                            <option v-for="(descripcion, id) in combo3" :key="id" :value="id">
                                {{ descripcion }}
                            </option>
                        </select>
                    </div>

                    <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wide mt-2">
                        Asignados
                        <span class="text-red-500 ml-1">({{ Object.keys(rightAsignados).length }})</span>
                    </h3>
                </header>

                <div class="flex-1 overflow-y-auto">
                    <!-- Checks si hay al menos una clave en el objeto -->
                    <template v-if="Object.keys(rightAsignados).length">
                        <label
                            v-for="item in rightAsignados"
                            :key="item.id"
                            class="flex items-center px-4 py-3 space-x-3 hover:bg-red-50/50 cursor-pointer transition-colors"
                        >
                            <input
                                type="checkbox"
                                :value="item.id"
                                v-model="rightSelected"
                                class="h-4 w-4 text-red-500 border-gray-300 rounded focus:ring-red-500 transition-colors"
                            />
                            <span class="text-sm font-medium text-gray-700">{{ item.descripcion }}</span>
                        </label>
                    </template>
                    <p v-else class="p-6 text-center text-sm text-gray-400">
                        🎯 No hay grupos asignados
                    </p>
                </div>

                <footer class="p-3 border-t border-gray-100 bg-gray-50">
                    <span class="text-xs font-medium text-gray-500">
                        {{ Object.keys(rightAsignados).length }} elementos asignados
                    </span>
                </footer>
            </section>
        </div>



    </AuthenticatedLayout>


</template>
