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
            $table->string('author_request_status')->nullable()->after('role'); // pending, approved, rejected, null
            $table->text('author_request_bio')->nullable()->after('author_request_status');
            $table->string('author_request_portfolio')->nullable()->after('author_request_bio');
            $table->timestamp('author_request_at')->nullable()->after('author_request_portfolio');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['author_request_status', 'author_request_bio', 'author_request_portfolio', 'author_request_at']);
        });
    }
};
