<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            if (Schema::hasColumn('events', 'city')) {
                $table->dropColumn('city');
            }
            if (Schema::hasColumn('events', 'venue')) {
                $table->dropColumn('venue');
            }
        });

        Schema::dropIfExists('cities');
    }

    public function down(): void
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::table('events', function (Blueprint $table) {
            if (!Schema::hasColumn('events', 'city')) {
                $table->string('city')->nullable()->after('capacity');
            }
            if (!Schema::hasColumn('events', 'venue')) {
                $table->string('venue')->nullable()->after('city');
            }
        });
    }
};
