<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            if (Schema::hasColumn('teachers', 'specialization')) {
                $table->dropColumn('specialization');
            }
            if (!Schema::hasColumn('teachers', 'birth_date')) {
                $table->date('birth_date')->nullable()->after('name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->string('specialization')->nullable()->after('name');
            $table->dropColumn('birth_date');
        });
    }
};
