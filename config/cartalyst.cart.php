<?php

/*
 * Part of the Cart package.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the Cartalyst PSL License.
 *
 * This source file is subject to the Cartalyst PSL License that is
 * bundled with this package in the LICENSE file.
 *
 * @package    Cart
 * @version    5.1.0
 * @author     Cartalyst LLC
 * @license    Cartalyst PSL
 * @copyright  (c) 2011-2020, Cartalyst LLC
 * @link       https://cartalyst.com
 */

// Import the necessary classes
use Cartalyst\Cart\Cart;
use Cartalyst\Cart\Storage\NativeSession;
use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Session\FileSessionHandler;
use Illuminate\Session\Store;

return [
    /*
    |--------------------------------------------------------------------------
    | Default Session Key
    |--------------------------------------------------------------------------
    |
    | This option allows you to specify the default session key used by the Cart.
    |
    */

    'session_key' => 'cartalyst_cart',

    /*
    |--------------------------------------------------------------------------
    | Default Cart Instance
    |--------------------------------------------------------------------------
    |
    | Define here the name of the default cart instance.
    |
    */

    'instance' => 'main',

    /*
    |--------------------------------------------------------------------------
    | Required Indexes
    |--------------------------------------------------------------------------
    |
    | Here you can define all the indexes that are required to be passed
    | when adding or updating items.
    |
    */

    'requiredIndexes' => [],
];
