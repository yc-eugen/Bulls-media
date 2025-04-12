<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('google_sheets', static function (Blueprint $table) {
            $table->id();
            $table->integer('google_account_id');
            $table->string('sheet_id');
            $table->string('sheet_list')->nullable();
            $table->string('range')->nullable();
            $table->timestamps();
        });
    }
};
