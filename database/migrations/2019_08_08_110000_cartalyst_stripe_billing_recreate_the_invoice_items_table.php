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

class CartalystStripeBillingRecreateTheInvoiceItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stripe_invoice_items', function (Blueprint $table) {
            $table->increments('id');
            $table->string('billable_type')->index();
            $table->integer('billable_id')->index()->unsigned();
            $table->integer('invoice_id')->unsigned()->nullable();
            $table->string('stripe_id')->index();
            $table->string('type')->nullable();
            $table->string('currency');
            $table->bigInteger('amount');
            $table->integer('quantity')->unsigned()->nullable();
            $table->boolean('proration')->default(0);
            $table->string('description')->nullable();
            $table->boolean('discountable')->default(0);
            $table->text('plan')->nullable();
            $table->mediumText('tax_rates')->nullable();
            $table->mediumText('metadata')->nullable();
            $table->timestamps();
            $table->timestamp('period_start_at')->nullable();
            $table->timestamp('period_end_at')->nullable();

            $table->engine = 'InnoDB';

            $table->foreign('invoice_id')->references('id')->on('stripe_invoices')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stripe_invoice_items');
    }
}
