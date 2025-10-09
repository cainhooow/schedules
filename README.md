# 🗓️ Schedules

**Schedules** é um sistema backend desenvolvido em **PHP (Laravel 12.x)** para agendamento de serviços gerais, como barbeiros, tatuadores e outros profissionais. Ele oferece um sistema robusto de permissões baseado em **flags dinâmicas**, com um fluxo de cadastro dividido em etapas e integração com ferramentas externas como o Google Calendar.

---

## 🚀 Visão Geral

O sistema permite que usuários se cadastrem como:

-   **Customer** – usuários que contratam serviços
-   **ServiceProvider** – usuários que oferecem serviços
-   **Enterprise** - empresas

Essa definição é feita por meio de um sistema de **flags**, que são atribuídas progressivamente conforme o usuário avança nas etapas do cadastro.

---

## 🔐 Sistema de Tarefas por Flags

O processo de cadastro é dividido em **três níveis de tarefa**, cada um associado a uma flag específica. Essas flags também controlam o acesso a funcionalidades do sistema.

### ✅ `AccountTaskLevel1` – Definir tipo de conta
-   O usuário escolhe se será um `Customer` ou `ServiceProvider`.
-   Flags atribuídas:
    -   `Customer` → recebe `CanContractServices`
    -   `ServiceProvider` → recebe `CanCreateService`

### ✅ `AccountTaskLevel2` – Criação do perfil

-   O usuário preenche dados como nome público, nome do negócio (caso prestador), biografia e avatar.
-   Ao concluir, recebe automaticamente a flag `AccountTaskLevel2`.

### ✅ `AccountTaskLevel3` – Definir endereço padrão

-   O usuário informa pelo menos um endereço. Isso permitirá futuramente a recomendação de serviços com base em localização (cidade/estado).

Ao concluir todas as etapas, o usuário recebe a flag final: `AccountCompletedTasks`.

---

## 🏷️ Sistema de Flags

As flags são utilizadas amplamente no sistema para:

-   Identificar o tipo de conta
-   Controlar permissões de acesso
-   Indicar progresso no cadastro
-   Definir preferências do usuário
-   Liberar funcionalidades específicas

Esse sistema substitui modelos tradicionais de controle de acesso por um mecanismo mais flexível e extensível.

---

## 🔌 Integrações (em desenvolvimento)

-   **Google Calendar** – sincronização de agendamentos
-   **Notificações** – via e-mail e pela própria plataforma
-   **Estatísticas e relatórios** – para prestadores de serviço
-   **Recomendações por região** – serviços exibidos conforme a localização do usuário

---

## 🧱 Stack Tecnológica

| Tecnologia               | Descrição                                      |
| ------------------------ | ---------------------------------------------- |
| **PHP**                  | Linguagem principal                            |
| **Laravel 12.x**         | Framework backend                              |
| **PostgreSQL**           | Banco de dados                                 |
| **IDs estilo Snowflake** | Geração distribuída de IDs ordenados por tempo |
| **Flags dinâmicas**      | Controle de permissões e progresso             |

---

## 📌 Funcionalidades Futuras

Painel administrativo para gerenciamento de flags

Suporte a múltiplas regiões

Listagem pública de serviços

Agendamentos por disponibilidade (calendário)

Sistema avançado de notificações (e-mail, in-app, push)