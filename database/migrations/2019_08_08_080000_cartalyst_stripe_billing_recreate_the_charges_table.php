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

class CartalystStripeBillingRecreateTheChargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stripe_charges', function (Blueprint $table) {
            $table->increments('id');
            $table->string('billable_type')->index();
            $table->integer('billable_id')->index()->unsigned();
            $table->integer('invoice_id')->unsigned()->nullable();
            $table->string('stripe_id')->index();
            $table->string('stripe_payment_intent_id')->nullable();
            $table->string('stripe_payment_method_id')->nullable();
            $table->string('stripe_balance_transaction_id')->nullable();
            $table->string('stripe_order_id')->nullable();
            $table->string('status');
            $table->text('payment_method')->nullable();
            $table->string('currency');
            $table->bigInteger('amount');
            $table->bigInteger('amount_refunded')->nullable();
            $table->boolean('paid')->default(0);
            $table->boolean('captured')->default(0);
            $table->boolean('refunded')->default(0);
            $table->boolean('failed')->default(0);
            $table->text('outcome')->nullable();
            $table->string('description')->nullable();
            $table->string('statement_descriptor')->nullable();
            $table->string('statement_descriptor_suffix')->nullable();
            $table->string('failure_code')->nullable();
            $table->string('failure_message')->nullable();
            $table->text('fraud_details')->nullable();
            $table->text('shipping')->nullable();
            $table->string('receipt_number')->nullable();
            $table->string('receipt_email')->nullable();
            $table->mediumText('metadata')->nullable();
            $table->timestamps();

            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stripe_charges');
    }
}
