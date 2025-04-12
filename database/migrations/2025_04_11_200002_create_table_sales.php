<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales', static function (Blueprint $table) {
            $table->id();
            $table->text('date')->nullable();
            $table->text('name')->nullable();
            $table->text('product_price')->nullable();
            $table->text('amount')->nullable();
        });
    }
};
