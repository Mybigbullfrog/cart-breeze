<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class CartalystCustomerController extends Controller
{
    /**
     * Cartalyst create a new Stripe customer
     */
    public function createStripeCustomer()
    {
        // Get the non Billable Entity object
        $user = User::find(1);

        // Create the Stripe customer on the non Billable Entity
        $user->createStripeCustomer([
            'email' => $user->email,
        ]);
    }

    /**
     * Update an existing customer
     */
    public function updateStripeCustomer()
    {
        // Get the Billable Entity object
        $user = User::find(1);

        // Update the Stripe customer
        $user->updateStripeCustomer([
            'description' => 'This is a VIP customer!',
        ]);
    }

    /**
     * Delete an existing Stripe customer
     */
    public function deleteStripeCustomer()
    {
        // Get the Billable Entity object
        $user = User::find(1);

        // Delete the Stripe Customer
        $user->deleteStripeCustomer();
    }

    /**
     * Determine if Entity is Billable
     */
    public function determineIfEntityIsBillable()
    {
        // Get the Billable Entity object
        $user = User::find(1);

        // Determine if the Entity is Billable
        if (!$user->isBillable()) {
            echo 'The Entity is not ready to be billed!';
        }
    }

    /**
     * Determine if the Entity has a discount applied
     */
    public function determineIfEntityHasDiscountApplied()
    {
        // Get the Billable Entity object
        $user = User::find(1);

        // Determine if the Entity has a stripe discount applied
        if (!$user->hasStripeDiscount()) {
            echo 'The Entity does not have a Stripe discount applied.';
        }
    }

    /**
     * Apply a discount
     */
    public function applyDiscount()
    {
        // Get the submitted Stripe coupon
        $coupon = request()->input('coupon');

        // Get the Billable Entity object
        $user = User::find(1);

        // Apply the coupon on the Entity
        $user->applyStripeDiscount($coupon);
    }

    /**
     * Remove the discount
     */
    public function removeDiscount()
    {
        // Get the Billable Entity object
        $user = User::find(1);

        // Remove the coupon from the Entity
        $user->removeStripeDiscount();
    }

    /**
     * Synchronize the Entity
     */
    public function synchronizeEntity()
    {
        // Get the Billable Entity object
        $user = User::find(1);

        // Synchronize the Billable Entity with Stripe
        $user->syncWithStripe();
    }
}
