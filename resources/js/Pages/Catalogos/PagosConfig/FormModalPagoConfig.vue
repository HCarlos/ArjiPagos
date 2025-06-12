<script setup>
import {ref, watch, computed, nextTick} from 'vue';
import {useForm, usePage} from '@inertiajs/vue3';
import Modal from '@/Components/Modal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import ToggleSwitch from '@/Components/ToggleSwitch.vue';

const props = defineProps({
    show: Boolean,
    pago: {
        type: Object,
        default: () => ({})
    },
    // Añade un valor por defecto que es un array vacío para asegurar el .map()
    niveles: {
        type: Array,
        default: () => [] // <--- Importante aquí
    },
    conceptos: {
        type: Array,
        default: () => [] // <--- Importante aquí
    },
    emisoresfiscales: {
        type: Array,
        default: () => [] // <--- Importante aquí
    },
    prodservsats: {
        type: Array,
        default: () => [] // <--- Importante aquí
    },
    unidades: {
        type: Array,
        default: () => [] // <--- Importante aquí
    }
});


const emit = defineEmits(['close', 'success']);
const processing = ref(false);

const errors = ref({});
const activeTab = ref(0);
const tabs = [
    { id: 0, label: 'Información Generales' },
    { id: 1, label: 'Domicilio' },
    { id: 2, label: 'Otros Datos' },
    { id: 3, label: 'Solo Alumnos' }
]

const form = useForm({
    id: 0,
    nivel_id: '',
    emisorfiscal_id: '',
    concepto_id: '',
    importe: 0,
    dia_limite: 0,
    dia_de_pago: 0,
    tiene_descuento_beca: false,
    tiene_descuento: false,
    acepta_pagos_diversos: false,
    aplica_al_siguiente_nivel: false,
    aplica_a: false,
    tiene_descuento_por_promocion: false,
    es_facturable: false,
    se_puede_ver_en_pantalla: false,
    orden_prioridad: 0,
    prodservsat_id: '',
    unidadmedidasat_id: '',
    tagpagos: '',
}, {
    casts: {
        importe: 'number',
        dia_limite: 'number',
        dia_de_pago: 'number',
        orden_prioridad: 'number',
        tiene_descuento_beca: 'boolean',
        tiene_descuento: 'boolean',
        acepta_pagos_diversos: 'boolean',
        aplica_al_siguiente_nivel: 'boolean',
        aplica_a: 'boolean',
        tiene_descuento_por_promocion: 'boolean',
        es_facturable: 'boolean',
        se_puede_ver_en_pantalla: 'boolean',
        tagpagos: 'string',
    }
});

// Los computed properties ya están correctos para generar arrays de { value: ..., label: ... }
const nivelesOptions = computed(() => [
    { value: '', label: 'Seleccionar nivel', disabled: true },
    ...props.niveles.map(n => ({
        value: n.id,
        label: `${n.clave_nivel} - ${n.nivel}`
    }))
]);

const conceptosOptions = computed(() => [
    { value: '', label: 'Seleccionar concepto', disabled: true },
    ...props.conceptos.map(c => ({
        value: c.id,
        label: c.concepto
    }))
]);

const emisoresOptions = computed(() => [
    { value: '', label: 'Seleccionar emisor fiscal', disabled: true },
    ...props.emisoresfiscales.map(e => ({
        value: e.id,
        label: e.razon_social_cfdi_40
    }))
]);

const prodservsatsOptions = computed(() => [
    { value: '', label: 'Seleccionar producto/servicio', disabled: true },
    ...props.prodservsats.map(p => ({
        value: p.id,
        label: `${p.producto_servicio}`
    }))
]);

const unidadmedidasatsOptions = computed(() => [
    { value: '', label: 'Seleccionar unidad de medida', disabled: true },
    ...props.unidades.map(u => ({
        value: u.id,
        label: `${u.unidad_medida}`
    }))
]);

watch(() => ({ ...props.pago }), (newVal) => {
    if (newVal.id) {
        // Usa Object.keys para asegurar que todas las propiedades se actualicen
        Object.keys(form).forEach(key => {
            if (key in newVal) {
                // Conversión explícita para tipos especiales
                if (form.casts && form.casts[key] === 'boolean') {
                    form[key] = !!newVal[key];
                }
                else if (form.casts && form.casts[key] === 'number') {
                    form[key] = Number(newVal[key]) || 0;
                }
                else if (form.casts && form.casts[key] === 'string') {
                    form[key] = String(newVal[key]) || "";
                }
                else {
                    form[key] = newVal[key];
                }
            }
        });
    } else {
        form.reset();
    }
}, { deep: true });

// Observar cambios en los errores de la página
watch(() => errors.value, (newErrors) => {
    errors.value = newErrors
    nextTick(() => {
        activeTab.value = 0
    })
})


const submit = () => {
    processing.value = true;
    errors.value = {};

    const url = form.id
        ? route('configuraciondepago.update', form.id) // Usar rutas con nombre
        : route('configuraciondepago.store'); // Usar rutas con nombre

    form.transform(data => ({
        ...data
    })).submit(form.id ? 'put' : 'post', url, {
        preserveScroll: true,
        onSuccess: () => {
            emit('success');
            closeModal();
        },
        onError: (err) => {
            processing.value = false;
            errors.value = err;
        },
        onFinish: () => {
            processing.value = false;
        }
    });
};

const closeModal = () => {
    form.reset();
    form.clearErrors();
    emit('close');
};

const setActiveTab = (index) => {
    activeTab.value = index;
};
</script>


<template>
    <Modal :show="show" @close="closeModal" max-width="5xl">
        <div class="p-6 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl shadow-2xl border border-blue-100">
            <div class="flex items-center justify-between mb-6 pb-3 border-b border-blue-200">
                <h2 class="text-2xl font-bold text-indigo-800">
                    <i class="fas fa-file-invoice-dollar mr-2"></i>
                    {{ form.id ? 'Editar' : 'Crear' }} Pago {{ form.id }}
                </h2>
                <button
                    @click="closeModal"
                    class="text-gray-500 hover:text-red-500 transition-colors duration-200"
                >
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form @submit.prevent="submit">

                <div class="flex flex-wrap border-b border-indigo-200 mb-6 overflow-x-auto">
                    <button
                        type="button"
                        @click="setActiveTab(0)"
                        :class="[
                            'px-6 py-3 font-medium text-sm rounded-t-xl flex items-center transition-all duration-300',
                            activeTab === 0
                                ? 'bg-white text-indigo-700 border-b-2 border-indigo-500 shadow-sm'
                                : 'text-gray-600 hover:text-indigo-600 hover:bg-indigo-50'
                        ]"
                    >
                        <i class="fas fa-info-circle mr-2"></i>
                        Información General
                    </button>
                    <button
                        type="button"
                        @click="setActiveTab(1)"
                        :class="[
                            'px-6 py-3 font-medium text-sm rounded-t-xl flex items-center transition-all duration-300',
                            activeTab === 1
                                ? 'bg-white text-indigo-700 border-b-2 border-indigo-500 shadow-sm'
                                : 'text-gray-600 hover:text-indigo-600 hover:bg-indigo-50'
                        ]"
                    >
                        <i class="fas fa-percent mr-2"></i>
                        Descuentos
                    </button>
                    <button
                        type="button"
                        @click="setActiveTab(2)"
                        :class="[
                            'px-6 py-3 font-medium text-sm rounded-t-xl flex items-center transition-all duration-300',
                            activeTab === 2
                                ? 'bg-white text-indigo-700 border-b-2 border-indigo-500 shadow-sm'
                                : 'text-gray-600 hover:text-indigo-600 hover:bg-indigo-50'
                        ]"
                    >
                        <i class="fas fa-receipt mr-2"></i>
                        Facturación
                    </button>
                    <button
                        type="button"
                        @click="setActiveTab(3)"
                        :class="[
                            'px-6 py-3 font-medium text-sm rounded-t-xl flex items-center transition-all duration-300',
                            activeTab === 3
                                ? 'bg-white text-indigo-700 border-b-2 border-indigo-500 shadow-sm'
                                : 'text-gray-600 hover:text-indigo-600 hover:bg-indigo-50'
                        ]"
                    >
                        <i class="fas fa-cog mr-2"></i>
                        Opciones
                    </button>

<!--                    <button-->
<!--                        v-for="tab in tabs"-->
<!--                        :key="tab.id"-->
<!--                        @click="activeTab = tab.id"-->
<!--                        class="px-4 py-2 text-sm font-medium"-->
<!--                        :class="{-->
<!--                'text-blue-600 border-b-2 border-blue-600': activeTab === tab.id,-->
<!--                'text-gray-500 hover:text-gray-700': activeTab !== tab.id-->
<!--              }"-->
<!--                    >-->
<!--                        {{ tab.label }}-->
<!--                    </button>-->


                </div>

                <div class="tab-content">
                    <div v-show="activeTab === 0" class="bg-white rounded-xl p-6 border border-indigo-100 shadow-sm">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-1">
                                <InputLabel for="nivel_id" value="Nivel" class="font-medium text-gray-700" />
                                <div class="relative">
                                    <select
                                        id="nivel_id"
                                        v-model="form.nivel_id"
                                        class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 shadow-sm transition-all duration-300"
                                        :class="{'border-red-500': errors.nivel_id}"
                                    >
                                        <option
                                            v-for="option in nivelesOptions"
                                            :key="option.value"
                                            :value="option.value"
                                            :disabled="option.disabled"
                                        >
                                            {{ option.label }}
                                        </option>
                                    </select>
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                        <i class="fas fa-layer-group"></i>
                                    </div>
                                </div>
                                <InputError :message="errors.nivel_id" class="mt-1" />
                            </div>

                            <div class="space-y-1">
                                <InputLabel for="concepto_id" value="Concepto" class="font-medium text-gray-700" />
                                <div class="relative">
                                    <select
                                        id="concepto_id"
                                        v-model="form.concepto_id"
                                        class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 shadow-sm transition-all duration-300"
                                        :class="{'border-red-500': errors.concepto_id}"
                                    >
                                        <option
                                            v-for="option in conceptosOptions"
                                            :key="option.value"
                                            :value="option.value"
                                            :disabled="option.disabled"
                                        >
                                            {{ option.label }}
                                        </option>
                                    </select>
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                        <i class="fas fa-tag"></i>
                                    </div>
                                </div>
                                <InputError :message="errors.concepto_id" class="mt-1" />
                            </div>

                            <div class="space-y-1">
                                <InputLabel for="importe" value="Importe" class="font-medium text-gray-700" />
                                <div class="relative">
                                    <TextInput
                                        id="importe"
                                        v-model="form.importe"
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 shadow-sm transition-all duration-300"
                                        :class="{'border-red-500': errors.importe}"
                                    />
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                        <i class="fas fa-dollar-sign"></i>
                                    </div>
                                </div>
                                <InputError :message="errors.importe" class="mt-1" />
                            </div>

                            <div class="space-y-1">
                                <InputLabel for="dia_limite" value="Día Límite" class="font-medium text-gray-700" />
                                <div class="relative">
                                    <TextInput
                                        id="dia_limite"
                                        v-model="form.dia_limite"
                                        type="number"
                                        min="0"
                                        max="31"
                                        class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 shadow-sm transition-all duration-300"
                                        :class="{'border-red-500': errors.dia_limite}"
                                    />
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                        <i class="fas fa-calendar-times"></i>
                                    </div>
                                </div>
                                <InputError :message="errors.dia_limite" class="mt-1" />
                            </div>

                            <div class="space-y-1">
                                <InputLabel for="dia_de_pago" value="Día de Pago" class="font-medium text-gray-700" />
                                <div class="relative">
                                    <TextInput
                                        id="dia_de_pago"
                                        v-model="form.dia_de_pago"
                                        type="number"
                                        min="0"
                                        max="31"
                                        class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 shadow-sm transition-all duration-300"
                                        :class="{'border-red-500': errors.dia_de_pago}"
                                    />
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                        <i class="fas fa-calendar-check"></i>
                                    </div>
                                </div>
                                <InputError :message="errors.dia_de_pago" class="mt-1" />
                            </div>

                            <div class="space-y-1">
                                <InputLabel for="orden_prioridad" value="Orden Prioridad" class="font-medium text-gray-700" />
                                <div class="relative">
                                    <TextInput
                                        id="orden_prioridad"
                                        v-model="form.orden_prioridad"
                                        type="number"
                                        min="0"
                                        class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 shadow-sm transition-all duration-300"
                                        :class="{'border-red-500': errors.orden_prioridad}"
                                    />
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                        <i class="fas fa-sort-numeric-up"></i>
                                    </div>
                                </div>
                                <InputError :message="errors.orden_prioridad" class="mt-1" />
                            </div>
                        </div>
                    </div>

                    <div v-show="activeTab === 1" class="bg-white rounded-xl p-6 border border-indigo-100 shadow-sm">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                                <ToggleSwitch
                                    v-model="form.tiene_descuento_beca"
                                    label="Tiene descuento por beca"
                                    active-color="bg-indigo-600"
                                    :show-labels="true"
                                    class="w-full"
                                />
                                <p class="text-xs text-gray-600 mt-2 ml-10">
                                    Habilitar si este pago aplica para descuentos por beca académica
                                </p>
                                <InputError :message="errors.tiene_descuento_beca" class="mt-1" />
                            </div>

                            <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                                <ToggleSwitch
                                    v-model="form.tiene_descuento"
                                    label="Tiene descuento"
                                    active-color="bg-indigo-600"
                                    :show-labels="true"
                                    class="w-full"
                                />
                                <p class="text-xs text-gray-600 mt-2 ml-10">
                                    Activar si este pago tiene algún tipo de descuento regular
                                </p>
                                <InputError :message="errors.tiene_descuento" class="mt-1" />
                            </div>

                            <div class="bg-blue-50 p-4 rounded-lg border border-blue-100 md:col-span-2">
                                <ToggleSwitch
                                    v-model="form.tiene_descuento_por_promocion"
                                    label="Tiene descuento por promoción"
                                    active-color="bg-indigo-600"
                                    :show-labels="true"
                                    class="w-full"
                                />
                                <p class="text-xs text-gray-600 mt-2 ml-10">
                                    Habilitar si este pago tiene descuentos especiales por promociones temporales
                                </p>
                                <InputError :message="errors.tiene_descuento_por_promocion" class="mt-1" />
                            </div>
                        </div>
                    </div>

                    <div v-show="activeTab === 2" class="bg-white rounded-xl p-6 border border-indigo-100 shadow-sm">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-1">
                                <InputLabel for="emisorfiscal_id" value="Emisor Fiscal" class="font-medium text-gray-700" />
                                <div class="relative">
                                    <select
                                        id="emisorfiscal_id"
                                        v-model="form.emisorfiscal_id"
                                        class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 shadow-sm transition-all duration-300"
                                        :class="{'border-red-500': errors.emisorfiscal_id}"
                                    >
                                        <option
                                            v-for="option in emisoresOptions"
                                            :key="option.value"
                                            :value="option.value"
                                            :disabled="option.disabled"
                                        >
                                            {{ option.label }}
                                        </option>
                                    </select>
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                        <i class="fas fa-building"></i>
                                    </div>
                                </div>
                                <InputError :message="errors.emisorfiscal_id" class="mt-1" />
                            </div>

                            <div class="space-y-1">
                                <InputLabel for="prodservsat_id" value="Producto/Servicio SAT" class="font-medium text-gray-700" />
                                <div class="relative">
                                    <select
                                        id="prodservsat_id"
                                        v-model="form.prodservsat_id"
                                        class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 shadow-sm transition-all duration-300"
                                        :class="{'border-red-500': errors.prodservsat_id}"
                                    >
                                        <option
                                            v-for="option in prodservsatsOptions"
                                            :key="option.value"
                                            :value="option.value"
                                            :disabled="option.disabled"
                                        >
                                            {{ option.label }}
                                        </option>
                                    </select>
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                        <i class="fas fa-box"></i>
                                    </div>
                                </div>
                                <InputError :message="errors.prodservsat_id" class="mt-1" />
                            </div>

                            <div class="space-y-1">
                                <InputLabel for="unidadmedidasat_id" value="Unidad de Medida SAT" class="font-medium text-gray-700" />
                                <div class="relative">
                                    <select
                                        id="unidadmedidasat_id"
                                        v-model="form.unidadmedidasat_id"
                                        class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 shadow-sm transition-all duration-300"
                                        :class="{'border-red-500': errors.unidadmedidasat_id}"
                                    >
                                        <option
                                            v-for="option in unidadmedidasatsOptions"
                                            :key="option.value"
                                            :value="option.value"
                                            :disabled="option.disabled"
                                        >
                                            {{ option.label }}
                                        </option>
                                    </select>
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                        <i class="fas fa-ruler"></i>
                                    </div>
                                </div>
                                <InputError :message="errors.unidadmedidasat_id" class="mt-1" />
                            </div>

                            <div class="bg-blue-50 p-4 rounded-lg border border-blue-100 flex items-center">
                                <div class="flex-1">
                                    <ToggleSwitch
                                        v-model="form.es_facturable"
                                        label="Es facturable"
                                        active-color="bg-indigo-600"
                                        :show-labels="true"
                                        class="w-full"
                                    />
                                    <p class="text-xs text-gray-600 mt-2 ml-10">
                                        Activar si este pago debe generar factura
                                    </p>
                                </div>
                                <InputError :message="errors.es_facturable" class="mt-1" />
                            </div>
                        </div>
                    </div>

                    <div v-show="activeTab === 3" class="bg-white rounded-xl p-6 border border-indigo-100 shadow-sm">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                                <ToggleSwitch
                                    v-model="form.acepta_pagos_diversos"
                                    label="Acepta pagos diversos"
                                    active-color="bg-indigo-600"
                                    :show-labels="true"
                                    class="w-full"
                                />
                                <p class="text-xs text-gray-600 mt-2 ml-10">
                                    Habilitar si este pago puede recibir múltiples formas de pago
                                </p>
                                <InputError :message="errors.acepta_pagos_diversos" class="mt-1" />
                            </div>

                            <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                                <ToggleSwitch
                                    v-model="form.aplica_al_siguiente_nivel"
                                    label="Aplica al siguiente nivel"
                                    active-color="bg-indigo-600"
                                    :show-labels="true"
                                    class="w-full"
                                />
                                <p class="text-xs text-gray-600 mt-2 ml-10">
                                    Activar si este pago se extiende al siguiente nivel académico
                                </p>
                                <InputError :message="errors.aplica_al_siguiente_nivel" class="mt-1" />
                            </div>

                            <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                                <ToggleSwitch
                                    v-model="form.aplica_a"
                                    label="Aplica a"
                                    active-color="bg-indigo-600"
                                    :show-labels="true"
                                    class="w-full"
                                />
                                <p class="text-xs text-gray-600 mt-2 ml-10">
                                    Especificar si este pago aplica a grupos específicos
                                </p>
                                <InputError :message="errors.aplica_a" class="mt-1" />
                            </div>

                            <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                                <ToggleSwitch
                                    v-model="form.se_puede_ver_en_pantalla"
                                    label="Visible en pantalla"
                                    active-color="bg-indigo-600"
                                    :show-labels="true"
                                    class="w-full"
                                />
                                <p class="text-xs text-gray-600 mt-2 ml-10">
                                    Controlar la visibilidad de este pago en interfaces públicas
                                </p>
                                <InputError :message="errors.se_puede_ver_en_pantalla" class="mt-1" />
                            </div>

                            <div class="space-y-1 md:col-span-2">
                                <InputLabel for="tagpagos" value="Tags de Pagos" class="font-medium text-gray-700" />
                                <div class="relative">
                                    <TextInput
                                        id="tagpagos"
                                        v-model="form.tagpagos"
                                        type="text"
                                        class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 shadow-sm transition-all duration-300"
                                        :class="{'border-red-500': errors.tagpagos}"
                                        placeholder="Etiquetas para clasificar pagos (separadas por comas)"
                                    />
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                        <i class="fas fa-tags"></i>
                                    </div>
                                </div>
                                <InputError :message="errors.tagpagos" class="mt-1" />
                            </div>

                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-4 mt-8 pt-4 border-t border-blue-200">
                    <SecondaryButton @click="closeModal" type="button">
                        Cancelar
                    </SecondaryButton>
                    <PrimaryButton type="submit" :disabled="form.processing">
                        <span v-if="form.processing">
                            <i class="fas fa-spinner fa-spin mr-2"></i> Procesando...
                        </span>
                        <span v-else>
                            {{ form.id ? 'Actualizar' : 'Guardar' }}
                        </span>
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </Modal>
</template>


<style scoped>
select, input {
    transition: all 0.3s ease;
}

select:focus, input:focus {
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
}

.tab-content {
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.modal-content {
    max-height: 90vh;
    overflow-y: auto;
}
</style>
