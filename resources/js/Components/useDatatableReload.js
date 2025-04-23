import { ref, nextTick } from 'vue';
import { router } from '@inertiajs/vue3';
import { destroyDataTable, initializeDataTable } from '@/js/arjiapp'; // Tus funciones de DataTable

export function useDatatableReload({ selector, reload, orden = [[0, 'desc']], cantidad_por_pagina = 10 }) {
    // Definimos una variable reactiva para la instancia de DataTable
    const dataTable = ref(null);

    // Método para reinicializar la DataTable
    const refresh = async () => {
        // Ejecuta la función reload, por ejemplo, router.reload()
        // que recargue solo los datos pertinentes
        await reload();

        // Espera a que Vue re-renderice el DOM con los nuevos datos
        await nextTick();

        // Si ya existe una instancia, se destruye
        if (window.jQuery && window.jQuery.fn.DataTable.isDataTable(selector)) {
            dataTable.value.destroy();
        }

        // Se inicializa la DataTable sobre el selector indicado
        initializeDataTable(dataTable, orden, cantidad_por_pagina);
    };

    return { dataTable, refresh };
}
