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
                <span class="price">€{{ number_format((float) $listing->starting_price, 2) }}</span>
                <span class="price-caption">Starting bid</span>
                <span class="price-caption">{{ $listing->ends_at?->isFuture() ? 'Ends in ' . now()->diffForHumans($listing->ends_at, ['parts' => 2, 'short' => true, 'syntax' => \Carbon\CarbonInterface::DIFF_ABSOLUTE]) : 'Auction ended' }}</span>
            </aside>
        </article>
    </main>
</body>
</html>
