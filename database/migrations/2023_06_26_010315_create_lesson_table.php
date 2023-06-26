<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLessonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lesson', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name_lesson', 50);
            $table->text('description_lesson')->nullable();
            $table->uuid('calendar_id');
            $table->dateTime('date_start_lesson');
            $table->dateTime('date_end_lesson');
            $table->time('duration_lesson');
            $table->timestamps();

            //Relations
            $table->foreign('calendar_id')
                ->on('calendar')
                ->references('id')
                ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lesson');
    }
}
