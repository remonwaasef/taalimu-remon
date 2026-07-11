<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function switch($locale)
    {
        // Validate locale
        if (! in_array($locale, ['ar', 'en', 'fr'])) {
            return redirect()->back();
        }

        // Store in session
        Session::put('locale', $locale);

        return redirect()->back();
    }
}
