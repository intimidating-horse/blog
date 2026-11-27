<?php

use App\Models\Bid;
use App\Models\Listing;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;

uses(RefreshDatabase::class);

function makeListingForBids(User $seller, string $price = '0.25', ?Carbon $endsAt = null): Listing
{
    return Listing::create([
        'user_id' => $seller->id,
        'title' => 'Test item',
        'description' => 'A test listing',
        'category' => 'other',
        'condition' => 'good',
        'starting_price' => $price,
        'current_price' => $price,
        'ends_at' => $endsAt ?? now()->addDay(),
    ]);
}

it('shows the current price and minimum next bid', function () {
    $seller = User::factory()->create();
    $listing = makeListingForBids($seller);

    $this->get(route('listings.show', $listing))
        ->assertOk()
        ->assertSee('Current price')
        ->assertSee('€0.25')
        ->assertSee('€1.25');
});

it('requires a bid at least one euro or five percent above the current price', function () {
    $seller = User::factory()->create();
    $bidder = User::factory()->create();
    $listing = makeListingForBids($seller, '0.25');

    $this->actingAs($bidder)->post(route('listings.bids.store', $listing), ['amount' => '1.24'])
        ->assertSessionHasErrors('amount');
    expect(Bid::count())->toBe(0);

    $this->actingAs($bidder)->post(route('listings.bids.store', $listing), ['amount' => '1.25'])
        ->assertRedirect(route('listings.show', $listing));
    expect(Bid::count())->toBe(1)
        ->and((float) $listing->fresh()->current_price)->toBe(1.25);

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('€1.25');
});

it('sets the current price to the exact amount submitted', function () {
    $seller = User::factory()->create();
    $bidder = User::factory()->create();
    $listing = makeListingForBids($seller, '100.00');

    $this->actingAs($bidder)->post(route('listings.bids.store', $listing), ['amount' => '1000.00'])
        ->assertRedirect(route('listings.show', $listing));

    expect((float) $listing->fresh()->current_price)->toBe(1000.0);
});

it('uses five percent as the minimum increment when it exceeds one euro', function () {
    $seller = User::factory()->create();
    $bidder = User::factory()->create();
    $listing = makeListingForBids($seller, '100.00');

    $this->actingAs($bidder)->post(route('listings.bids.store', $listing), ['amount' => '104.99'])
        ->assertSessionHasErrors('amount');
    $this->actingAs($bidder)->post(route('listings.bids.store', $listing), ['amount' => '105.00'])
        ->assertRedirect(route('listings.show', $listing));
});

it('prevents the seller and users bidding after an auction ends', function () {
    $seller = User::factory()->create();
    $listing = makeListingForBids($seller);

    $this->actingAs($seller)->post(route('listings.bids.store', $listing), ['amount' => '1.25'])
        ->assertSessionHasErrors('amount');

    $listing->update(['ends_at' => now()->subSecond()]);
    $bidder = User::factory()->create();
    $this->actingAs($bidder)->post(route('listings.bids.store', $listing), ['amount' => '1.25'])
        ->assertSessionHasErrors('amount');

    expect(Bid::count())->toBe(0)
        ->and((float) $listing->fresh()->current_price)->toBe(0.25);
});

it('requires authentication to submit a bid', function () {
    $listing = makeListingForBids(User::factory()->create());

    $this->post(route('listings.bids.store', $listing), ['amount' => '1.25'])
        ->assertRedirect('/');
});
