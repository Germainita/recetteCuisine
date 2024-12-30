<?php

use App\Models\Recette;
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
        //
        Schema::create('denrees', function (Blueprint $table) {
            $table->id();
            $table->string('denree');
            $table->integer('preparation');
            $table->foreignIdFor(Recette::class);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('denrees');
    }
};
