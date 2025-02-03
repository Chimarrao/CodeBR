<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration {
    public function up()
    {
        // Definição dos pares de artigos (Português e Inglês)
        $artigosRelacionados = [
            ['php-vai-morrer-em-2024', 'php-will-die-in-2024'],
            ['bibliotecas-interface-grafica-python', 'python-graphical-interface-libraries'],
            ['sqlite-e-php-comparacao-com-mysql', 'sqlite-and-php-comparison-with-mysql'],
            ['como-resolver-parse-error-syntax-error-unexpected-end-of-file-php', 'error-cannot-modify-header-information-headers-already-sent'],
            ['avif-vs-webp-comparacao-formato-de-imagem', 'avif-vs-webp-image-format-comparison'],
            ['como-configurar-jwt-no-laravel-passo-a-passo', 'how-to-configure-jwt-in-laravel-step-by-step'],
            ['clean-code-codigo-limpo-desenvolvimento', 'clean-code-principles-for-development'],
            ['unlim-armazenamento-em-nuvem-ilimitado-e-gratuito', 'unlim-unlimited-and-free-cloud-storage'],
            ['documentar-api-laravel-com-swagger', 'document-api-laravel-with-swagger'],
        ];

        foreach ($artigosRelacionados as $artigos) {
            $artigo1 = DB::table('artigos')->where('url', $artigos[0])->first();
            $artigo2 = DB::table('artigos')->where('url', $artigos[1])->first();

            if ($artigo1 && $artigo2) {
                $idLigacao = Str::uuid();

                DB::table('artigos_idiomas')->insert([
                    ['id_ligacao' => $idLigacao, 'id_artigo' => $artigo1->id_artigo, 'created_at' => now(), 'updated_at' => now()],
                    ['id_ligacao' => $idLigacao, 'id_artigo' => $artigo2->id_artigo, 'created_at' => now(), 'updated_at' => now()],
                ]);
            }
        }
    }

    public function down()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('artigos_idiomas')->truncate();
        Schema::enableForeignKeyConstraints();
    }
};
