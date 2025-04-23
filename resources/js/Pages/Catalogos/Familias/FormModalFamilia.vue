<script setup>
import {ref, watch} from 'vue';
import { useForm } from '@inertiajs/vue3';
import Modal from '@/Components/Modal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const props = defineProps({
    show: Boolean,
    familia: {
        type: Object,
        default: () => ({})
    },
});

const emit = defineEmits(['close', 'success']);
const processing = ref(false)
const errors = ref({})

const form = useForm({
    id: 0,
    familia: '',
    observaciones_control_escolar: '',
    observaciones_pagos: '',
    convenios: '',
    email_pagos: '',
    email_facturas: '',
    email_control_escolar: '',
    email_convenios: '',

});

watch(() => props.familia, (nuevo) => {
    if (nuevo && Object.keys(nuevo).length > 0) {
        // Si viene para editar, llenamos todo explícitamente
        form.defaults({ ...nuevo });
        form.reset(); // esto ahora sí tendrá sentido
    } else {
        // Nuevo registro: limpiar manualmente el formulario

        form.reset();
        form.clearErrors();
        form.defaults({
            id: 0,
            familia: '',
            observaciones_control_escolar: '',
            observaciones_pagos: '',
            convenios: '',
            email_pagos: '',
            email_facturas: '',
            email_control_escolar: '',
            email_convenios: '',
        });


    }
}, { immediate: true, deep: true });


const submit = () => {

    processing.value = true
    errors.value = {} // Limpiar errores anteriores

    const url = form.id
        ? `/familia.update`
        : '/familia.store';

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

const closeModal = () => {
    form.reset();
    form.clearErrors();
    limpiarFormulario();
    emit('close');
};


const limpiarFormulario = () => {
    form.reset();
    form.clearErrors();
    form.defaults({
        id: 0,
        familia: '',
        observaciones_control_escolar: '',
        observaciones_pagos: '',
        convenios: '',
        email_pagos: '',
        email_facturas: '',
        email_control_escolar: '',
        email_convenios: '',
    });
};


</script>


<template>
    <Modal :show="show" @close="closeModal" max-width="2xl">
        <div class="p-6 bg-white rounded-lg">
            <h2 class="text-2xl font-bold mb-6 text-gray-800">
                {{ form.id ? 'Editar' : 'Nueva' }} Familia
            </h2>

            <form @submit.prevent="submit">
                <div class="grid grid-cols-1 md:grid-cols-1 gap-6 mb-6">
                    <div>
                        <InputLabel for="familia" value="Familia *" class="mb-2 font-medium text-gray-700"/>
                        <TextInput
                            id="familia"
                            v-model="form.familia"
                            type="text"
                            class="w-full"
                            :error="form.errors.familia"
                            maxlength="250"
                            required
                        />
                        <InputError :message="form.errors.familia" class="mt-2"/>
                    </div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-2 gap-6 mb-6">

                    <div>
                        <InputLabel for="observaciones_control_escolar" value="Obs. CE" class="mb-2 font-medium text-gray-700"/>
                        <TextInput
                            id="observaciones_control_escolar"
                            v-model="form.observaciones_control_escolar"
                            type="text"
                            class="w-full"
                            :error="form.errors.observaciones_control_escolar"
                            maxlength="250"
                        />
                        <InputError :message="form.errors.observaciones_control_escolar" class="mt-2"/>
                    </div>

                    <div>
                        <InputLabel for="observaciones_pagos" value="Obs. Pagos" class="mb-2 font-medium text-gray-700"/>
                        <TextInput
                            id="observaciones_pagos"
                            v-model="form.observaciones_pagos"
                            type="text"
                            class="w-full"
                            :error="form.errors.observaciones_pagos"
                            maxlength="250"
                        />
                        <InputError :message="form.errors.observaciones_pagos" class="mt-2"/>
                    </div>


                </div>

                <div class="grid grid-cols-2 md:grid-cols-2 gap-6 mb-6">

                    <div>
                        <InputLabel for="convenios" value="Convenios" class="mb-2 font-medium text-gray-700"/>
                        <TextInput
                            id="convenios"
                            v-model="form.convenios"
                            type="text"
                            class="w-full"
                            :error="form.errors.convenios"
                            maxlength="250"
                        />
                        <InputError :message="form.errors.convenios" class="mt-2"/>
                    </div>

                    <div>
                        <InputLabel for="email_pagos" value="Emails Pagos" class="mb-2 font-medium text-gray-700"/>
                        <TextInput
                            id="email_pagos"
                            v-model="form.email_pagos"
                            type="text"
                            class="w-full"
                            :error="form.errors.email_pagos"
                            maxlength="250"
                        />
                        <InputError :message="form.errors.email_pagos" class="mt-2"/>
                    </div>


                </div>

                <div class="grid grid-cols-3 md:grid-cols-3 gap-6 mb-6">

                    <div>
                        <InputLabel for="email_facturas" value="Emails Facturas" class="mb-2 font-medium text-gray-700"/>
                        <TextInput
                            id="email_facturas"
                            v-model="form.email_facturas"
                            type="text"
                            class="w-full"
                            :error="form.errors.email_facturas"
                            maxlength="250"
                        />
                        <InputError :message="form.errors.email_facturas" class="mt-2"/>
                    </div>

                    <div>
                        <InputLabel for="email_control_escolar" value="Emails CE" class="mb-2 font-medium text-gray-700"/>
                        <TextInput
                            id="email_control_escolar"
                            v-model="form.email_control_escolar"
                            type="text"
                            class="w-full"
                            :error="form.errors.email_control_escolar"
                            maxlength="250"
                        />
                        <InputError :message="form.errors.email_control_escolar" class="mt-2"/>
                    </div>

                    <div>
                        <InputLabel for="email_convenios" value="Emails Convenios" class="mb-2 font-medium text-gray-700"/>
                        <TextInput
                            id="email_convenios"
                            v-model="form.email_convenios"
                            type="text"
                            class="w-full"
                            :error="form.errors.email_convenios"
                            maxlength="250"
                        />
                        <InputError :message="form.errors.email_convenios" class="mt-2"/>
                    </div>


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
