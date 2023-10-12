<?php

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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('notification_trk')->nullable();
            $table->unsignedBigInteger('notification_from_id');
            $table->string('notification_from_name');
            $table->unsignedBigInteger('notification_to_id');
            $table->string('notification_message');
            $table->string('notification_status');//read or unread
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
