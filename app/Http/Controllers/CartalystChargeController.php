<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartalystChargeController extends Controller
{
    /**
     * Creating a Charge
     * To charge a credit card, you create a charge object.
     * If your API key is in test mode, the supplied card
     * won't actually be charged, though everything else
     * will occur as if in live mode.
     * (Stripe assumes that the charge would have completed
     * successfully).
     *
     * Arguments
     * The following are the arguments that the create()
     * method of the Card Gateway accepts.
     */
    public function charge()
    {
        // Get the Billable Entity object
        $user = User::find(1);

        // Create the Charge
        $charge = $user->charges()->create([
            'amount' => 5055,
            'currency' => 'usd',
            'description' => 'A single Charge',
        ]);
    }


    /**
     * Retrieve a Charge
     * To retrieve an existing payment that is attached to an Entity all
     * you're required to do is to call the find() method and pass the
     * payment id on the charges collection.
     */
    public function retrieveCharge()
    {
        // Get the Billable Entity object
        $user = User::find(1);

        // Find the Charge
        $payment = $user->charges->find(10);
    }

    /**
     * If a more robust search is required, you can use
     * the normalLaravel Eloquent methods like where() etc..
     */
    public function retrieveChargeWithEloquent()
    {
        // Get the Billable Entity object
        $user = User::find(1);

        // Find the Charge
        $payment = $user->charges()
            ->where('stripe_id', 'ch_15jaLgJvzVWl1WTeiY8u661R')
            ->first();
    }

    /**
     * Statuses
     * To determine if a Charge is fully captured,
     * you may use the isFullyCaptured() method:
     */
    public function verifyChargeIsFullyCaptured()
    {
        // Get the Billable Entity object
        $user = User::find(1);

        // Find the Charge
        $payment = $user->charges->find(10);

        if ($payment->isFullyCaptured()) {
            {
                echo 'The Charge is fully captured.';
            }
        }
    }

    /**
     * To determine if the Charge is fully refunded,
     * you may use the isFullyRefunded() method:
     */
    public function verifyChargeHasBeenFullyRefunded()
    {
        // Get the Billable Entity object
        $user = User::find(1);

        // Find the Charge
        $payment = $user->charges->find(10);

        if ($payment->isFullyRefunded()) {
            echo 'The Charge is fully refunded.';
        }
    }

    /**
     * To determine if the Charge is partially captured,
     * you may use the isPartiallyCaptured() method:
     */
    public function verifyChargeIsPartiallyCaptured()
    {
        // Get the Billable Entity object
        $user = User::find(1);

        // Find the Charge
        $payment = $user->charges->find(10);

        if ($payment->isPartiallyCaptured()) {
            echo 'The Charge is partially captured.';
        }
    }

    /**
     * To determine if the Charge is partially refunded,
     * you may use the isPartiallyRefunded() method:
     */
    public function verifyChargeIsPartiallyRefunded()
    {
        // Get the Billable Entity object
        $user = User::find(1);

        // Find the Charge
        $payment = $user->charges->find(10);

        if ($payment->isPartiallyRefunded()) {
            echo 'The Charge is partially refunded.';
        }
    }

    /**
     * To determine if the Charge is paid,
     * you may use the isPaid() method:
     */
    public function verifyChargeIsPaid()
    {
        // Get the Billable Entity object
        $user = User::find(1);

        // Find the Charge
        $payment = $user->charges->find(10);

        if ($payment->isPaid()) {
            echo 'The Charge is not paid.';
        }
    }


    /**
     * Update a Charge
     * Updating a Charge is very easy.
     *
     * Arguments
     * The following are the arguments that
     * the update() method accepts
     */
    public function updateCharge()
    {
        // Get the Billable Entity object
        $user = User::find(1);

        // Find the Charge to be updated
        $payment = $user->charges->find(10);

        // Update the Charge
        $payment->update([
            'description' => 'Charge for the Book',
        ]);
    }

    /**
     * Fully capture a Charge
     * Fully capture an un-captured Charge on the entity of its amount
     *
     * Arguments
     * The following are the arguments that the capture() method accepts
     */
    public function fullyCaptureCharge()
    {
        // Get the Billable Entity object
        $user = User::find(1);

        // Find the Charge
        $charge = $user->charges->find(10);

        // Fully capture the Charge
        $charge->capture();
    }

    /**
     * Partially capture a Charge
     * Fully capture an un-captured Charge on the given amount
     * Arguments
     * The following are the arguments that the partiallyCapture() method accepts
     */
    public function partiallyCaptureCharge()
    {
        // Get the Billable Entity object
        $user = User::find(1);

        // Find the Charge
        $charge = $user->charges->find(10);

        // Partially capture the Charge
        $charge->partiallyCapture(2349);
    }

    /**
     * Retrieve all Charges
     * To retrieve all charges that are attached to an
     * Entity all you're required to do is to call the
     * charges collection, yeah, that simple.
     */
    public function retrieveAllCharges()
    {
        // Get the Billable Entity object
        $user = User::find(1);

        // Find the Charges
        $charges = $user->charges;
    }

    /**
     * If a more robust search is required,
     * you can use the normal Laravel Eloquent methods
     * like where(), orderBy() etc..
     */
    public function retrieveAllChargesEloquently()
    {
        // Get the Billable Entity object
        $user = User::find(1);

        // Find the Charges
        $charges = $user->charges()
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Synchronization
     * Often you might have the need to synchronize the data
     * from Stripe with your database, we have an easy way to
     * achieve this.
     * This method synchronizes all the Charges of Entity
     * from Stripe into the local storage.
     */
    public function synchronizeAllEntityChargesFromStripeToLocalDatabase()
    {
        // Get the Billable Entity object
        $user = User::find(1);

        // Synchronize this Entity Charges
        $user->charges()->syncWithStripe();
    }

    /**Events
     * On this section you can find all the events that are
     * dispatched whenever an action occurs within the Charges API.
     *
     * Note: For more information on how Laravel Events work
     * and how to set Event Listeners, please refer to the
     * Laravel Documentation.
     */
}
