<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $listing->title }} | Listings</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    @auth
        <div class="topstrip">
            <div class="wrap topstrip-inner">
                <span>Hello, {{ auth()->user()->name }}</span>
                <form action="/logout" method="POST">
                    @csrf
                    <button class="linkbtn">Sign out</button>
                </form>
            </div>
        </div>
    @endauth

    <main class="wrap page-content">
        @if ($errors->any())
            <div class="errors" role="alert">
                <strong>Please check the following:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (session('status'))
            <div class="success" role="status">{{ session('status') }}</div>
        @endif
        <p class="back-link"><a href="{{ route('home') }}#listings">&larr; Back to listings</a></p>
        <article class="listing-detail">
            <div class="detail-thumb" aria-hidden="true">{{ strtoupper(substr($listing->title, 0, 1)) }}</div>
            <div class="detail-content">
                <p class="meta-line">{{ ucfirst($listing->category) }} <span aria-hidden="true">·</span> {{ ucfirst(str_replace('_', ' ', $listing->condition)) }}</p>
                <h1>{{ $listing->title }}</h1>
                <p class="detail-description">{{ $listing->description }}</p>
                <p class="seller">Listed by <strong>{{ $listing->user?->name ?? 'Unknown seller' }}</strong></p>
            </div>
            <aside class="detail-price">
                <span class="price">€{{ number_format((float) $listing->current_price, 2) }}</span>
                <span class="price-caption">Current price</span>
                <span class="price-caption">{{ $listing->ends_at?->isFuture() ? 'Ends in ' . now()->diffForHumans($listing->ends_at, ['parts' => 2, 'short' => true, 'syntax' => \Carbon\CarbonInterface::DIFF_ABSOLUTE]) : 'Auction ended' }}</span>
            </aside>
        </article>

        @php
            $currentCents = (int) round((float) $listing->current_price * 100);
            $minimumBidCents = $currentCents + max(100, (int) ceil($currentCents * 0.05));
            $minimumBid = number_format($minimumBidCents / 100, 2, '.', '');
            $auctionOpen = $listing->ends_at?->isFuture() ?? false;
        @endphp
        <section class="bid-panel" aria-labelledby="bid-heading">
            <div>
                <h2 id="bid-heading">Place a bid</h2>
                <p class="price-caption">Minimum next bid: <strong>€{{ $minimumBid }}</strong></p>
            </div>
            @if (!$auctionOpen)
                <p class="bid-note">This auction has ended.</p>
            @elseif (auth()->check() && auth()->id() === $listing->user_id)
                <p class="bid-note">You can’t bid on your own listing.</p>
            @elseif (auth()->guest())
                <p class="bid-note">Please <a href="{{ route('home') }}">sign in</a> to place a bid.</p>
            @else
                <form class="bid-form" action="{{ route('listings.bids.store', $listing) }}" method="POST">
                    @csrf
                    <label class="field" for="bid-amount">Your bid (€)
                        <input id="bid-amount" type="number" name="amount" min="{{ $minimumBid }}" step="0.01" value="{{ old('amount', $minimumBid) }}" required>
                    </label>
                    <button class="btn" type="submit">Confirm bid</button>
                </form>
            @endif
        </section>

    </main>
</body>
</html>
