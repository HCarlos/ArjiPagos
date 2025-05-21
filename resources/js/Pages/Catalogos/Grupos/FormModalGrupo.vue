<script setup>
import {ref, watch} from 'vue';
import { useForm } from '@inertiajs/vue3';
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import ToggleSwitch from "@/Components/ToggleSwitch.vue";

const props = defineProps({
    show: Boolean,
    grupo: {
        type: Object,
        default: () => ({})
    },
    niveles: {
        type: Object,
        default: () => ({})
    },
    ciclos: {
        type: Object,
        default: () => ({})
    },
});

const { niveles } = props;
const { ciclos } = props;

const emit = defineEmits(['close', 'success']);
const processing = ref(false)
const errors = ref({})

const form = useForm({
    id: 0,
    grupo_id:0,
    clave_grupo:'',
    grupo: '',
    grupo_oficial:'',
    grupo_periodo:'',
    grupo_periodo_ciclo:'',
    grado:'',
    grado_pai:'',
    num:'',
    is_visible:true,
    is_bloqueado:false,
    is_activo_en_caja:true,
    is_ver_boleta_interna:false,
    is_ver_boleta_oficial:false,
    is_grupo_pai:false,
    status_grupo:true,
    grupo_siguiente_id:0,
});

watch(() => props.grupo, (nuevo) => {
    if (nuevo && Object.keys(nuevo).length > 0) {
        form.defaults({ ...nuevo });
        form.reset();
    } else {
        form.reset();
        form.clearErrors();
        form.defaults({
            id: 0,
            grupo_id:0,
            clave_grupo:'',
            grupo: '',
            grupo_oficial:'',
            grupo_periodo:'',
            grupo_periodo_ciclo:'',
            grado:'',
            grado_pai:'',
            num:'',
            is_visible:true,
            is_bloqueado:false,
            is_activo_en_caja:true,
            is_ver_boleta_interna:false,
            is_ver_boleta_oficial:false,
            is_grupo_pai:false,
            status_grupo:true,
            grupo_siguiente_id:0,
        });


    }
}, { immediate: true, deep: true });


const submit = () => {

    processing.value = true
    errors.value = {} // Limpiar errores anteriores

    const url = form.id
        ? '/grupo.update'
        : '/grupo.store';

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
        grupo_id:0,
        clave_grupo:'',
        grupo: '',
        grupo_oficial:'',
        grupo_periodo:'',
        grupo_periodo_ciclo:'',
        grado:'',
        grado_pai:'',
        num:'',
        is_visible:true,
        is_bloqueado:false,
        is_activo_en_caja:true,
        is_ver_boleta_interna:false,
        is_ver_boleta_oficial:false,
        is_grupo_pai:false,
        status_grupo:true,
        grupo_siguiente_id:0,
    });
};

const tabs = [
    { name: 'basica', label: 'Información Básica', icon: 'fas fa-info-circle' },
    { name: 'avanzada', label: 'Configuraciones', icon: 'fas fa-cogs' }
];

const activeTab = ref('basica');

</script>

<style>

.tab-content {
    min-height: 300px; /* Ajusta según necesidad */
}

</style>


<template>
    <Modal :show="show" @close="closeModal" max-width="2xl">
        <div class="p-8 bg-white rounded-xl shadow-lg border border-gray-100">
            <!-- Encabezado -->
            <div class="mb-6 flex items-center justify-between border-b border-gray-200 pb-4">
                <h2 class="text-2xl font-bold text-gray-800">
                    👥 {{ form.id ? 'Editar' : 'Nuevo' }} Grupo
                </h2>
                <button @click="closeModal" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>

            <form @submit.prevent="submit" class="space-y-8">
                <!-- Pestañas -->
                <div class="border-b border-gray-200 bg">
                    <nav class="flex space-x-8" aria-label="Tabs">
                        <button type="button"
                            v-for="tab in tabs"
                            :key="tab.name"
                            @click="activeTab = tab.name"
                            :class="[
                                activeTab === tab.name
                                ? 'border-indigo-500 text-indigo-600'
                                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
                                'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm'
                            ]"
                        >
                            {{ tab.label }}
                            <i :class="tab.icon" class="ml-2"></i>
                        </button>
                    </nav>
                </div>

                <!-- Contenido de Pestañas -->
                <div class="tab-content">
                    <!-- Pestaña Información Básica -->
                    <div v-show="activeTab === 'basica'" class="space-y-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Columna Izquierda -->
                            <div class="space-y-6">
                                <div>
                                    <InputLabel value="🔑 Clave del Grupo" class="mb-2 font-medium"/>
                                    <TextInput
                                        v-model="form.clave_grupo"
                                        type="text"
                                        class="w-full p-3.5 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-200"
                                        placeholder="Ej: GRP-2024-01"
                                        :error="!!form.errors.clave_grupo"
                                    />
                                    <InputError :message="form.errors.clave_grupo" class="mt-2"/>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <InputLabel value="📅 Periodo" class="mb-2 font-medium"/>
                                        <TextInput
                                            v-model="form.grupo_periodo"
                                            type="text"
                                            class="w-full p-3.5 border-2 border-gray-200 rounded-xl"
                                            placeholder="Ej: 2023-2024"
                                        />
                                    </div>
                                    <div>
                                        <InputLabel for="grado" value="🎓 Grado" class="mb-2 font-medium"/>
                                        <TextInput
                                            v-model="form.grado"
                                            type="text"
                                            class="w-full p-3.5 border-2 border-gray-200 rounded-xl"
                                            placeholder="Ej: 2do año"
                                        />
                                        <InputError :message="form.errors.grado" class="mt-2"/>
                                    </div>
                                </div>

                            </div>

                            <!-- Columna Derecha -->
                            <div class="space-y-6">

                                <div class="grid grid-cols-1 gap-4">
<!--                                    <div>-->
<!--                                        <InputLabel for="ciclo" value="ciclo" class="mb-2 font-medium text-gray-700"/>-->
<!--                                        <select-->
<!--                                            v-model="form.ciclo_id"-->
<!--                                            :error="form.errors.ciclo_id"-->
<!--                                            class="w-full"-->
<!--                                        >-->
<!--                                            <option v-for="(ciclo, id) in ciclos" :key="id" :value="id">-->
<!--                                                {{ ciclo }}-->
<!--                                            </option>-->
<!--                                        </select>-->
<!--                                        <InputError :message="form.errors.ciclo_id" class="mt-2"/>-->
<!--                                    </div>-->
<!--                                    <div>-->
<!--                                        <InputLabel for="nivel" value="nivel" class="mb-2 font-medium text-gray-700"/>-->
<!--                                        <select-->
<!--                                            v-model="form.nivel_id"-->
<!--                                            :error="form.errors.nivel_id"-->
<!--                                            class="w-full"-->
<!--                                        >-->
<!--                                            <option v-for="(nivel, id) in niveles" :key="id" :value="id">-->
<!--                                                {{ nivel }}-->
<!--                                            </option>-->
<!--                                        </select>-->
<!--                                        <InputError :message="form.errors.nivel_id" class="mt-2"/>-->
<!--                                    </div>-->

                                    <div>
                                        <InputLabel value="🏫 Nombre del Grupo" class="mb-2 font-medium"/>
                                        <TextInput
                                            v-model="form.grupo"
                                            type="text"
                                            class="w-full p-3.5 border-2 border-gray-200 rounded-xl mb-2 font-medium"
                                            placeholder="Ej: Grupo de Secundaria"
                                            :error="!!form.errors.grupo"
                                        />
                                        <InputError :message="form.errors.grupo" class="mt-2"/>
                                    </div>

                                    <div>
                                        <InputLabel value="➡️ Grupo Siguiente ID" class="mb-2 font-medium"/>
                                        <TextInput
                                            v-model="form.grupo_siguiente_id"
                                            type="number"
                                            class="w-full p-3.5 border-2 border-gray-200 rounded-xl mb-2 font-medium"
                                            placeholder="Ej: 456"
                                        />
                                    </div>


                                </div>



                            </div>
                        </div>
                        <div class="grid grid-cols-1 gap-4">
                            <!-- Estado Principal -->
                            <div class="bg-gradient-to-r from-indigo-50 to-blue-50 p-6 rounded-2xl flex items-center justify-between">
                                <div class="space-y-2">
                                    <h4 class="text-lg font-bold text-indigo-800">📈 Estado del Grupo</h4>
                                    <p class="text-sm text-indigo-600">
                                        {{ form.status_grupo ? 'Activo' : 'Inactivo' }}
                                    </p>
                                </div>
                                <ToggleSwitch
                                    v-model="form.status_grupo"
                                    :active-color="form.status_grupo ? 'bg-green-500' : 'bg-red-500'"
                                    :show-labels="false"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Pestaña Configuraciones Avanzadas -->
                    <div v-show="activeTab === 'avanzada'" class="space-y-8">

                        <div class="bg-indigo-50 p-6 rounded-2xl space-y-8">

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                                <ToggleSwitch
                                    v-model="form.is_visible"
                                    label="👀 Visibilidad Pública"
                                    description="Mostrar en listados y selecciones"
                                    active-color="bg-green-500"
                                />

                                <ToggleSwitch
                                    v-model="form.is_bloqueado"
                                    label="🔒 Bloqueo de Grupo"
                                    description="Restringir modificaciones"
                                    active-color="bg-red-500"
                                />

                                <ToggleSwitch
                                    v-model="form.is_activo_en_caja"
                                    label="💰 Activo en Caja"
                                    description="Habilitar para pagos y cobros"
                                    active-color="bg-gray-500"
                                />

                                <ToggleSwitch
                                    v-model="form.is_ver_boleta_interna"
                                    label="📄 Boleta Interna"
                                    description="Permitir generación interna"
                                    active-color="bg-purple-500"
                                />

                                <ToggleSwitch
                                    v-model="form.is_ver_boleta_oficial"
                                    label="📑 Boleta Oficial"
                                    description="Generar documentos oficiales"
                                    active-color="bg-teal-500"
                                />

                                <ToggleSwitch
                                    v-model="form.is_grupo_pai"
                                    label="🌐 Grupo PAI"
                                    description="Programa Bachillerato Internacional"
                                    active-color="bg-orange-500"
                                />
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Sección Fija Inferior -->
                <div class="space-y-8">

                    <!-- Botones de Acción -->
                    <div class="flex justify-end gap-4 border-t border-gray-200 pt-8">
                        <SecondaryButton
                            @click="closeModal"
                            type="button"
                            class="px-8 py-3.5 rounded-xl bg-gray-100 hover:bg-gray-200 transition-colors duration-300"
                        >
                            ❌ Cancelar
                        </SecondaryButton>
                        <PrimaryButton
                            type="submit"
                            :disabled="form.processing"
                            class="px-8 py-3.5 rounded-xl bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white font-bold shadow-lg transition-all"
                        >
                            <template v-if="form.processing">
                                <i class="fas fa-spinner fa-spin mr-3"></i> Procesando...
                            </template>
                            <template v-else>
                                💾 {{ form.id ? 'Actualizar Grupo' : 'Crear Nuevo Grupo' }}
                            </template>
                        </PrimaryButton>
                    </div>
                </div>
            </form>
        </div>
    </Modal>
</template>
