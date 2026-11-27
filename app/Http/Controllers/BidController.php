<?php

namespace App\Http\Controllers;

use App\Models\Bid;
use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BidController extends Controller
{
    public function store(Request $request, Listing $listing)
    {
        $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01', 'decimal:0,2'],
        ]);

        DB::transaction(function () use ($request, $listing) {
            $listing = Listing::query()->lockForUpdate()->findOrFail($listing->id);

            if ($listing->user_id === $request->user()->id) {
                throw ValidationException::withMessages(['amount' => 'You cannot bid on your own listing.']);
            }

            if ($listing->ends_at === null || ! $listing->ends_at->isFuture()) {
                throw ValidationException::withMessages(['amount' => 'This auction has ended.']);
            }

            $currentPriceCents = (int) round((float) $listing->current_price * 100);
            $minimumIncrementCents = max(100, (int) ceil($currentPriceCents * 0.05));
            $minimumBidCents = $currentPriceCents + $minimumIncrementCents;
            $amountCents = (int) round((float) $request->input('amount') * 100);

            if ($amountCents < $minimumBidCents) {
                $minimum = number_format($minimumBidCents / 100, 2, '.', '');
                throw ValidationException::withMessages(['amount' => "Your bid must be at least €{$minimum}."]);
            }

            Bid::create([
                'listing_id' => $listing->id,
                'user_id' => $request->user()->id,
                'amount' => number_format($amountCents / 100, 2, '.', ''),
            ]);

            $listing->update(['current_price' => number_format($amountCents / 100, 2, '.', '')]);
        });

        return redirect()->route('listings.show', $listing)->with('status', 'Your bid was placed.');
    }
}
