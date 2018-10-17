<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePortfolioSubmissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('portfolio_submission', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('portfolio_id');
            $table->foreign('portfolio_id')->references('id')->on('portfolios');

            $table->unsignedInteger('submission_id');
            $table->foreign('submission_id')->references('id')->on('submissions');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('portfolio_submission');
    }
}
