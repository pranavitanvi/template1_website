<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    /**
     * Shopping Cart / Bag view.
     */
    public function cart(): View
    {
        return view('pages.cart');
    }

    /**
     * Checkout view.
     */
    public function checkout(): View
    {
        return view('pages.checkout');
    }

    /**
     * Order success confirmation view.
     */
    public function orderSuccess(Request $request): View
    {
        $orderId = $request->query('order');

        return view('pages.order-success', compact('orderId'));
    }

    /**
     * Wishlist view.
     */
    public function wishlist(): View
    {
        return view('pages.wishlist');
    }
}
