<?php

namespace App\Constants;

enum Flags
{
    case Customer;
    case ServiceProvider;
    case Enterprise;
    case Account_Task_Level_1;
    case Account_Task_Level_2;
    case Account_Task_Level_3;
    case Account_Completed_Tasks;
    case Can_Create_Services;
    case Can_Contract_Services;
    case Can_Update_Services;
    case Can_Update_Users;
    case Can_Authenticate;
}
