# Endpoints
Você pode conferir a documentação `swagger` em `localhost:8000/api/docs` com `scalar`, essa, sendo a mais atualizada que este arquivo.

A rota base para todos os endpoints é: `localhost:8000/api`


## Autenticação e session

[`POST`] - `/api/v1/auth/login`

Rota para auth-login local

[`POST`] - `/api/v1/auth/register`

Rota para auth-register local

[`GET`] - `/api/v1/auth/providers/{provider}`

Rota para auth com providers (Google, Facebook, etc...)

Para saber quais provedores são suportados, consulte esta [doc](./endpoints.md).


[`GET`] `/api/v1/auth/providers/{provider}/callback`

Rota de redirecionamento do provider


[`GET`] `/api/v1/me`

Retorna o usuário da session atual.
