<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->enum('type', ['book' , 'e-book']);
            $table->string('isbn', 25)->unique()->nullable();
            $table->string('code', 25)->unique()->nullable();
            $table->string('title', 50);
            $table->year('year')->nullable();
            $table->smallInteger('pages')->nullable();
            $table->tinyInteger('edition')->nullable();
            $table->enum('ebook_available', [0, 1])->default(0);
            $table->text('description')->nullable();
            $table->string('book_cover_url', 50)->nullable();
            $table->string('ebook_url', 50)->nullable();
            $table->text('table_of_contents')->nullable();
            $table->tinyInteger('total_qty')->nullable();
            $table->tinyInteger('qty_lost')->nullable();
            $table->unsignedTinyInteger('category_id')->nullable();
            $table->unsignedSmallInteger('publisher_id')->nullable();
            $table->unsignedTinyInteger('rack_id')->nullable();
            $table->enum('disabled', [0, 1])->default(0);
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
            $table->foreign('publisher_id')->references('id')->on('publishers')->onDelete('set null');
            $table->foreign('rack_id')->references('id')->on('racks')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
}
