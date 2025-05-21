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
    ciclo: {
        type: Object,
        default: () => ({})
    },
});

const emit = defineEmits(['close', 'success']);
const processing = ref(false)
const errors = ref({})

const form = useForm({
    id: 0,
    ciclo: '',
    fecha_inicio: Date(),
    fecha_fin: Date(),
    predeterminado: false,
    ciclo_anterior_id: 0,
    ano_anterior: 0,
    ano_siguiente: 0
});

watch(() => props.ciclo, (nuevo) => {
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
            ciclo: '',
            fecha_inicio: Date(),
            fecha_fin: Date(),
            predeterminado: false,
            ciclo_anterior_id: 0,
            ano_anterior: 0,
            ano_siguiente: 0
        });


    }
}, { immediate: true, deep: true });


const submit = () => {

    processing.value = true
    errors.value = {} // Limpiar errores anteriores

    const url = form.id
        ? `/ciclo.update`
        : '/ciclo.store';

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
        ciclo: '',
        fecha_inicio: Date(),
        fecha_fin: Date(),
        predeterminado: false,
        ciclo_anterior_id: 0,
        ano_anterior: 0,
        ano_siguiente: 0
    });
};


</script>


<template>
    <Modal :show="show" @close="closeModal" max-width="2xl">
        <div class="p-8 bg-white rounded-xl shadow-lg border border-gray-100">
            <!-- Encabezado -->
            <div class="mb-6 flex items-center justify-between border-b border-gray-200 pb-4">
                <h2 class="text-2xl font-bold text-gray-800">
                    📅 {{ form.id ? 'Editar' : 'Nuevo' }} Ciclo
                </h2>
                <button @click="closeModal" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <!-- Campos del formulario -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Columna Izquierda -->
                    <div class="space-y-4">
                        <!-- Nombre del ciclo -->
                        <div>
                            <InputLabel value="🏷️ Nombre del ciclo" class="mb-2 font-medium"/>
                            <TextInput
                                v-model="form.ciclo"
                                type="text"
                                class="w-full p-3 border-2 border-gray-200 rounded-xl focus:border-indigo-500"
                                :class="{ 'border-red-500': form.errors.ciclo }"
                            />
                            <InputError :message="form.errors.ciclo" class="mt-1"/>
                        </div>

                        <!-- Fechas -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <InputLabel value="📅 Fecha inicio" class="mb-2 font-medium"/>
                                <TextInput
                                    v-model="form.fecha_inicio"
                                    type="date"
                                    class="w-full p-3 border-2 border-gray-200 rounded-xl"
                                />
                                <InputError :message="form.errors.fecha_inicio" class="mt-1"/>
                            </div>
                            <div>
                                <InputLabel value="📅 Fecha fin" class="mb-2 font-medium"/>
                                <TextInput
                                    v-model="form.fecha_fin"
                                    type="date"
                                    class="w-full p-3 border-2 border-gray-200 rounded-xl"
                                />
                                <InputError :message="form.errors.fecha_fin" class="mt-1"/>
                            </div>
                        </div>
                    </div>

                    <!-- Columna Derecha -->
                    <div class="space-y-4">
                        <!-- Predeterminado -->
                        <div>
                            <InputLabel value="⭐ Predeterminado" class="mb-2 font-medium"/>
                            <div class="relative flex items-center gap-3">
                                <button
                                    type="button"
                                    @click="form.predeterminado = !form.predeterminado"
                                    class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors duration-200"
                                    :class="form.predeterminado ? 'bg-green-500' : 'bg-gray-300'"
                                >
                                    <span
                                        class="h-4 w-4 transform bg-white rounded-full transition-transform duration-200"
                                        :class="form.predeterminado ? 'translate-x-6' : 'translate-x-1'"
                                    ></span>
                                </button>
                                <span class="text-sm font-medium">
                                    {{ form.predeterminado ? 'Activado' : 'Desactivado' }}
                                </span>
                            </div>
                        </div>

                        <!-- Campos numéricos -->
                        <div class="space-y-4">
                            <div>
                                <InputLabel value="🔗 Ciclo anterior ID" class="mb-2 font-medium"/>
                                <TextInput
                                    v-model="form.ciclo_anterior_id"
                                    type="number"
                                    class="w-full p-3 border-2 border-gray-200 rounded-xl"
                                />
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <InputLabel value="◀️ Año anterior" class="mb-2 font-medium"/>
                                    <TextInput
                                        v-model="form.ano_anterior"
                                        type="number"
                                        class="w-full p-3 border-2 border-gray-200 rounded-xl"
                                    />
                                </div>
                                <div>
                                    <InputLabel value="▶️ Año siguiente" class="mb-2 font-medium"/>
                                    <TextInput
                                        v-model="form.ano_siguiente"
                                        type="number"
                                        class="w-full p-3 border-2 border-gray-200 rounded-xl"
                                    />
                                </div>
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
