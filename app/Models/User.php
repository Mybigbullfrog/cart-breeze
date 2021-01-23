<?php

namespace App\Models;

//use Laravel\Cashier\Billable as LaravelCashier;
use Cartalyst\Stripe\Billing\Laravel\Billable;
use Cartalyst\Stripe\Billing\Laravel\BillableContract;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Cartalyst\Stripe\Billing\Laravel\PaymentMethod\Events;
use Cartalyst\Stripe\Billing\Laravel;
use Cartalyst\Stripe\Billing\Laravel\Charge\Events\ChargeCreated;
use Cartalyst\Stripe\Billing\Laravel\Charge\ChargeContract;
use Cartalyst\Stripe\Billing\Laravel\Charge\Refund\RefundContract;
use Cartalyst\Stripe\Billing\Laravel\Charge\Refund\Events\Refundpdated;
use Illuminate\Notifications\Notifiable;

class User extends \TCG\Voyager\Models\User
{
    //use LaravelCashier;
    //use PaymentMethodContract;
    //use PaymentMethodAttached;
    use HasFactory;
    use Notifiable;
    use Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // public function preferedCurrency(): string
    // {
    // Define the value as being static or check the
    // next example for a more dynamic behaviour.
    //return 'eur';

    // Alternatively, this value can be read from the database if you so desire.
    // So something like the following would read the value
    // from the column "currency" from the "users" table.
    // return $this->currency;
    //}
}
