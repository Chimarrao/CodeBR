<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

![PHP](https://img.shields.io/badge/-PHP-777BB4?style=flat-square&logo=php&logoColor=white) ![Laravel](https://img.shields.io/badge/-Laravel-FF2D20?style=flat-square&logo=laravel&logoColor=white) ![Bulma CSS](https://img.shields.io/badge/-Bulma-00D1B2?style=flat-square&logo=bulma&logoColor=white) ![TypeScript](https://img.shields.io/badge/-TypeScript-3178C6?style=flat-square&logo=typescript&logoColor=white) ![Licença](https://img.shields.io/badge/Licença-MIT-green?style=flat-square)

# CodeBR Website

O CodeBR Website foi inicialmente desenvolvido por volta de 2021, utilizando PHP + Bootstrap, com base no template CleanBlog (https://startbootstrap.com/theme/clean-blog). Em 2023, foi lançada a versão 2.0, reimaginada e reconstruída com Laravel + BulmaCSS. A estrutura HTML foi visualmente inspirada no template original, incorporando novos recursos como paginação e funcionalidade de busca.

## Tecnologias Utilizadas

- Laravel 10
- BulmaCSS 0.9
- PHP 8.3
- Typescript 5.3
- Bootstrap (removida)
- CleanBlog Template (removida)

## Como executar o Projeto

Instale as dependências do front e compile os códigos em typescript:

```bash
npm install
npm run build
```

Atualização do Arquivo .env:

Copie o arquivo .env.example e renomeie para .env.
Abra o arquivo .env e ajuste as configurações conforme necessário, como conexão com o banco de dados.

No backend, instale as dependências via composer, gere a chave do app, realize as migrations e coloque o servidor para executar:

```bash
composer install
php artisan key:generate
php artisan migrate
php artisan serve
```

## Histórico de Versões

-**Versão 1.0 (2021):**

- Desenvolvido com PHP + Bootstrap
- Baseado no template CleanBlog

-**Versão 1.1 (2023):**

- Passado para Laravel (fins de teste, não publicado)

-**Versão 2.0 (2023):**

- Recriado com Laravel + BulmaCSS
- Aprimorado visualmente com base no template original
- Adição de funcionalidades como paginação e busca

## Como Contribuir

Sinta-se à vontade para contribuir para o desenvolvimento do CodeBR. Se você encontrar problemas, bugs ou tiver ideias para melhorias, abra uma issue ou envie um pull request.

## Licença

Este projeto está licenciado sob a [Licença MIT](LICENSE).
