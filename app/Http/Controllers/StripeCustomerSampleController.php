<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\StripeCustomer;
use Exception;

class StripeCustomerSampleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $stripeCustomers = StripeCustomer::latest()->paginate(5);

        return view('StripeCustomers.index', compact('stripeCustomers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('stripeCustomers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required',
            'detail' => 'required',
        ]);

        StripeCustomer::create($request->all());

        return redirect()->route('stripeCustomers.index')
            ->with('success', 'StripeCustomer created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  StripeCustomer  $stripeCustomer
     * @return Application|Factory|View
     */
    public function show(StripeCustomer $stripeCustomer)
    {
        return view('stripeCustomers.show', compact('stripeCustomer'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  StripeCustomer  $stripeCustomer
     * @return Application|Factory|View
     */
    public function edit(StripeCustomer $stripeCustomer)
    {
        return view('stripeCustomers.edit', compact('stripeCustomer'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  StripeCustomer  $stripeCustomer
     * @return RedirectResponse
     */
    public function update(Request $request, StripeCustomer $stripeCustomer): RedirectResponse
    {
        $request->validate([
            'name' => 'required',
            'detail' => 'required',
        ]);

        $stripeCustomer->update($request->all());

        return redirect()->route('stripeCustomers.index')
            ->with('success', 'StripeCustomer updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  StripeCustomer  $stripeCustomer
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(StripeCustomer $stripeCustomer): RedirectResponse
    {
        $stripeCustomer->delete();

        return redirect()->route('stripeCustomers.index')
            ->with('success', 'StripeCustomer deleted successfully');
    }
}