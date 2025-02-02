<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up()
    {
        DB::statement('ALTER TABLE artigos MODIFY id_artigo INT(7) UNSIGNED NOT NULL AUTO_INCREMENT;');

        Schema::create('artigos_idiomas', function (Blueprint $table) {
            $table->id();
            $table->uuid('id_ligacao'); 
            $table->unsignedInteger('id_artigo'); 
            $table->timestamps();

            // Chave estrangeira corretamente formada
            $table->foreign('id_artigo')
                  ->references('id_artigo')
                  ->on('artigos')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('artigos_idiomas');
    }
};
