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
        Schema::table('users', function (Blueprint $table) {
            // Google'dan gelen benzersiz kullanıcı ID'si
            $table->string('google_id')->nullable()->unique()->after('id');

            // Kullanıcının profil resmi URL'i
            $table->string('avatar')->nullable()->after('email');

            // Google ile giriş yapan kullanıcılar için şifre opsiyonel
            $table->string('password')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['google_id', 'avatar']);

            // Password'u tekrar zorunlu yap
            $table->string('password')->nullable(false)->change();
        });
    }
};
