<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->string('locale', 8)->default('en')->after('remember_token');
            $table->string('currency', 8)->default('USD')->after('locale');
            $table->boolean('is_blocked')->default(false)->after('currency');
            $table->string('social_provider')->nullable()->after('is_blocked');
            $table->string('social_id')->nullable()->after('social_provider');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone', 'locale', 'currency', 'is_blocked', 'social_provider', 'social_id',
            ]);
        });
    }
};
