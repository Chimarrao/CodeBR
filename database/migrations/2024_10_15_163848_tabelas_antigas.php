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
        Schema::create('acessos', function (Blueprint $table) {
            $table->increments('id_acesso');
            $table->string('url_anterior', 512)->nullable();
            $table->string('url_acessada', 512)->nullable();
            $table->string('ip_usuario', 256)->nullable();
            $table->dateTime('data_hora')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->boolean('excluido');
        });

        Schema::create('artigos', function (Blueprint $table) {
            $table->increments('id_artigo');
            $table->string('artigo', 256)->nullable();
            $table->boolean('liberado')->nullable();
            $table->boolean('destaque')->nullable();
            $table->string('descricao', 256)->nullable();
            $table->string('html_title', 256)->nullable();
            $table->string('html_meta', 256)->nullable();
            $table->mediumText('texto')->nullable();
            $table->string('imagem', 255)->nullable();
            $table->string('url', 256)->nullable();
            $table->string('autor', 256)->nullable();
            $table->date('data_criacao')->nullable();
            $table->date('data_publicacao')->nullable();
            $table->date('data_modificacao')->nullable();
            $table->string('lang', 256)->nullable();
            $table->string('tags', 256)->nullable();
            $table->boolean('excluido');
            $table->index('url', 'url_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acessos');
        Schema::dropIfExists('artigos');
    }
};
