# Auditoria

Projeto criado para auditar volumes embalados num armazém logístico.

# Estrutura

Será utilizado nesse projeto:

- PHP
- Laravel
- SQLite
- Apache
- Composer

Para satisfazer as condições acima, será utilizado o [Laragon]() 

Depois de baixar e instalar, iniciar o apache para começar o projeto

## Configuração

Para criar o projeto utilizaremos o seguinte comando:

```bash
composer create-project --prefer-dist laravel/laravel auditoria
```

Após a finalização do download, entrar na pasta e iniciar o servidor

```bash
cd auditoria
php artisan serve
```

Também é possível utilizar o seguinte caminho para acessar o projeto [http://localhost:8001/auditoria/public/](http://localhost:8001/auditoria/public/)

## Database

Dentro da pasta do projeto criar um banco de dados 

```bash
touch database/db.sqlite
```

Dentro do arquivo php.ini alterar(descomentar) a extensão do sqlite

```php
extension=pdo_sqlite
```