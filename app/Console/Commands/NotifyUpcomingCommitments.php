<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class NotifyUpcomingCommitments extends Command
{
     /**
      * The name and signature of the console command.
      *
      * @var string
      */
     protected $signature = 'app:notify-upcoming-commitments';

     /**
      * The console command description.
      *
      * @var string
      */
     protected $description = 'Notify users one hour before the scheduled time';

     /**
      * Execute the console command.
      */
     public function handle()
     {
          //
     }
}
