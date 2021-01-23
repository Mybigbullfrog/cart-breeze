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

class CartalystStripeBillingRecreateTheInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stripe_invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('billable_type')->index();
            $table->integer('billable_id')->index()->unsigned();
            $table->integer('charge_id')->unsigned()->nullable();
            $table->integer('subscription_id')->unsigned()->nullable();
            $table->string('stripe_id')->index();
            $table->string('stripe_payment_intent_id')->nullable();
            $table->string('number')->nullable();
            $table->string('currency');
            $table->string('status');
            $table->boolean('attempted')->default(0);
            $table->integer('attempt_count')->unsigned()->default(0);
            $table->boolean('paid')->default(0);
            $table->bigInteger('amount_due')->default(0);
            $table->bigInteger('amount_paid')->default(0);
            $table->bigInteger('amount_remaining')->default(0);
            $table->bigInteger('starting_balance')->nullable();
            $table->bigInteger('ending_balance')->nullable();
            $table->mediumText('discount')->nullable();
            $table->bigInteger('subtotal')->default(0);
            $table->bigInteger('total')->default(0);
            $table->boolean('auto_advance')->default(0);
            $table->string('billing_reason')->nullable();
            $table->string('collection_method')->nullable();
            $table->string('description')->nullable();
            $table->bigInteger('application_fee_amount')->nullable();
            $table->integer('tax')->unsigned()->nullable();
            $table->mediumText('tax_rates')->nullable();
            $table->mediumText('metadata')->nullable();
            $table->timestamps();
            $table->timestamp('period_start')->nullable();
            $table->timestamp('period_end')->nullable();
            $table->timestamp('due_at')->nullable();
            $table->timestamp('next_attempt_at')->nullable();
            $table->timestamp('finalized_at')->nullable();
            $table->timestamp('marked_uncollectible_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('voided_at')->nullable();

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
        Schema::dropIfExists('stripe_invoices');
    }
}
