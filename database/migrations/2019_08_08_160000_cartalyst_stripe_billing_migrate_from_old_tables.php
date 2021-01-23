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

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class CartalystStripeBillingMigrateFromOldTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->migratePaymentMethods();

        $this->migrateCharges();
        $this->migrateRefunds();

        $this->migrateSubscriptions();
        $this->migrateSubscriptionItems();

        $this->migrateInvoices();
        $this->migrateInvoiceItems();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }

    protected function migratePaymentMethods()
    {
        DB::table('stripe_cards_old')
            ->select([
                'stripe_cards_old.id',
                'stripe_cards_old.billable_type',
                'stripe_cards_old.billable_id',
                'stripe_cards_old.stripe_id',
                'stripe_cards_old.default',
                'stripe_cards_old.fingerprint',
                'stripe_cards_old.brand',
                'stripe_cards_old.funding',
                'stripe_cards_old.cvc_check',
                'stripe_cards_old.last_four',
                'stripe_cards_old.exp_month',
                'stripe_cards_old.exp_year',
                'stripe_cards_old.name',
                'stripe_cards_old.address_line1',
                'stripe_cards_old.address_line2',
                'stripe_cards_old.address_city',
                'stripe_cards_old.address_state',
                'stripe_cards_old.address_zip',
                'stripe_cards_old.address_country',
                'stripe_cards_old.address_line1_check',
                'stripe_cards_old.address_zip_check',
                'stripe_cards_old.metadata',
                'stripe_cards_old.created_at',
                'stripe_cards_old.updated_at',
            ])
            ->orderBy('stripe_cards_old.id')
            ->chunk('500', function ($cards) {
                $data = $cards->map(function ($card) {
                    return [
                        'billable_type'   => $card->billable_type,
                        'billable_id'     => $card->billable_id,
                        'stripe_id'       => $card->stripe_id,
                        'type'            => 'card',
                        'default'         => $card->default,
                        'billing_details' => json_encode([
                            'address' => [
                                'city'        => $card->address_city,
                                'country'     => $card->address_country,
                                'line1'       => $card->address_line1,
                                'line2'       => $card->address_line2,
                                'postal_code' => $card->address_zip,
                                'state'       => $card->address_state,
                            ],
                            'email' => null,
                            'name'  => $card->name,
                            'phone' => null,
                        ]),
                        'data' => json_encode([
                            'brand'  => $card->brand,
                            'checks' => [
                                'address_line1_check'       => $card->address_line1_check,
                                'address_postal_code_check' => $card->address_zip_check,
                                'cvc_check'                 => $card->cvc_check,
                            ],
                            'country'              => $card->address_country,
                            'exp_month'            => $card->exp_month,
                            'exp_year'             => $card->exp_year,
                            'fingerprint'          => $card->fingerprint,
                            'funding'              => $card->funding,
                            'generated_from'       => null,
                            'last4'                => $card->last_four,
                            'three_d_secure_usage' => [],
                            'wallet'               => null,
                        ]),
                        'metadata'   => $card->metadata,
                        'created_at' => $card->created_at,
                        'updated_at' => $card->updated_at,
                    ];
                });

                DB::table('stripe_payment_methods')->insert($data->toArray());
            })
        ;
    }

    protected function migrateCharges()
    {
        DB::table('stripe_payments_old')
            ->select([
                'stripe_payments_old.id',
                'stripe_payments_old.billable_type',
                'stripe_payments_old.billable_id',
                'stripe_payments_old.stripe_id',
                'stripe_payments_old.card',
                'stripe_payments_old.status',
                'stripe_payments_old.currency',
                'stripe_payments_old.description',
                'stripe_payments_old.statement_descriptor',
                'stripe_payments_old.amount',
                'stripe_payments_old.paid',
                'stripe_payments_old.captured',
                'stripe_payments_old.refunded',
                'stripe_payments_old.failed',
                'stripe_payments_old.failure_code',
                'stripe_payments_old.failure_message',
                'stripe_payments_old.metadata',
                'stripe_payments_old.created_at',
                'stripe_payments_old.updated_at',
            ])
            ->orderBy('stripe_payments_old.id')
            ->chunk('500', function ($charges) {
                $data = $charges->map(function ($charge) {
                    $card = json_decode($charge->card);

                    return [
                        'billable_type'            => $charge->billable_type,
                        'billable_id'              => $charge->billable_id,
                        'stripe_id'                => $charge->stripe_id,
                        'stripe_payment_method_id' => $card->id,
                        'payment_method'           => json_encode([
                            'card' => [
                                'brand'  => $card->brand,
                                'checks' => [
                                    'address_line1_check'       => $card->address_line1_check,
                                    'address_postal_code_check' => $card->address_zip_check,
                                    'cvc_check'                 => $card->cvc_check,
                                ],
                                'country'              => $card->country,
                                'exp_month'            => $card->exp_month,
                                'exp_year'             => $card->exp_year,
                                'fingerprint'          => $card->fingerprint,
                                'funding'              => $card->funding,
                                'last4'                => $card->last4,
                                'three_d_secure_usage' => [],
                                'wallet'               => null,
                            ],
                            'type' => 'card',
                        ]),
                        'status'               => $charge->status,
                        'currency'             => $charge->currency,
                        'amount'               => $charge->amount,
                        'paid'                 => $charge->paid,
                        'captured'             => $charge->captured,
                        'refunded'             => $charge->refunded,
                        'failed'               => $charge->failed,
                        'description'          => $charge->description,
                        'statement_descriptor' => $charge->statement_descriptor,
                        'failure_code'         => $charge->failure_code,
                        'failure_message'      => $charge->failure_message,
                        'metadata'             => $charge->metadata,
                        'created_at'           => $charge->created_at,
                        'updated_at'           => $charge->updated_at,
                    ];
                });

                DB::table('stripe_charges')->insert($data->toArray());
            })
        ;
    }

    protected function migrateRefunds()
    {
    }

    protected function migrateSubscriptions()
    {
        DB::table('stripe_subscriptions_old')
            ->select([
                'stripe_subscriptions_old.billable_type',
                'stripe_subscriptions_old.billable_id',
                'stripe_subscriptions_old.stripe_id',
                'stripe_subscriptions_old.active',
                'stripe_subscriptions_old.discount',
                'stripe_subscriptions_old.metadata',
                'stripe_subscriptions_old.created_at',
                'stripe_subscriptions_old.updated_at',
                'stripe_subscriptions_old.period_starts_at',
                'stripe_subscriptions_old.period_ends_at',
                'stripe_subscriptions_old.ended_at',
                'stripe_subscriptions_old.canceled_at',
                'stripe_subscriptions_old.trial_starts_at',
                'stripe_subscriptions_old.trial_ends_at',
            ])
            ->orderBy('stripe_subscriptions_old.id')
            ->chunk('500', function ($subscriptions) {
                $data = $subscriptions->map(function ($subscription) {
                    $status = $subscription->active === 1 ? 'active' : 'incomplete_expired';

                    if ($subscription->canceled_at) {
                        $status = 'canceled';
                    }

                    if ($subscription->ended_at) {
                        $status = 'incomplete';
                    }

                    return [
                        'billable_type'     => $subscription->billable_type,
                        'billable_id'       => $subscription->billable_id,
                        'stripe_id'         => $subscription->stripe_id,
                        'status'            => $status,
                        'collection_method' => 'charge_automatically',
                        'discount'          => $subscription->discount,
                        'metadata'          => $subscription->metadata,
                        'created_at'        => $subscription->created_at,
                        'updated_at'        => $subscription->updated_at,
                        'period_starts_at'  => $subscription->period_starts_at,
                        'period_ends_at'    => $subscription->period_ends_at,
                        'ended_at'          => $subscription->ended_at,
                        'canceled_at'       => $subscription->canceled_at,
                        'trial_starts_at'   => $subscription->trial_starts_at,
                        'trial_ends_at'     => $subscription->trial_ends_at,
                    ];
                });

                DB::table('stripe_subscriptions')->insert($data->toArray());
            })
        ;
    }

    protected function migrateSubscriptionItems()
    {
        DB::table('stripe_subscriptions_old')
            ->select([
                'stripe_subscriptions.id as subscription_id',
                'stripe_subscriptions_old.stripe_id',
                'stripe_subscriptions_old.quantity',
                'stripe_subscriptions_old.plan',
                'stripe_subscriptions_old.metadata',
                'stripe_subscriptions_old.created_at',
                'stripe_subscriptions_old.updated_at',
            ])
            ->join('stripe_subscriptions', 'stripe_subscriptions.stripe_id', '=', 'stripe_subscriptions_old.stripe_id')
            ->orderBy('stripe_subscriptions_old.id')
            ->chunk('500', function ($subscriptionItems) {
                $data = $subscriptionItems->map(function ($subscriptionItem) {
                    return [
                        'subscription_id' => $subscriptionItem->subscription_id,
                        'stripe_id'       => 'si_'.$subscriptionItem->stripe_id, // This is a fake id because this information is not available
                        'quantity'        => $subscriptionItem->quantity,
                        'plan'            => $subscriptionItem->plan,
                        'metadata'        => $subscriptionItem->metadata,
                        'created_at'      => $subscriptionItem->created_at,
                        'updated_at'      => $subscriptionItem->updated_at,
                    ];
                });

                DB::table('stripe_subscription_items')->insert($data->toArray());
            })
        ;
    }

    protected function migrateInvoices()
    {
        DB::table('stripe_invoices_old')
            ->select([
                'stripe_invoices_old.id',
                'stripe_invoices_old.billable_type',
                'stripe_invoices_old.billable_id',
                'stripe_invoices_old.stripe_id',
                'stripe_invoices_old.currency',
                'stripe_invoices_old.description',
                'stripe_invoices_old.subtotal',
                'stripe_invoices_old.total',
                'stripe_invoices_old.application_fee',
                'stripe_invoices_old.amount_due',
                'stripe_invoices_old.attempted',
                'stripe_invoices_old.attempt_count',
                'stripe_invoices_old.paid',
                'stripe_invoices_old.metadata',
                'stripe_invoices_old.created_at',
                'stripe_invoices_old.updated_at',
                'stripe_invoices_old.period_start',
                'stripe_invoices_old.period_end',
                'stripe_invoices_old.next_payment_attempt',
                'stripe_subscriptions.id as subscription_id',
            ])
            ->leftJoin('stripe_subscriptions', 'stripe_subscriptions.stripe_id', '=', 'stripe_invoices_old.subscription_id')
            ->orderBy('stripe_invoices_old.id')
            ->chunk('500', function ($invoices) {
                $data = $invoices->map(function ($invoice) {
                    $status = $invoice->paid === 1 ? 'paid' : 'uncollectible';

                    return [
                        'billable_type'          => $invoice->billable_type,
                        'billable_id'            => $invoice->billable_id,
                        'subscription_id'        => $invoice->subscription_id,
                        'stripe_id'              => $invoice->stripe_id,
                        'currency'               => $invoice->currency,
                        'status'                 => $status,
                        'attempted'              => $invoice->attempted,
                        'attempt_count'          => $invoice->attempt_count,
                        'paid'                   => $invoice->paid,
                        'amount_due'             => $invoice->amount_due,
                        'subtotal'               => $invoice->subtotal,
                        'total'                  => $invoice->total,
                        'description'            => $invoice->description,
                        'application_fee_amount' => $invoice->application_fee,

                        'metadata'        => $invoice->metadata,
                        'created_at'      => $invoice->created_at,
                        'updated_at'      => $invoice->updated_at,
                        'period_start'    => $invoice->period_start,
                        'period_end'      => $invoice->period_end,
                        'next_attempt_at' => $invoice->next_payment_attempt,
                    ];
                });

                DB::table('stripe_invoices')->insert($data->toArray());
            })
        ;
    }

    protected function migrateInvoiceItems()
    {
        DB::table('stripe_invoice_items_old')
            ->select([
                'stripe_invoices.billable_type',
                'stripe_invoices.billable_id',
                'stripe_invoice_items_old.stripe_id',
                'stripe_invoices.id as invoice_id',
                'stripe_invoice_items_old.currency',
                'stripe_invoice_items_old.type',
                'stripe_invoice_items_old.amount',
                'stripe_invoice_items_old.proration',
                'stripe_invoice_items_old.description',
                'stripe_invoice_items_old.plan',
                'stripe_invoice_items_old.quantity',
                'stripe_invoice_items_old.metadata',
                'stripe_invoice_items_old.created_at',
                'stripe_invoice_items_old.updated_at',
                'stripe_invoice_items_old.period_start',
                'stripe_invoice_items_old.period_end',
            ])
            ->join('stripe_invoices_old', 'stripe_invoices_old.id', '=', 'stripe_invoice_items_old.invoice_id')
            ->join('stripe_invoices', 'stripe_invoices.stripe_id', '=', 'stripe_invoices_old.stripe_id')
            ->orderBy('stripe_invoice_items_old.id')
            ->chunk('500', function ($invoiceItems) {
                $data = $invoiceItems->map(function ($invoiceItem) {
                    return [
                        'invoice_id'      => $invoiceItem->invoice_id,
                        'billable_type'   => $invoiceItem->billable_type,
                        'billable_id'     => $invoiceItem->billable_id,
                        'stripe_id'       => $invoiceItem->stripe_id,
                        'type'            => $invoiceItem->type,
                        'currency'        => $invoiceItem->currency,
                        'amount'          => $invoiceItem->amount,
                        'quantity'        => $invoiceItem->quantity,
                        'proration'       => $invoiceItem->proration,
                        'description'     => $invoiceItem->description,
                        'plan'            => $invoiceItem->plan,
                        'metadata'        => $invoiceItem->metadata,
                        'created_at'      => $invoiceItem->created_at,
                        'updated_at'      => $invoiceItem->updated_at,
                        'period_start_at' => $invoiceItem->period_start,
                        'period_end_at'   => $invoiceItem->period_end,
                    ];
                });

                DB::table('stripe_invoice_items')->insert($data->toArray());
            })
        ;
    }
}
