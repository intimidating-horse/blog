{{--
    resources/views/home.blade.php
    CSS goes in public/css/style.css

    Route: Route::get('/', fn () => view('home'));

    Sample listings are used until your controller passes a $listings collection.
    Each listing needs: id, title, seller (username), condition, bid, bids, ends_at (Carbon date), and optionally image.
--}}
@php
    $listings = $listings ?? collect([
        ['id' => 1, 'title' => 'Calculus textbook, 8th edition', 'seller' => 'marta_eng', 'condition' => 'Good', 'bid' => 12, 'bids' => 4, 'ends_at' => now()->addMinutes(38)],
        ['id' => 2, 'title' => 'Mini fridge (works, a bit loud)', 'seller' => 'jordi_phys', 'condition' => 'Used', 'bid' => 35, 'bids' => 9, 'ends_at' => now()->addMinutes(95)],
        ['id' => 3, 'title' => 'Casio scientific calculator', 'seller' => 'aina_chem', 'condition' => 'Like new', 'bid' => 8, 'bids' => 2, 'ends_at' => now()->addHours(7)],
        ['id' => 4, 'title' => 'Fixie bike with new brakes', 'seller' => 'pau_biz', 'condition' => 'Good', 'bid' => 70, 'bids' => 14, 'ends_at' => now()->addDay()],
        ['id' => 5, 'title' => 'Desk lamp and three plants', 'seller' => 'laia_design', 'condition' => 'Like new', 'bid' => 6, 'bids' => 1, 'ends_at' => now()->addDays(2)],
        ['id' => 6, 'title' => 'Graduation gown, size M', 'seller' => 'marc_law', 'condition' => 'New', 'bid' => 15, 'bids' => 0, 'ends_at' => now()->addDays(3)],
    ])->map(fn ($l) => (object) $l);
    $categories = ['Books', 'Electronics', 'Furniture', 'Clothing', 'Transport', 'Tickets', 'Other'];
@endphp
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'campusbid') }} | Student auctions</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

<div class="topstrip">
    <div class="wrap">
        <div>
            @guest
                Hi! <a href="{{ url('/login') }}">Log in</a> or <a href="{{ url('/register') }}">register</a>
            @endguest
            @auth
                Hi, {{ auth()->user()->name }}!
                <form method="POST" action="{{ url('/logout') }}">
                    @csrf
                    <button class="linkbtn">Log out</button>
                </form>
            @endauth
        </div>
        <div>
            @auth
                <a href="{{ url('/my-listings') }}">My listings</a>
            @endauth
        </div>
    </div>
</div>

<header class="mainbar">
    <div class="wrap">
        <a href="{{ url('/') }}" class="logo">campusbid</a>
        <form class="search" action="{{ url('/') }}" method="GET" role="search">
            <input type="search" name="q" value="{{ request('q') }}" placeholder="Search for anything" aria-label="Search listings">
            <button type="submit">Search</button>
        </form>
        <a href="{{ url('/listings/create') }}" class="btn btn-main">Make a listing</a>
    </div>
</header>

<nav class="catnav" aria-label="Categories">
    <div class="wrap">
        <a href="{{ url('/') }}" class="active">All</a>
        @foreach ($categories as $cat)
            <a href="{{ url('/?category=' . urlencode($cat)) }}">{{ $cat }}</a>
        @endforeach
    </div>
</nav>

<main class="wrap">
    <div class="results-head">
        <h1>Open auctions</h1>
        <span>{{ $listings->count() }} {{ Str::plural('result', $listings->count()) }}</span>
    </div>

    <div class="list">
        @forelse ($listings as $l)
            <article class="item">
                <a href="{{ url('/listings/' . $l->id) }}" class="thumb">
                    @if (!empty($l->image))
                        <img src="{{ asset('storage/' . $l->image) }}" alt="{{ $l->title }}">
                    @else
                        {{ Str::upper(Str::substr($l->title, 0, 1)) }}
                    @endif
                </a>
                <div class="info">
                    <a href="{{ url('/listings/' . $l->id) }}" class="title">{{ $l->title }}</a>
                    <span class="meta-line">Condition: {{ $l->condition }}</span>
                    <span class="meta-line">Seller: {{ $l->seller }}</span>
                    <span class="price">&euro;{{ $l->bid }}</span>
                    <span class="bids">{{ $l->bids }} {{ Str::plural('bid', $l->bids) }}</span>
                </div>
                <div class="side">
                    <span class="time {{ $l->ends_at->isBefore(now()->addHour()) ? 'soon' : '' }}">
                        {{ $l->ends_at->isPast() ? 'Ended' : 'Ends in ' . $l->ends_at->diffForHumans(now(), true, true) }}
                    </span>
                    <a href="{{ url('/listings/' . $l->id) }}" class="btn btn-sm">Place bid</a>
                </div>
            </article>
        @empty
            <div class="empty">
                <p>No open auctions yet. Be the first to list something.</p>
                <a href="{{ url('/listings/create') }}" class="btn">Make a listing</a>
            </div>
        @endforelse
    </div>
</main>


</body>
</html>
