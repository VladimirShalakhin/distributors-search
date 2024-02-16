<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Grammars\PostgresGrammar;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Fluent;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::connection()->setSchemaGrammar(new class extends PostgresGrammar
        {
            protected function typeText_array(Fluent $column): string
            {
                return 'text[]';
            }
        });

        Schema::create('distributors', function (Blueprint $table) {
            $table->id();
            $table->string('region_id', 256);
            $table->integer('city_id')->nullable();
            $table->string('name', 256);
            $table->string('status', 256);
            $table->string('address', 256)->nullable();
            $table->addColumn('text_array', 'email')->nullable();
            $table->addColumn('text_array', 'web_site')->nullable();
            $table->addColumn('text_array', 'phone')->nullable();
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('distributors', function (Blueprint $table) {
            $table->dropForeign('distributors_city_id_foreign');
            $table->dropColumn('region_id');
        });
        Schema::dropIfExists('distributors');
    }
};
