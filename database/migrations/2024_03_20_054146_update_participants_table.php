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
        Schema::table('participants', function (Blueprint $table) {
            $after = 'is_active';
            for($i =1; $i<=11; $i++)
            {
                $table->integer('q'.$i)
                    ->after($after)
                    ->nullable();
                $table->dateTime('q'.$i.'_t')
                    ->after('q'.$i)
                    ->nullable();
                $after = 'q'.$i;
            }

            $table->index('telegram_id', 'telegram_id');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
