<?php namespace Depcore\UserReview\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class UpdateServicesTable extends Migration
{
    public function up()
    {
        Schema::table('depcore_services_services', function(Blueprint $table) {
            $table->string( 'title' )->nullable(  );
        });
    }

    public function down()
    {
        Schema::table('depcore_services_services', function(Blueprint $table) {
            $table->dropColumn('title');
        });
    }
}
