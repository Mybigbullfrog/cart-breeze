<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartalystRefundController extends Controller
{

    /**
     * REFUNDS
     * Refund objects allow you to refund a charge that has
     * previously been created but not yet refunded. Funds
     * will be refunded to the credit or debit card that was
     * originally charged. The fees you were originally
     * charged are also refunded.
     *
     * Create a Refund
     * Creating a new Refund will Refund a Charge that has previously been created but not yet refunded. Funds will be refunded to the credit or debit card that was originally charged. The fees you were originally charged are also refunded.
     *
     * Arguments
     * The following are the arguments that the update() method
     * accepts.
     */
    public function createRefund()
    {
        // Get the Billable Entity object
        $user = User::find(1);

        // Find the Charge
        $payment = $user->charges->find(10);

        // Refund the Charge
        $payment->refund();
    }

    /**
     * Do a partial Refund
     */
    public function partialRefund()
    {
        // Get the Billable Entity object
        $user = User::find(1);

        // Find the Charge
        $payment = $user->charges->find(10);

        // Refund the Charge
        $payment->refund(5000);
    }

    /**
     * Retrieve a Refund
     * To retrieve an existing Charge Refund call the find()
     * method and pass the refund id on the refunds collection.
     *
     * Usage examples
     */
    public function retrieveRefund()
    {
        // Get the Billable Entity object
        $user = User::find(1);

        // Find the Charge
        $charge = $user->charges->find(10);

        // Find the Refund
        $refund = $charge->refunds->find(2020);
    }

    /**
     * If a more robust search is required,
     * you can use the normal Laravel Eloquent
     * methods like where() etc..
     */
    public function retrieveRefundEloquently()
    {
        // Get the Billable Entity object
        $user = User::find(1);

        // Find the Charge
        $charge = $user->charges->find(10);

        // Find the Refund
        $refund = $user->refunds()
            ->where('stripe_id', 're_15iBdsJvzVWl1WTevOaPONHV')
            ->first();
    }

    /**
     * Update a refund
     * Updating a Refund is very easy.
     *
     * Arguments
     * The following are the arguments that the update()
     * method accepts.
     */
    public function updateRefund()
    {
        // Get the Billable Entity object
        $user = User::find(1);

        // Find the Charge
        $charge = $user->charges->find(10);

        // Find the Refund to be updated
        $refund = $charge->refunds->find(2020);

        // Update the Refund
        $refund->update([
            'metadata' => [
                'foo' => 'Bar',
            ],
        ]);
    }

    /**
     * Retrieve all Refunds
     *
     * To retrieve all Refunds that are attached to a
     * Charge all you're required to do is to call the
     * refunds collection.
     */
    public function retrieveAllRefunds()
    {
        // Get the Billable Entity object
        $user = User::find(1);

        // Find the Charge
        $charge = $user->charges->find(10);

        // Find the Refunds
        $refunds = $charge->refunds;
    }

    /**
     * If a more robust search is required,
     * you can use the normal Laravel Eloquent
     * methods like where(), orderBy() etc..
     */
    public function retrieveAllRefundsEloquently()
    {
        // Get the Billable Entity object
        $user = User::find(1);

        // Find the Charge
        $charge = $user->charges->find(10);

        // Find the Refunds
        $refunds = $charge->refunds()
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
