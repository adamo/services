<?php namespace Depcore\Services\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateServicesTable extends Migration
{
    public function up()
    {
        Schema::create('depcore_services_services', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string( 'name' );
            $table->string( 'slug' );
            $table->integer( 'sort_order' )->unsigned(  )->default ( 0 );
            $table->integer( 'price_from' )->unsigned(  )->nullable( );
            $table->text( 'short_description' )->nullable( );
            $table->text( 'content' )->nullable( );
            $table->text( 'content_blocks' )->nullable( );
            $table->string( 'meta_title', 70 )->nullable( );
            $table->string( 'meta_description', 160 )->nullable( );
            $table->boolean( 'published' )->default( 0 );

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('depcore_services_services');
    }
}
