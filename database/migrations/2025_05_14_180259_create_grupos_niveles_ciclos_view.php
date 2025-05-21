<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        DB::statement("
            CREATE VIEW _vi_grupo_niveles_ciclos AS
            SELECT
                gn.id,
                gn.ciclo_id,
                c.ciclo,
                c.fecha_inicio,
                c.fecha_fin,
                c.predeterminado,
                gn.nivel_id,
                n.nivel,
                n.clave_nivel,
                n.clave_registro_nivel,
                n.nivel_oficial,
                n.nivel_fiscal,
                gn.grupo_id,
                g.grupo,
                g.clave_grupo,
                g.grupo_oficial,
                g.grupo_periodo,
                g.grupo_periodo_ciclo,
                g.grado,
                g.grado_pai,
                g.num,
                g.is_visible as is_grupo_visible,
                g.is_bloqueado,
                g.is_activo_en_caja,
                g.is_ver_boleta_interna,
                g.is_ver_boleta_oficial,
                g.is_grupo_pai,
                g.status_grupo,
                g.grupo_siguiente_id,
                gn.is_visible,
                gn.deleted_at,
                gn.created_at,
                gn.updated_at,
                gn.old_ciclo_id,
                gn.old_nivel_id,
                gn.old_grupo_id,
                gn.old_grupo_nivel_id,
                gn.empresa_id
            FROM grupos_niveles gn
            LEFT JOIN ciclos c
                ON gn.ciclo_id = c.id
            LEFT JOIN niveles n
                ON gn.nivel_id = n.id
            LEFT JOIN grupos g
                ON gn.grupo_id = g.id
        ");

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void{
        DB::statement('DROP VIEW IF EXISTS _vi_grupo_niveles_ciclos');
    }
};
