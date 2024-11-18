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
        Schema::create('documents', function (Blueprint $table) {

            $table->id();
            $table->bigInteger('topic_id');
            $table->bigInteger('user_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('file')->nullable();
            $table->string('type')->nullable(); //pdf, word, txt
            $table->longText('content')->nullable();
            $table->longText('content_vector')->nullable();
            $table->integer('status')->default(0);
            $table->softDeletes();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
