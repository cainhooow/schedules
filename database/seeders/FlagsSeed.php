<?php

namespace Database\Seeders;

use App\Constants\FlagConstant;
use App\Models\Flag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FlagsSeed extends Seeder
{
    public function run(): void
    {
        $flags = [
            ['name' => FlagConstant::CUSTOMER, 'description' => 'Usuário de preferencia cliente, busca serviços.'],
            ['name' => FlagConstant::SERVICE_PROVIDER, 'description' => 'Usuário de preferencia serviço, presta serviços.'],
            ['name' => FlagConstant::ENTERPRISE, 'description' => 'Usuário de preferencia empresa, flag exclusiva para empresas.'],

            ['name' => FlagConstant::ACCOUNT_TASK_LEVEL_1, 'description' => 'Completou a personalização da conta nivel 1'],
            ['name' => FlagConstant::ACCOUNT_TASK_LEVEL_2, 'description' => 'Completou a personalização da conta nivel 2'],
            ['name' => FlagConstant::ACCOUNT_TASK_LEVEL_3, 'description' => 'Completou a personalização da conta nivel 3'],
            ['name' => FlagConstant::ACCOUNT_COMPLETED_TASKS, 'description' => 'Completou todas as tarefas de personalização'],

            ['name' => FlagConstant::CAN_CREATE_SERVICES, 'description' => 'Usuário de preferencia serviço, presta e cria serviços'],
            ['name' => FlagConstant::CAN_CONTRACT_SERVICES, 'description' => 'O usuário de preferencia customer pode contratar um serviço'],
            ['name' => FlagConstant::CAN_UPDATE_SERVICES, 'description' => 'Pode atualizar serviços'],
            ['name' => FlagConstant::CAN_UPDATE_USERS, 'description' => 'Pode atualizar usuários'],
            ['name' => FlagConstant::CAN_AUTHENTICATE, 'description' => 'Pode fazer login'],
        ];

        foreach ($flags as $flag) {
            Flag::create($flag);
        }
    }
}
