<?php

namespace App\Http\Controllers;

use App\Services\CmsApiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PageController extends Controller
{
    /**
     * Homepage.
     */
    public function home(): View
    {
        $banners = CmsApiService::getBanners();
        $occasions = CmsApiService::getOccasions();
        $collectionsPage = CmsApiService::getCollections();

        return view('pages.home', compact('banners', 'occasions', 'collectionsPage'));
    }

    /**
     * About Us.
     */
    public function about(): View
    {
        $aboutPage = CmsApiService::getAbout();

        return view('pages.about', compact('aboutPage'));
    }

    /**
     * Contact Us.
     */
    public function contact(): View
    {
        $contactPage = CmsApiService::getContact();

        return view('pages.contact', compact('contactPage'));
    }

    /**
     * Submit Contact Inquiry (AJAX / Form).
     */
    public function submitContact(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150',
            'phone' => 'nullable|string|max:30',
            'subject' => 'nullable|string|max:150',
            'message' => 'required|string|max:3000',
        ]);

        $result = CmsApiService::submitContactEnquiry($validated);

        return response()->json($result, ($result['success'] ?? false) ? 200 : 422);
    }

    /**
     * Collections Showcase.
     */
    public function collections(): View
    {
        $collectionsPage = CmsApiService::getCollections();

        return view('pages.collections', compact('collectionsPage'));
    }

    /**
     * Craftsmanship & Heritage.
     */
    public function craftsmanship(): View
    {
        return view('pages.craftsmanship');
    }

    /**
     * Customer Care Overview.
     */
    public function customerCare(): View
    {
        return view('pages.customer-care');
    }

    /**
     * Store Locator.
     */
    public function stores(): View
    {
        return view('pages.stores');
    }

    /**
     * Frequently Asked Questions.
     */
    public function faqs(): View
    {
        return view('pages.faqs');
    }

    /**
     * Size Guide.
     */
    public function sizeGuide(): View
    {
        return view('pages.size-guide');
    }

    /**
     * Shipping & Delivery Policy.
     */
    public function shipping(): View
    {
        return view('pages.shipping');
    }

    /**
     * Returns & Exchanges Policy.
     */
    public function returns(): View
    {
        return view('pages.returns');
    }

    /**
     * Jewellery Care Instructions.
     */
    public function jewelleryCare(): View
    {
        return view('pages.jewellery-care');
    }

    /**
     * Login View.
     */
    public function login(): mixed
    {
        if (session()->has('customer')) {
            return redirect()->route('home');
        }
        return view('pages.login');
    }

    /**
     * Submit Login to Jewellerysoft API.
     */
    public function submitLogin(Request $request)
    {
        $credentials = [
            'login' => $request->input('login') ?: ($request->input('email') ?: $request->input('phone')),
            'password' => $request->input('password'),
        ];

        $result = CmsApiService::customerLogin($credentials);

        if ($result['success']) {
            session([
                'customer' => $result['customer'],
                'customer_token' => $result['token'],
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $result['message'],
                    'redirect' => $request->input('redirect') ?: route('home'),
                    'customer' => $result['customer'],
                ]);
            }

            return redirect($request->input('redirect') ?: route('home'))
                ->with('success', $result['message']);
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => $result['message'],
                'errors' => $result['errors'] ?? null,
            ], 422);
        }

        return redirect()->back()->withInput()->with('error', $result['message']);
    }

    /**
     * Registration View.
     */
    public function register(): mixed
    {
        if (session()->has('customer')) {
            return redirect()->route('home');
        }
        return view('pages.register');
    }

    /**
     * Submit Registration to Jewellerysoft API.
     */
    public function submitRegister(Request $request)
    {
        $data = [
            'name' => $request->input('name') ?: trim($request->input('first_name') . ' ' . $request->input('last_name')),
            'phone' => $request->input('phone'),
            'email' => $request->input('email'),
            'password' => $request->input('password'),
            'password_confirmation' => $request->input('password_confirmation') ?: $request->input('password'),
            'city' => $request->input('city'),
            'pin_code' => $request->input('pin_code'),
        ];

        $result = CmsApiService::customerRegister($data);

        if ($result['success']) {
            session([
                'customer' => $result['customer'],
                'customer_token' => $result['token'],
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $result['message'],
                    'redirect' => route('home'),
                    'customer' => $result['customer'],
                ]);
            }

            return redirect()->route('home')->with('success', $result['message']);
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => $result['message'],
                'errors' => $result['errors'] ?? null,
            ], 422);
        }

        return redirect()->back()->withInput()->with('error', $result['message']);
    }

    /**
     * Customer Logout.
     */
    public function logout(Request $request)
    {
        $token = session('customer_token');
        if ($token) {
            CmsApiService::customerLogout($token);
        }
        session()->forget(['customer', 'customer_token']);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'redirect' => route('home')]);
        }

        return redirect()->route('home')->with('success', 'You have been logged out.');
    }

    /**
     * Customer Account Portal.
     */
    public function account(): mixed
    {
        if (!session()->has('customer')) {
            return redirect()->route('login');
        }

        $customer = session('customer');
        $token = session('customer_token');
        if ($token) {
            $fresh = CmsApiService::customerProfile($token);
            if ($fresh['success'] && !empty($fresh['customer'])) {
                $customer = $fresh['customer'];
                session(['customer' => $customer]);
            }
        }

        return view('pages.account', compact('customer'));
    }
}

