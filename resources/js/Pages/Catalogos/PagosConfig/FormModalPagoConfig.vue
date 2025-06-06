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
    conceptodepago: {
        type: Object,
        default: () => ({})
    },
});

const { conceptodepago } = props;

const emit = defineEmits(['close', 'success']);
const processing = ref(false)
const errors = ref({})

const form = useForm({
    id: 0,
    concepto: '',
    concepto_unico: '',
    tag: '',
    status_concepto: true,
});

watch(() => props.conceptodepago, (newVal) => {
    if (newVal.id) {
        form.defaults({
            ...newVal
        });
        form.reset();
    } else {
        form.reset();
    }
}, { immediate: true, deep: true });

const submit = () => {

    processing.value = true
    errors.value = {} // Limpiar errores anteriores

    const url = form.id
        ? `/conceptodepago.update/${form.id}`
        : '/conceptodepago.store';

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
    emit('close');
};
</script>


<template>
    <Modal :show="show" @close="closeModal" max-width="2xl">
        <div class="p-6 bg-white rounded-lg">
            <h2 class="text-2xl font-bold mb-6 text-gray-800">
                {{ form.id ? 'Editar' : 'Nuevo' }} Uso CFDI
            </h2>

            <form @submit.prevent="submit">
                <!-- Sección RFC y Razón Social -->
                <div class="grid grid-cols-1 md:grid-cols-1 gap-6 mb-6">
                    <div>
                        <InputLabel for="concepto" value="Concepto *" class="mb-2 font-medium text-gray-700"/>
                        <TextInput
                            id="concepto"
                            v-model="form.concepto"
                            type="text"
                            class="w-full"
                            :error="form.errors.concepto"
                            maxlength="20"
                            required
                        />
                        <InputError :message="form.errors.concepto" class="mt-2"/>
                    </div>

                    <div>
                        <InputLabel for="concepto_unico" value="Concepto Único" class="mb-2 font-medium text-gray-700"/>
                        <TextInput
                            id="concepto_unico"
                            v-model="form.concepto_unico"
                            type="text"
                            class="w-full"
                            :error="form.errors.concepto_unico"
                            maxlength="250"
                        />
                        <InputError :message="form.errors.concepto_unico" class="mt-2"/>
                    </div>

                    <div>
                        <InputLabel for="tag" value="Tag" class="mb-2 font-medium text-gray-700"/>
                        <TextInput
                            id="tag"
                            v-model="form.tag"
                            type="text"
                            class="w-full"
                            :error="form.errors.tag"
                            maxlength="250"
                        />
                        <InputError :message="form.errors.tag" class="mt-2"/>
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
