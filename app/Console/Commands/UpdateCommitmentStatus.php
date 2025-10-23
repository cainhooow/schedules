<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateCommitmentStatus extends Command
{
     /**
      * The name and signature of the console command.
      *
      * @var string
      */
     protected $signature = 'app:update-commitment-status';

     /**
      * The console command description.
      *
      * @var string
      */
     protected $description = 'Update all commitments "scheduled" status to "running"';

     /**
      * Execute the console command.
      */
     public function handle()
     {
          //
     }
}
