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
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("user_id")->unsigned();
            $table->text("title");
            $table->text("category");
            $table->text("description");
            $table->text("location");
            $table->string("status");
            $table->string("proof");
            $table->text("date_followed_up")->nullable();
            $table->text("information")->nullable();
            $table->text("result")->nullable();
            $table->timestamps();

            $table->foreign("user_id")->references("id")->on("users")->onDelete("CASCADE");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
