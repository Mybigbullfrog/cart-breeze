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

class CartalystStripeBillingRenameTheSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stripe_subscriptions', function (Blueprint $table) {
            $table->dropIndex(['stripe_id']);
            $table->dropIndex(['billable_type']);
            $table->dropIndex(['billable_id']);
        });

        Schema::rename('stripe_subscriptions', 'stripe_subscriptions_old');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('stripe_subscriptions_old', 'stripe_subscriptions');
    }
}
