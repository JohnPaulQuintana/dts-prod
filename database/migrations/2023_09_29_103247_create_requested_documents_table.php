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
        Schema::create('requested_documents', function (Blueprint $table) {
            $table->id();
            $table->string('trk_id')->unique()->nullable();//treacking id generated
            $table->foreignId('requestor');//id of sender
            $table->foreignId('requestor_user');//id of sender
            $table->bigInteger('forwarded_to')->default(1);//id of reciever default is direct to admin
            $table->string('purpose');//purpose
            $table->bigInteger('recieved_offices');//purpose
            $table->string('documents');//purpose
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requested_documents');
    }
};
