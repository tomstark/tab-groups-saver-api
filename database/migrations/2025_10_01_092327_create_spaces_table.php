<?php

declare(strict_types=1);

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
        Schema::create('spaces', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->integer('position')->default(0);
            $table->foreignUuid('user_id')->constrained(); // ToDo - tackle deletion cascade in PHP logic, not DB
            $table->timestamps();
        });

        if (DB::connection()->getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE spaces ALTER COLUMN id SET DEFAULT uuid_generate_v4();');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spaces');
    }
};
