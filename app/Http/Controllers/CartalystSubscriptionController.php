<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class CartalystSubscriptionController extends Controller
{
    /**
     * SUBSCRIPTIONS
     * Subscriptions allow you to charge a customer's card
     * on a recurring basis. A subscription ties a customer
     * to a particular plan you've created.
     *
     * Create a Subscription.
     * Subscribing an Entity to a single plan:
     */
    public function createSubscription()
    {
        // Get the Billable Entity object
        $user = User::find(1);

        // Create the Subscription
        $user->subscriptions()->create([
            'items' => [
                [
                    'plan' => 'monthly',
                    'quantity' => 1,
                ],
            ],
        ]);
    }

    /**
     * Subscribing an Entity to multiple plans:
     */
    public function createMultipleSubscriptions()
    {
        // Get the Billable Entity object
        $user = User::find(1);

        // Create the Subscription
        $user->subscriptions()->create([
            'items' => [
                [
                    'plan' => 'monthly',
                    'quantity' => 1,
                ],
                [
                    'plan' => '50mb-storage-addon',
                    'quantity' => 1,
                ],
            ],
        ]);
    }

    /**
     * Subscribing an Entity to a plan and apply a coupon
     * to this new subscription:
     */
    public function createSubscriptionApplyingCoupon()
    {
        // Get the submitted coupon
        $coupon = request()->input('coupon');

        // Get the Billable Entity object
        $user = User::find(1);

        // Create the Subscription
        $user->subscriptions()->create([
            'discount' => $coupon,
            'items' => [
                [
                    'plan' => 'monthly',
                    'quantity' => 1,
                ],
            ],
        ]);
    }

    /**
     * Create a trial subscription:
     */
    public function createTrialSubscription()
    {
        // Get the Billable Entity object
        $user = User::find(1);

        // Create the Subscription
        $user->subscriptions()->create([
            'trial_end' => Carbon::now()->addDays(14),
            'items' => [
                [
                    'plan' => 'monthly',
                    'quantity' => 1,
                ],
            ],
        ]);
    }

    /**
     * Retrieve a Subscription
     * To retrieve an existing subscription that is attached
     * to an Entity all you're required to do is to call
     * the find() method and pass the subscription id on
     * the subscriptions collection.
     *
     * Usage examples
     */
    public function retrieveSubscription()
    {
        // Get the Billable Entity object
        $user = User::find(1);

        // Find the Subscription
        $subscription = $user->subscriptions->find(10);
    }


    /**
     * If a more robust search is required, you can use
     * the normal Laravel Eloquent methods like where() etc..
     */
    public function retrieveSubscriptionEloquently()
    {
        // Get the Billable Entity object
        $user = User::find(1);

        // Find the Subscription
        $subscription = $user->subscriptions()
            ->where('stripe_id', 'sub_5vp6DX7N6yVJqY')
            ->first();
    }

    /**
     * Check if the Entity has active Subscriptions
     * You can check if an Entity has active subscriptions
     * by calling the isSubscribed() method on the
     * Entity object:
     *
     * Usage
     */
    public function verifyEntityHasSubscription()
    {
        $user = User::find(1);

        // Check if the Entity has any active Subscription
        if ($user->isSubscribed()) {
            echo 'Entity has at least one active Subscription!';
        }
    }

    /**
     * Statuses
     *
     * To determine if the subscription is on the trial period,
     * you may use the onTrialPeriod() method:
     */
    public function verifyEntitySubscriptionStatus()
    {
        // Get the Billable Entity object
        $user = User::find(1);

        // Find the subscription
        $subscription = $user->subscriptions->find(10);

        if ($subscription->isOnTrialPeriod()) {
            echo "Subscription is on trial period from {$subscription->trial_starts_at} to {$subscription->trial_ends_at}!";
        }
    }

    /**
     * To determine if the subscription is marked as canceled,
     * you may use the isCanceled() method:
     */
    public function verifySubscriptionHasBeenCanceled()
    {
        // Get the Billable Entity object
        $user = User::find(1);

        // Find the subscription
        $subscription = $user->subscriptions->find(10);

        if ($subscription->isCanceled()) {
            echo "Subscription was canceled on {$subscription->canceled_at}!";
        }
    }

    /**
     * To determine if the subscription has expired,
     * you may use the isExpired() method:
     */
    public function verifySubscriptionHasExpired()
    {
        // Get the Billable Entity object
        $user = User::find(1);

        // Find the subscription
        $subscription = $user->subscriptions->find(10);

        if ($subscription->isExpired()) {
            echo "Subscription has expired on {$subscription->ended_at}!";
        }
    }

    /**
     * To determine if a subscription is still on their
     * "grace period" until the subscription fully expires.
     * For example, if a user cancels a subscription on
     * March 5th that was scheduled to end on March 10th,
     * the user is on their "grace period" until March 10th.
     */
    public function verifySubscriptionIsOnGracePeriod()
    {
        // Get the Billable Entity object
        $user = User::find(1);

        // Find the subscription
        $subscription = $user->subscriptions->find(10);

        if ($subscription->isOnGracePeriod()) {
            echo "Subscription is on it's grace period until {$subscription->period_ends_at}.";
        }
    }

    /**
     * Update a Subscription.
     *
     * Swap a plan from a Subscription item.
     */
    public function updateSubscription()
    {
        // Get the Billable Entity object
        $user = User::find(1);

        // Find the Subscription
        $subscription = $user->subscriptions->find(10);

        // Get the item to have the plan swapped
        $item = $subscription->items->first();

        // Swap the Subscription plan
        $item->swap('new-plan-name');
    }

    /**
     * Swap a plan from a Subscription item and
     * Invoice the customer.
     */
    public function swapPlanFromSubscriptionItemAndInvoiceCustomer()
    {
        // Get the Billable Entity object
        $user = User::find(1);

        // Find the Subscription
        $subscription = $user->subscriptions->find(10);

        // Get the item to have the plan swapped
        $item = $subscription->items->first();

        // Swap the Subscription plan
        $item->swapAndInvoice('new-plan-name');
    }

    /**
     * Apply a trial period on a subscription.
     */
    public function applyTrialPeriodOnSubscription()
    {
        // Get the Billable Entity object
        $user = User::find(1);

        // Find the Subscription
        $subscription = $user->subscriptions->find(10);

        // Create a new Carbon instance
        $trialPeriod = Carbon::now()->addDays(14);

        // Set the trial on the Subscription
        $subscription->applyTrialPeriod($trialPeriod);
    }

    /**
     * Removing the trial period from a Subscription.
     */
    public function removeTrialPeriodFromSubscription()
    {
        // Get the Billable Entity object
        $user = User::find(1);

        // Find the Subscription
        $subscription = $user->subscriptions->find(10);

        // Remove the trial from the Subscription
        $subscription->removeTrialPeriod();
    }

    /**
     * Apply a coupon to an existing Subscription.
     */
    public function applyCouponToExistingSubscription()
    {
        // Get the submitted coupon
        $coupon = request()->input('coupon');

        // Get the Billable Entity object
        $user = User::find(1);

        // Find the Subscription
        $subscription = $user->subscriptions->find(10);

        // Apply the coupon
        $subscription->applyCoupon($coupon);
    }

    /**
     * Remove a coupon from an existing Subscription
     */
    public function removeCouponFromExistingSubscription()
    {
        // Get the Billable Entity object
        $user = User::find(1);

        // Find the Subscription
        $subscription = $user->subscriptions->find(10);

        // Remove the coupon
        $subscription->removeCoupon();
    }

    /**
     * Cancel a Subscription.
     *
     * Cancel an active Subscription immediately.
     */
    public function cancelSubscription()
    {
        // Get the Billable Entity object
        $user = User::find(1);

        // Find the Subscription
        $subscription = $user->subscriptions->find(10);

        // Cancel the Subscription
        $subscription->cancelNow();
    }

    /**
     * Cancel a subscription at the end of the billing period.
     */
    public function cancelSubscriptionAtEndOfBillingPeriod()
    {
        // Get the Billable Entity object
        $user = User::find(1);

        // Find the Subscription
        $subscription = $user->subscriptions->find(10);

        // Cancel the Subscription
        $subscription->cancelAtPeriodEnd();
    }

    /**
     * Resume a subscription.
     *
     * Resume a canceled subscription.
     */
    public function resumeSubscription()
    {
        // Get the Billable Entity object
        $user = User::find(1);

        // Find the Subscription
        $subscription = $user->subscriptions->find(10);

        // Resume the Subscription
        $subscription->resume();
    }

    /**
     * Retrieve all subscriptions
     *
     * To retrieve all subscriptions that are attached to an
     * Entity all you're required to do is to call the
     * subscriptions collection, yeah, that simple.
     *
     * Usage examples
     */
    public function retrieveAllEntitiesSubscriptions()
    {
        // Get the Billable Entity object
        $user = User::find(1);

        //  Find the Subscriptions
        $subscriptions = $user->subscriptions;
    }

    /**
     * If a more robust search is required, you can use the
     * normal Laravel Eloquent methods like where(),
     * orderBy() etc..
     */
    public function retrieveAllEntitiesSubscriptionsEloquently()
    {
        // Get the Billable Entity object
        $user = User::find(1);

        // Find the Subscriptions
        $subscriptions = $user->subscriptions()
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Find all Subscriptions that are active.
     */
    public function retrieveAllActiveSubscriptions()
    {
        // Get the Billable Entity object
        $user = User::find(1);

        // Find the subscriptions
        $subscriptions = $user->subscriptions()
            ->active()
            ->get();
    }

    /**
     * Find all Subscriptions that are not active.
     */
    public function retrieveAllNonActiveSubscriptions()
    {
        // Get the Billable Entity object
        $user = User::find(1);

        // Find the subscriptions
        $subscriptions = $user->subscriptions()
            ->notActive()
            ->get();
    }

    /**
     * Find all canceled Subscriptions that are canceled.
     */
    public function retrieveAllCanceledSubscriptions()
    {
        // Get the Billable Entity object
        $user = User::find(1);

        // Find the subscriptions
        $subscriptions = $user->subscriptions()
            ->canceled()
            ->get();
    }

    /**
     * Find all non canceled Subscriptions that are not canceled.
     */
    public function retrieveAllNonCanceledSubscriptionsThatAreNotCanceled()
    {
        // Get the Billable Entity object
        $user = User::find(1);

        // Find the subscriptions
        $subscriptions = $user->subscriptions()
            ->notCanceled()
            ->get();
    }

    /**
     * Find all Subscriptions that are expired.
     */
    public function retrieveAllExpiredSubscriptions()
    {
        // Get the Billable Entity object
        $user = User::find(1);

        // Find the subscriptions
        $subscriptions = $user->subscriptions()
            ->expired()
            ->get();
    }

    /**
     * Find all Subscriptions that are not expired.
     */
    public function retrieveAllNonExpiredSubscriptions()
    {
        // Get the Billable Entity object
        $user = User::find(1);

        // Find the subscriptions
        $subscriptions = $user->subscriptions()
            ->notExpired()
            ->get();
    }

    /**
     * Find all Subscriptions that are incomplete.
     */
    public function retrieveAllIncompleteSubscriptions()
    {
        // Get the Billable Entity object
        $user = User::find(1);

        // Find the subscriptions
        $subscriptions = $user->subscriptions()
            ->incomplete()
            ->get();
    }

    /**
     * Find all Subscriptions that are not incomplete.
     */
    public function retrieveAllNonIncompleteSubscriptions()
    {
        // Get the Billable Entity object
        $user = User::find(1);

        // Find the subscriptions
        $subscriptions = $user->subscriptions()
            ->notIncomplete()
            ->get();
    }

    /**
     * Find all Subscriptions that are on grace period.
     */
    public function retrieveAllSubscriptionsOnGracePeriod()
    {
        // Get the Billable Entity object
        $user = User::find(1);

        // Find the subscriptions
        $subscriptions = $user->subscriptions()
            ->onGracePeriod()
            ->get();
    }

    /**
     * Find all Subscriptions that are not on grace period.
     */
    public function retrieveAllSubscriptionsNotOnGracePeriod()
    {
        // Get the Billable Entity object
        $user = User::find(1);

        // Find the subscriptions
        $subscriptions = $user->subscriptions()
            ->notOnGracePeriod()
            ->get();
    }

    /**
     * Find all Subscriptions that are on trial period.
     */
    public function retrieveAllSubscriptionsOnTrialPeriod()
    {
        // Get the Billable Entity object
        $user = User::find(1);

        // Find the subscriptions
        $subscriptions = $user->subscriptions()
            ->onTrialPeriod()
            ->get();
    }

    /**
     * Find all Subscriptions that are not on trial period.
     */
    public function retrieveAllSubscriptionsNotOnTrialPeriod()
    {
        // Get the Billable Entity object
        $user = User::find(1);

        // Find the subscriptions
        $subscriptions = $user->subscriptions()
            ->notOnTrialPeriod()
            ->get();
    }

    /**
     * Find all Subscriptions that are past due.
     */
    public function retrieveAllPastDueSubscriptions()
    {
        // Get the Billable Entity object
        $user = User::find(1);

        // Find the subscriptions
        $subscriptions = $user->subscriptions()
            ->pastDue()->get();
    }

    /**
     * Find all Subscriptions that are not past due.
     */
    public function retrieveAllNonPastDueSubscriptions()
    {
        // Get the Billable Entity object
        $user = User::find(1);

        // Find the subscriptions
        $subscriptions = $user->subscriptions()
            ->notPastDue()
            ->get();
    }

    /**
     * Find all Subscriptions that are unpaid.
     */
    public function retrieveAllUnpaidSubscriptions()
    {
        // Get the Billable Entity object
        $user = User::find(1);

        // Find the subscriptions
        $subscriptions = $user->subscriptions()
            ->unpaid()->get();
    }

    /**
     * Find all Subscriptions that are not unpaid.
     */
    public function retrieveAllPaidSubscriptions()
    {
        // Get the Billable Entity object
        $user = User::find(1);

        // Find the subscriptions
        $subscriptions = $user->subscriptions()
            ->unpaid()->get();
    }
}
