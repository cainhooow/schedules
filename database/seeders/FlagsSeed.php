<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FlagsSeed extends Seeder
{
    public function run(): void
    {
        $flags = [
            ['name' => 'CUSTOMER', 'description' => 'Usuário de preferencia cliente, busca serviços.'],
            ['name' => 'SERVICE_PROVIDER', 'description' => 'Usuário de preferencia serviço, presta serviços.'],
            ['name' => 'ENTERPRISE', 'description' => 'Usuário de preferencia empresa, flag exclusiva para empresas.'],

            ['name' => 'ACCOUNT_TASK_LEVEL_1', 'description' => 'Completou a personalização da conta nivel 1'],
            ['name' => 'ACCOUNT_TASK_LEVEL_2', 'description' => 'Completou a personalização da conta nivel 2'],
            ['name' => 'ACCOUNT_TASK_LEVEL_3', 'description' => 'Completou a personalização da conta nivel 3'],
            ['name' => 'ACCOOUNT_COMPLETED_TASKS', 'description' => 'Completou todas as tarefas de personalização'],

            ['name' => 'CAN_CREATE_SERVICES', 'description' => 'Usuário de preferencia serviço, presta e cria serviços'],
            ['name' => 'CAN_UPDATE_SERVICES', 'description' => 'Pode atualizar serviços'],
            ['name' => 'CAN_UPDATE_USERS', 'description' => 'Pode atualizar usuários'],
            ['name' => 'CAN_AUTHENTICATE', 'description' => 'Pode fazer login'],
        ];

        DB::table('flags')->insert($flags);
    }
}
