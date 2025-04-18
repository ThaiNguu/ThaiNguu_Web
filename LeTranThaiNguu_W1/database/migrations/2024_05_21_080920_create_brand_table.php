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
        Schema::create('lttn_brand', function (Blueprint $table) {
            $table->id();
            $table->string("name", 1000);
            $table->string("slug", 1000)->nullable();
            $table->string("parent_id")->default(0);
            $table->string("image", 1000)->nullable();
            $table->unsignedInteger("sort_order")->default(0);
            $table->text("description")->nullable();
            $table->unsignedInteger("created_by")->default(1);
            $table->unsignedInteger("updated_by")->nullable();
            $table->unsignedTinyInteger("status")->default(2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lttn_brand');
    }
};
