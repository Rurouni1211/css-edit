<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class OthersController extends Controller
{
    public function termsAndConditions()
    {
        return Inertia::render('Others/TermsAndConditions');
    }

    public function privacyPolicy()
    {
        return Inertia::render('Others/PrivacyPolicy');
    }

    public function storeDetails()
    {
        return Inertia::render('Others/StoreDetails');
    }

    public function aboutMe()
    {
        return Inertia::render('Others/AboutMe');
    }

    public function faq()
    {
        return Inertia::render('Others/Faq');
    }
    
    public function concept()
    {
        return Inertia::render('Others/Concept');
    }
}
