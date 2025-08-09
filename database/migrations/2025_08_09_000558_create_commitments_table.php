<?php

use App\Models\AvailableSchedules;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('commitments', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->longText('comment');
            $table->foreignIdFor(User::class, 'service_provider_id');
            $table->foreignIdFor(User::class, 'customer_id');
            $table->foreignIdFor(AvailableSchedules::class);
            $table->foreignIdFor(Service::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commitments');
    }
};
