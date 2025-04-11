<script setup>
import {ref, watch} from 'vue';
import { useForm } from '@inertiajs/vue3';
import Modal from '@/Components/Modal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import Checkbox from '@/Components/Checkbox.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Login from "@/Pages/Auth/Login.vue";

const props = defineProps({
    show: Boolean,
    registro: {
        type: Object,
        default: () => ({})
    },
    regimenes_fiscales: {
        type: Object,
        default: () => ({})
    },
});

const { regimenes_fiscales } = props;

const emit = defineEmits(['close', 'success']);
const processing = ref(false)
const errors = ref({})

const form = useForm({
    id: 0,
    rfc: '',
    razon_social: '',
    razon_social_cfdi_40: '',
    calle: '',
    num_ext: '',
    num_int: '',
    colonia: '',
    localidad: '',
    estado: '',
    pais: 'México',
    codigo_postal: '',
    regimen_fiscal_id: 3,
    es_extranjero: false
});

watch(() => props.registro, (newVal) => {
    if (newVal.id) {
        form.defaults({
            ...newVal
        });
        form.reset();
    } else {
        form.reset();
        form.pais = 'México';
    }
}, { immediate: true, deep: true });
// regimen_fiscal_1: newVal.regimen_fiscal_1?.join(', ') || '',
// regimen_fiscal_2: newVal.regimen_fiscal_2?.join(', ') || ''

const submit = () => {

    processing.value = true
    errors.value = {} // Limpiar errores anteriores

    const url = form.id
        ? `/registros_fiscales.update/${form.id}`
        : '/registros_fiscales.store';

    form.transform(data => ({
        ...data
    })).submit(form.id ? 'put' : 'post', url, {
        preserveScroll: true,
        onSuccess: () => {
            emit('success');
            closeModal();
        },
        onError: (err) => {
            processing.value = false
            errors.value = err
        },
        onFinish: () => {
            processing.value = false
        }


    });
};

// regimen_fiscal_1: data.regimen_fiscal_1.split(',').map(e => e.trim()).filter(e => e),
// regimen_fiscal_2: data.regimen_fiscal_2.split(',').map(e => e.trim()).filter(e => e)

const closeModal = () => {
    form.reset();
    form.clearErrors();
    emit('close');
};
</script>


<template>
    <Modal :show="show" @close="closeModal" max-width="2xl">
        <div class="p-6 bg-white rounded-lg">
            <h2 class="text-2xl font-bold mb-6 text-gray-800">
                {{ form.id ? 'Editar' : 'Nuevo' }} Registro Fiscal
            </h2>

            <form @submit.prevent="submit">
                <!-- Sección RFC y Razón Social -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <InputLabel for="rfc" value="RFC *" class="mb-2 font-medium text-gray-700"/>
                        <TextInput
                            id="rfc"
                            v-model="form.rfc"
                            type="text"
                            class="w-full"
                            :error="form.errors.rfc"
                            maxlength="20"
                            required
                        />
                        <InputError :message="form.errors.rfc" class="mt-2"/>
                    </div>

                    <div>
                        <InputLabel for="razon_social" value="Razón Social" class="mb-2 font-medium text-gray-700"/>
                        <TextInput
                            id="razon_social"
                            v-model="form.razon_social"
                            type="text"
                            class="w-full"
                            :error="form.errors.razon_social"
                            maxlength="250"
                        />
                        <InputError :message="form.errors.razon_social" class="mt-2"/>
                    </div>

                    <div>
                        <InputLabel for="razon_social" value="Razón Social CDFi 4.0" class="mb-2 font-medium text-gray-700"/>
                        <TextInput
                            id="razon_social"
                            v-model="form.razon_social_cfdi_40"
                            type="text"
                            class="w-full"
                            :error="form.errors.razon_social_cfdi_40"
                            maxlength="250"
                        />
                        <InputError :message="form.errors.razon_social_cfdi_40" class="mt-2"/>
                    </div>

                </div>

                <!-- Sección Dirección -->
                <div class="space-y-4 mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Domicilio Fiscal</h3>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <InputLabel for="calle" value="Calle" class="mb-2 font-medium text-gray-700"/>
                            <TextInput
                                id="calle"
                                v-model="form.calle"
                                type="text"
                                class="w-full"
                                :error="form.errors.calle"
                                maxlength="250"
                            />
                            <InputError :message="form.errors.calle" class="mt-2"/>
                        </div>

                        <div>
                            <InputLabel for="num_ext" value="Número Exterior" class="mb-2 font-medium text-gray-700"/>
                            <TextInput
                                id="num_ext"
                                v-model="form.num_ext"
                                type="text"
                                class="w-full"
                                :error="form.errors.num_ext"
                                maxlength="25"
                            />
                            <InputError :message="form.errors.num_ext" class="mt-2"/>
                        </div>

                        <div>
                            <InputLabel for="num_int" value="Número Interior" class="mb-2 font-medium text-gray-700"/>
                            <TextInput
                                id="num_int"
                                v-model="form.num_int"
                                type="text"
                                class="w-full"
                                :error="form.errors.num_int"
                                maxlength="25"
                            />
                            <InputError :message="form.errors.num_int" class="mt-2"/>
                        </div>
                        <div>
                            <InputLabel for="colonia" value="Colonia" class="mb-2 font-medium text-gray-700"/>
                            <TextInput
                                id="colonia"
                                v-model="form.colonia"
                                type="text"
                                class="w-full"
                                :error="form.errors.colonia"
                                maxlength="250"
                            />
                            <InputError :message="form.errors.colonia" class="mt-2"/>
                        </div>

                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                        <div>
                            <InputLabel for="localidad" value="Localidad" class="mb-2 font-medium text-gray-700"/>
                            <TextInput
                                id="localidad"
                                v-model="form.localidad"
                                type="text"
                                class="w-full"
                                :error="form.errors.localidad"
                                maxlength="250"
                            />
                            <InputError :message="form.errors.localidad" class="mt-2"/>
                        </div>

                        <div>
                            <InputLabel for="codigo_postal" value="Código Postal" class="mb-2 font-medium text-gray-700"/>
                            <TextInput
                                id="codigo_postal"
                                v-model="form.codigo_postal"
                                type="text"
                                class="w-full"
                                :error="form.errors.codigo_postal"
                                maxlength="10"
                            />
                            <InputError :message="form.errors.codigo_postal" class="mt-2"/>
                        </div>
                        <div>
                            <InputLabel for="estado" value="Estado" class="mb-2 font-medium text-gray-700"/>
                            <TextInput
                                id="estado"
                                v-model="form.estado"
                                type="text"
                                class="w-full"
                                :error="form.errors.estado"
                                maxlength="30"
                            />
                            <InputError :message="form.errors.estado" class="mt-2"/>
                        </div>

                        <div>
                            <InputLabel for="pais" value="País" class="mb-2 font-medium text-gray-700"/>
                            <TextInput
                                id="pais"
                                v-model="form.pais"
                                type="text"
                                class="w-full"
                                :error="form.errors.pais"
                                maxlength="30"
                            />
                            <InputError :message="form.errors.pais" class="mt-2"/>
                        </div>

                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-1 gap-4">
                        <div>
                            <InputLabel for="regimen_fiscal_id" value="Regimen Fiscal" class="mb-2 font-medium text-gray-700"/>
                            <select
                                v-model="form.regimen_fiscal_id"
                                :error="form.errors.regimen_fiscal_id"
                                class="w-full"
                            >
                                <option v-for="(regimen, id) in regimenes_fiscales" :key="id" :value="id">
                                    {{ regimen }}
                                </option>
                            </select>
                            <InputError :message="form.errors.regimen_fiscal_id" class="mt-2"/>
                        </div>
                    </div>



                </div>

                <!-- Checkbox Extranjero -->
                <div class="mb-6">
                    <Checkbox
                        v-model:checked="form.es_extranjero"
                        label="Es extranjero"
                        :error="form.errors.es_extranjero"
                        id="es_extranjero"
                    />
                    <label for="es_extranjero" class="ml-2 text-sm text-gray-600">Es extranjero</label>
                    <InputError :message="form.errors.es_extranjero" class="mt-2"/>
                </div>

                <!-- Botones -->
                <div class="flex justify-end space-x-4 mt-8">
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
