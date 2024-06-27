Projeto criado no curso como criar API com Laravel 11 e aplicativo com React Native. Módulo como criar API com Laravel 11.

## Requisitos

* PHP 8.2 ou superior
* MySQL 8 ou superior
* Composer

## Como rodar o projeto baixado

Duplicar o arquivo ".env.example" e renomear para ".env".<br>
Alterar no arquivo .env as credenciais do banco de dados.<br>
Alterar no arquivo .env as credenciais do e-mail, recomendado utilizar servidor de teste:
* https://mailtrap.io/
* https://www.startmail.com/pricing/<br>
Em vez de usar o servidor fake, é recomendado utilizar o Iagente. Altere no arquivo .env as credenciais do servidor de e-mail: https://www.iagente.com.br/solicitacao-conta-smtp/origin/celke<br>

Instalar as dependências do PHP.
```
composer install
```

Gerar a chave.
```
php artisan key:generate
```

Executar as migration.
```
php artisan migrate
```

Executar as seed.
```
php artisan db:seed
```

Iniciar o projeto criado com Laravel.
```
php artisan serve
```

Para acessar a API, é recomendado utilizar o Insomnia para simular requisições à API.
```
http://127.0.0.1:8000/api/users
```


## Lista de requisições no Insomnia
Backup das requisições do Insomnia disponível no diretório "Insomnia".<br>
Criar as variáveis de ambiente:
```
{
	"URL": "http://127.0.0.1:8000/api",
	"TOKEN": "gerar_token_na_rota_login_e_colocar_aqui"
}
```


## Sequencia para criar o projeto
Criar o projeto com Laravel
```
composer create-project laravel/laravel .
```

Alterar no arquivo .env as credenciais do banco de dados<br>

Criar o arquivo de rotas para API.
```
php artisan install:api
```

Iniciar o projeto criado com Laravel.
```
php artisan serve
```

Para acessar a API, é recomendado utilizar o Insomnia para simular requisições à API.
```
http://127.0.0.1:8000/api/users
```

Criar a migration.
```
php artisan make:migration create_name_table
```
```
php artisan make:migration create_bills_table
```

Executar as migration.
```
php artisan migrate
```

Criar a Model.
```
php artisan make:model NomeDaModel
php artisan make:model Bill
```

Criar seed.
```
php artisan make:seeder NomeDaSeeder
php artisan make:seeder BillSeeder
```

Executar as seed.
```
php artisan db:seed
```

Criar a Controller.
```
php artisan make:model NomeDaController
php artisan make:controller BillController
```

Criar o Request.
```
php artisan make:request NomeDoRequest
php artisan make:request BillRequest
```

Instalar o pacote de auditoria do Laravel
```
composer require owen-it/laravel-auditing
```

Publicar a configuração e as migration para auditoria
```
php artisan vendor:publish --provider "OwenIt\Auditing\AuditingServiceProvider" --tag="config"
```
```
php artisan vendor:publish --provider "OwenIt\Auditing\AuditingServiceProvider" --tag="migrations"
```

## Como usar o GitHub
Baixar os arquivos do Git.
```
git clone --branch <branch_name> <repository_url> .
```

Verificar a branch.
```
git branch 
```

Baixar as atualizações.
```
git pull
```

Adicionar todos os arquivos modificados no staging area - área de preparação.
```
git add .
```

Representa um conjunto de alterações em um ponto específico da história do seu projeto, registra apenas as alterações adicionadas ao índice de preparação.
O comando -m permite que insira a mensagem de commit diretamente na linha de comando.
```
git commit -m "Descrição do commit"
```

Enviar os commits locais, para um repositório remoto.
```
git push <remote> <branch>
git push origin dev-master
```
