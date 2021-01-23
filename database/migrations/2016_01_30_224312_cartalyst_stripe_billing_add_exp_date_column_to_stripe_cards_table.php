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

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CartalystStripeBillingAddExpDateColumnToStripeCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stripe_cards', function (Blueprint $table) {
            $table->timestamp('exp_date')->after('exp_year')->nullable();
        });

        foreach (DB::table('stripe_cards')->get() as $card) {
            DB::table('stripe_cards')->where('id', $card->id)->update([
                'exp_date' => (string) Carbon::createFromDate($card->exp_year, $card->exp_month)->endOfMonth(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stripe_cards', function (Blueprint $table) {
            $table->dropColumn('exp_date');
        });
    }
}
