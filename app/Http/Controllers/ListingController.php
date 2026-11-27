<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ListingController extends Controller
{
    public function createListing(Request $request)
    {
        $incomingFields = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'category' => [
                'required',
                Rule::in(['electronics', 'clothing', 'books', 'other']),
            ],
            'starting_price' => ['required', 'numeric', 'min:0', 'decimal:0,2'],
            'duration_days' => ['required', 'integer', 'min:1', 'max:30'],
            'condition' => [
                'required',
                Rule::in(['new', 'like_new', 'good', 'acceptable', 'worn_out']),
            ],
        ]);
        $incomingFields['title'] = strip_tags($incomingFields['title']);
        $incomingFields['description'] = strip_tags($incomingFields['description']);
        $incomingFields['current_price'] = $incomingFields['starting_price'];
        $incomingFields['user_id'] = auth()->id();
        $incomingFields['ends_at'] = now()->addDays((int) $incomingFields['duration_days']);
        unset($incomingFields['duration_days']);
        Listing::create($incomingFields);
        return redirect('/');
    }
}
