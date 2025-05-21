<script setup>
import {ref, watch} from 'vue';
import { useForm } from '@inertiajs/vue3';
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";

const props = defineProps({
    show: Boolean,
    nivel: {
        type: Object,
        default: () => ({})
    },
});

const emit = defineEmits(['close', 'success']);
const processing = ref(false)
const errors = ref({})

const form = useForm({
    id: 0,
    nivel: '',
    clave_nivel: '',
    clave_registro_nivel: '',
    nivel_oficial: '',
    nivel_fiscal: '',
    prefijo_evaluacion: '',
    numero_evaluaciones: '',
    fecha_actas: '',

});

watch(() => props.nivel, (nuevo) => {
    if (nuevo && Object.keys(nuevo).length > 0) {
        form.defaults({ ...nuevo });
        form.reset();
    } else {
        form.reset();
        form.clearErrors();
        form.defaults({
            id: 0,
            nivel: '',
            clave_nivel: '',
            clave_registro_nivel: '',
            nivel_oficial: '',
            nivel_fiscal: '',
            prefijo_evaluacion: '',
            numero_evaluaciones: '',
            fecha_actas: '',
        });


    }
}, { immediate: true, deep: true });


const submit = () => {

    processing.value = true
    errors.value = {} // Limpiar errores anteriores

    const url = form.id
        ? `/nivel.update`
        : '/nivel.store';

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
        nivel: '',
        clave_nivel: '',
        clave_registro_nivel: '',
        nivel_oficial: '',
        nivel_fiscal: '',
        prefijo_evaluacion: '',
        numero_evaluaciones: '',
        fecha_actas: '',
    });
};


</script>


<template>
    <Modal :show="show" @close="closeModal" max-width="2xl">
        <div class="p-8 bg-white rounded-xl shadow-lg border border-gray-100">
            <!-- Encabezado -->
            <div class="mb-6 flex items-center justify-between border-b border-gray-200 pb-4">
                <h2 class="text-2xl font-bold text-gray-800">
                    📊 {{ form.id ? 'Editar' : 'Nuevo' }} Nivel
                </h2>
                <button @click="closeModal" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <!-- Campos principales -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Columna Izquierda -->
                    <div class="space-y-4">
                        <!-- Nombre del nivel -->
                        <div>
                            <InputLabel value="🏷️ Nombre del nivel" class="mb-2 font-medium"/>
                            <TextInput
                                v-model="form.nivel"
                                type="text"
                                class="w-full p-3 border-2 border-gray-200 rounded-xl focus:border-indigo-500"
                                :class="{ 'border-red-500': form.errors.nivel }"
                            />
                            <InputError :message="form.errors.nivel" class="mt-1"/>
                        </div>

                        <!-- Claves -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <InputLabel value="🔑 Clave nivel" class="mb-2 font-medium"/>
                                <TextInput
                                    v-model="form.clave_nivel"
                                    type="text"
                                    class="w-full p-3 border-2 border-gray-200 rounded-xl"
                                />
                                <InputError :message="form.errors.clave_nivel" class="mt-1"/>
                            </div>
                            <div>
                                <InputLabel value="🗝️ Clave registro" class="mb-2 font-medium"/>
                                <TextInput
                                    v-model="form.clave_registro_nivel"
                                    type="text"
                                    class="w-full p-3 border-2 border-gray-200 rounded-xl"
                                />
                                <InputError :message="form.errors.clave_registro_nivel" class="mt-1"/>
                            </div>
                        </div>
                    </div>

                    <!-- Columna Derecha -->
                    <div class="space-y-4">
                        <!-- Niveles oficial y fiscal -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <InputLabel value="🏛️ Nivel oficial" class="mb-2 font-medium"/>
                                <TextInput
                                    v-model="form.nivel_oficial"
                                    type="text"
                                    class="w-full p-3 border-2 border-gray-200 rounded-xl"
                                />
                            </div>
                            <div>
                                <InputLabel value="💰 Nivel fiscal" class="mb-2 font-medium"/>
                                <TextInput
                                    v-model="form.nivel_fiscal"
                                    type="text"
                                    class="w-full p-3 border-2 border-gray-200 rounded-xl"
                                />
                            </div>
                        </div>

                        <!-- Configuración evaluaciones -->
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <InputLabel value="🏷️ Prefijo evaluación" class="mb-2 font-medium"/>
                                    <TextInput
                                        v-model="form.prefijo_evaluacion"
                                        type="text"
                                        class="w-full p-3 border-2 border-gray-200 rounded-xl"
                                    />
                                </div>
                                <div>
                                    <InputLabel value="🔢 Número evaluaciones" class="mb-2 font-medium"/>
                                    <TextInput
                                        v-model="form.numero_evaluaciones"
                                        type="number"
                                        class="w-full p-3 border-2 border-gray-200 rounded-xl"
                                    />
                                </div>
                            </div>

                            <div>
                                <InputLabel value="📅 Fecha actas" class="mb-2 font-medium"/>
                                <TextInput
                                    v-model="form.fecha_actas"
                                    type="date"
                                    class="w-full p-3 border-2 border-gray-200 rounded-xl"
                                />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botones -->
                <div class="flex justify-end gap-4 border-t border-gray-200 pt-6">
                    <SecondaryButton
                        @click="closeModal"
                        type="button"
                        class="px-6 py-3 rounded-xl bg-gray-100 hover:bg-gray-200 transition-colors"
                    >
                        ❌ Cancelar
                    </SecondaryButton>
                    <PrimaryButton
                        type="submit"
                        :disabled="form.processing"
                        class="px-6 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold transition-colors"
                    >
                        <template v-if="form.processing">
                            <i class="fas fa-spinner fa-spin mr-2"></i> Guardando...
                        </template>
                        <template v-else>
                            💾 {{ form.id ? 'Actualizar' : 'Guardar' }}
                        </template>
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </Modal>
</template>
