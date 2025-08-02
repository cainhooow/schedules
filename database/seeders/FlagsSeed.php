<?php

namespace Database\Seeders;

use App\Constants\Flags;
use App\Models\Flag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FlagsSeed extends Seeder
{
    public function run(): void
    {
        $flags = [
            ['name' => Flags::CUSTOMER, 'description' => 'Usuário de preferencia cliente, busca serviços.'],
            ['name' => Flags::SERVICE_PROVIDER, 'description' => 'Usuário de preferencia serviço, presta serviços.'],
            ['name' => Flags::ENTERPRISE, 'description' => 'Usuário de preferencia empresa, flag exclusiva para empresas.'],

            ['name' => Flags::ACCOUNT_TASK_LEVEL_1, 'description' => 'Completou a personalização da conta nivel 1'],
            ['name' => Flags::ACCOUNT_TASK_LEVEL_2, 'description' => 'Completou a personalização da conta nivel 2'],
            ['name' => Flags::ACCOUNT_TASK_LEVEL_3, 'description' => 'Completou a personalização da conta nivel 3'],
            ['name' => Flags::ACCOUNT_COMPLETED_TASKS, 'description' => 'Completou todas as tarefas de personalização'],

            ['name' => Flags::CAN_CREATE_SERVICES, 'description' => 'Usuário de preferencia serviço, presta e cria serviços'],
            ['name' => Flags::CAN_CONTRACT_SERVICES, 'description' => 'O usuário de preferencia customer pode contratar um serviço'],
            ['name' => Flags::CAN_UPDATE_SERVICES, 'description' => 'Pode atualizar serviços'],
            ['name' => Flags::CAN_UPDATE_USERS, 'description' => 'Pode atualizar usuários'],
            ['name' => Flags::CAN_AUTHENTICATE, 'description' => 'Pode fazer login'],
        ];

        foreach ($flags as $flag) {
            Flag::create($flag);
        }
    }
}
