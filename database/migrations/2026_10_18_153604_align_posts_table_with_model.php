<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasColumn('posts', 'content') && ! Schema::hasColumn('posts', 'body')) {
            Schema::table('posts', function (Blueprint $table) {
                $table->renameColumn('content', 'body');
            });
        }

        if (! Schema::hasColumn('posts', 'published_at')) {
            Schema::table('posts', function (Blueprint $table) {
                $table->timestamp('published_at')->nullable();
            });

            DB::table('posts')
                ->where('is_published', true)
                ->whereNull('published_at')
                ->update(['published_at' => DB::raw('created_at')]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
