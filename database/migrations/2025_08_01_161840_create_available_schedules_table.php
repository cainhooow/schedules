<?php

use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
     /**
      * Run the migrations.
      */
     public function up(): void
     {
          Schema::create('available_schedules', function (Blueprint $table) {
               $table->unsignedBigInteger('id')->primary();
               $table->enum(
                    'day_of_week',
                    [
                         'monday',
                         'tuesday',
                         'wednesday',
                         'thursday',
                         'friday',
                         'saturday',
                         'sunday'
                    ]
               );
               $table->time('start_time');
               $table->time('end_time');
               $table->boolean('available')->default(true);
               $table->foreignIdFor(Service::class)->constrained()->onDelete('cascade');
               $table->timestamps();
          });
     }

     /**
      * Reverse the migrations.
      */
     public function down(): void
     {
          Schema::dropIfExists('available_schedules');
     }
};
