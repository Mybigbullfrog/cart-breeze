<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class CartalystPaymentMethodController extends Controller
{
    /**
     * Payment methods
     * Attach a Payment Method
     */
    public function attachPaymentMethod()
    {
        // Receive the submitted Stripe Payment Method ID that was generated using Stripe.js
        $paymentMethodId = request()->input('payment_method_id');

        // Get the Billable Entity object
        $user = User::find(1);

        // Create the card and make it default
        $user->paymentMethods()->attach($paymentMethodId);
    }

    /**
     * Detach a Payment Method
     */
    public function detachPaymentMethod()
    {
        // Get the Billable Entity object
        $user = User::find(1);

        // Find the Payment Method to be detached
        $paymentMethod = $user->paymentMethods->find(10);

        // Detach the Payment Method
        $paymentMethod->detach();
    }

    /**
     * Create a Payment Method
     */
    public function createPaymentMethod()
    {
        // Get the Billable Entity object
        $user = User::find(1);

        // Create the Payment Method
        $paymentMethod = $user->paymentMethods()->create([
            'type' => 'card',
            'card' => [
                'number' => '4242424242424242',
                'exp_month' => 10,
                'cvc' => 314,
                'exp_year' => 2025,
            ],
        ]);
    }

    /**
     * Retrieve a Payment Method
     */
    public function retrievePaymentMethod()
    {
        // Get the Billable Entity object
        $user = User::find(1);

        // Find the Payment Method
        $paymentMethod = $user->paymentMethods->find(10);
    }

    /**
     * Retrieve a more robust Payment Method
     */
    public function retrievePaymentMethodWithEloquent()
    {
        // Get the Billable Entity object
        $user = User::find(1);

        // Find the Payment Method
        $paymentMethod = $user->paymentMethods()
            ->where('stripe_id', 'pm_1EUpGU4Lf1hyuG3tyUasGREf')
            ->first();
    }

    /**
     * Determine if the Entity has active Payment Methods
     */
    public function determineIfEntityHasActivePaymentMethods()
    {
        // Get the Billable Entity object
        $user = User::find(1);

        // Determine if the Entity has active Payment Methods
        if (!$user->hasActivePaymentMethods()) {
            echo 'The Entity does not have any active Payment Methods!';
        }
    }

    /**
     * Get the Entity default Payment Method
     */
    public function getEntityDefaultPaymentMethod()
    {
        // Get the Billable Entity object
        $user = User::find(1);

        // Get the Entity default Payment Method object
        $paymentMethod = $user->getDefaultPaymentMethod();

        echo $paymentMethod->data['last4'];
    }

    /**
     * Statuses
     * Is Expirable
     */
    public function isExpirable()
    {
        // Get the Billable Entity object
        $user = User::find(1);

        // Find the Payment Method
        $paymentMethod = $user->paymentMethods->find(10);

        // Determine if the Payment Method is expirable
        if (!$paymentMethod->isExpirable()) {
            echo 'The Payment Method is not expirable.';
        }
    }

    /**
     * Has expired
     * To determine if the Payment Method has expired,
     * you may use the hasExpired() method:
     */
    public function hasExpired()
    {
        // Get the Billable Entity object
        $user = User::find(1);

        // Find the Payment Method
        $paymentMethod = $user->paymentMethods->find(10);

        if ($paymentMethod->hasExpired()) {
            {
                echo 'The Payment Method has expired.';
            }
        }
    }

    /**
     * Is expiring  // In this example we'll be checking if the
     * Payment Method is expiring in the upcoming month.
     */
    public function isPaymentMethodExpiring()
    {
        // Create a new Carbon object date
        $date = Carbon\Carbon::now()->addMonth();

        // Get the Billable Entity object
        $user = User::find(1);

        // Find the Payment Method
        $paymentMethod = $user->paymentMethods->find(10);

        // Check if the Payment Method is expiring
        if ($paymentMethod->isExpiringOn($date)) {
            echo 'The Payment Method is going to expire next month!';
        }
    }

    /**
     * Is default
     * To determine if the Payment Method is the default
     * Payment Method, you may use the isDefault()
     */
    public function isDefaultPaymentMethod()
    {
        // Get the Billable Entity object
        $user = User::find(1);

        // Find the Payment Method
        $paymentMethod = $user->paymentMethods->find(10);

        if ($paymentMethod->isDefault()) {
            echo 'This is the default Payment Method.';
        }
    }

    /**
     * Update a Payment Method
     * Updating a Payment Method is straightforward.
     */
    public function updatePaymentMethod()
    {
        // Get the Billable Entity object
        $user = User::find(1);

        // Find the Payment Method to be updated
        $paymentMethod = $user->paymentMethods->find(10);

        // Update the Payment Method
        $paymentMethod->update([
            'billing_details' => [
                'name' => 'John Doe',
            ],
        ]);
    }

    /**
     * Setting an existing Payment Method as the
     * default Payment Method
     */
    public function setExistingPaymentMethodAsDefaultPaymentMethod()
    {
        $user = User::find(1);

        $user->paymentMethods->find(10)->setAsDefault();
    }

    /**
     *Retrieve all Payment Methods
     * To retrieve all Payment Methods that are attached
     * to an Entity all you need to do is to call the
     * paymentMethods collection.
     */
    public function retrieveAllPaymentMethodsAttachedToAnEntity()
    {
        // Get the Billable Entity object
        $user = User::find(1);

        // Find the Payment Methods
        $paymentMethods = $user->paymentMethods;
    }

    /**
     * If a more robust search is required,
     * you can use the normal Laravel Eloquent methods
     * like where(), orderBy() etc..
     */
    public function retrieveAllPaymentMethodsAttachedToAnEntityWithEloquent()
    {
        // Get the Billable Entity object
        $user = User::find(1);

        // Find the Payment Methods with a custom query
        $paymentMethods = $user->paymentMethods()->where(function ($query) {
            return $query
                ->where('type', 'card')
                ->where('data.exp_year', '>=', '2020');
        })->get();
    }

    /**
     *Synchronization
     * Often you might have the need to synchronize the data
     * from Stripe with your database, we have an easy way
     * to achieve this.
     *
     * This method synchronizes all the Payment Methods of
     * Entity from Stripe into the local storage.
     */
    public function synchronizeDataFromStripeToOurDatabase()
    {
        // Get the Billable Entity object
        $user = User::find(1);

        // Synchronize this Entity Payment Methods
        $user->paymentMethods()->syncWithStripe();
    }
}
