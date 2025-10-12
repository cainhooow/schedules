<?php

namespace App\Constants;

enum Flags
{
     case Customer;
     case ServiceProvider;
     case Enterprise;
     case AccountTaskLevel1;
     case AccountTaskLevel2;
     case AccountTaskLevel3;
     case AccountCompletedTasks;
     case CanCreateServices;
     case CanContractServices;
     case CanUpdateServices;
     case CanUpdateUsers;
     case CanAuthenticate;
     case LocalAccountProvider;
     case GoogleAccountProvider;
     case FacebookAccountProvider;
}
