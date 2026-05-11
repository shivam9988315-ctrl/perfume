<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('product_type')->default('perfume')->after('gender');
            $table->boolean('is_limited_edition')->default(false)->after('is_on_sale');
            $table->text('top_notes')->nullable()->after('description');
            $table->text('middle_notes')->nullable()->after('top_notes');
            $table->text('base_notes')->nullable()->after('middle_notes');
        });

        Schema::table('product_variants', function (Blueprint $table) {
            $table->unsignedSmallInteger('volume_ml')->nullable()->after('size_label');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'product_type', 'is_limited_edition', 'top_notes', 'middle_notes', 'base_notes',
            ]);
        });

        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropColumn('volume_ml');
        });
    }
};
