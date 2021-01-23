<?php

declare(strict_types=1);

/*
 * Part of the Stripe Billing Laravel package.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the Cartalyst PSL License.
 *
 * This source file is subject to the Cartalyst PSL License that is
 * bundled with this package in the LICENSE file.
 *
 * @package    Stripe Billing Laravel
 * @version    13.0.0
 * @author     Cartalyst LLC
 * @license    Cartalyst PSL
 * @copyright  (c) 2011-2020, Cartalyst LLC
 * @link       https://cartalyst.com
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CartalystStripeBillingRecreateTheChargeRefundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stripe_charge_refunds', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('charge_id')->index()->unsigned();
            $table->string('stripe_id')->index();
            $table->string('balance_transaction')->nullable();
            $table->string('currency');
            $table->bigInteger('amount');
            $table->string('reason')->nullable();
            $table->string('receipt_number')->nullable();
            $table->mediumText('metadata')->nullable();
            $table->timestamps();

            $table->engine = 'InnoDB';

            $table->foreign('charge_id')->references('id')->on('stripe_charges')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stripe_charge_refunds');
    }
}
