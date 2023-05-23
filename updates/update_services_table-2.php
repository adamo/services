<?php namespace Depcore\UserReview\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class UpdateServicesTable2 extends Migration
{
    public function up()
    {
        Schema::table('depcore_services_services', function(Blueprint $table) {
            $table->string( 'portfolio_category_ids' )->nullable(  );
        });
    }

    public function down()
    {
        Schema::table('depcore_services_services', function(Blueprint $table) {
            $table->dropColumn('portfolio_category_ids');
        });
    }
}
