# Guia de instalação
Após clonar o repositório, precisamos criar um `.env`, para guardar algumas configurações do app e secrets keys, etc.

Copiar `.env.example` para `.env`

```bash
cp .env.example .env
```

Após isso, execute o comando abaixo, caso já tenha o `composer` e o `php` instalados corretamente.

Agora, para instalar as dependências do projeto:

```bash
composer install
```

Aguarde que o composer termine a instalação das dependências, e logo em seguida, você poderá executar:

```bash
php artisan key:generate
```

Para gerar uma `APP_KEY` do Laravel.

Feitos os passos acima, agora precisamos gerar uma secret key para o `JWT_SECRET`, que pode ser feita:

```bash
php artisan jwt:secret
```

Agora, será necessário que você tenha um banco de dados configurado, de preferência `postgresql`.

Preencha as credenciais no seu `.env`
```env
DB_CONNECTION=pgsql
DB_HOST=172.0.0.0
DB_PORT=5432
DB_DATABASE=schedules
DB_USERNAME=root
DB_PASSWORD=schedules
```

Após se certificar de que está tudo correto, rode as migrations e seeders

```bash
php artisan migrate
```

E as flags:

```bash
php artisan db:seed --class FlagsSeed
```

As flags serão necessárias para o bom funcionamento do app, então certifique-se de que elas foram criadas devidamente.

Até aqui, o projeto já está pronto para rodar.
