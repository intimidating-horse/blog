<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Listings</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    @auth
        <div class="topstrip">
            <div class="wrap topstrip-inner">
                <div class="account-links">
                    <span>Hello, {{ auth()->user()->name }}</span>
                    <form action="/logout" method="POST">
                        @csrf
                        <button class="linkbtn">Sign out</button>
                    </form>
                </div>
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

        @auth
            <section class="sell-panel" id="create-listing">
                <div class="section-heading">
                    <h1>Create a new listing</h1>
                    <p>Add the details below to list an item.</p>
                </div>
                <form class="listing-form" action="/createListing" method="POST">
                    @csrf
                    <label class="field field-wide">Item title
                        <input type="text" name="title" placeholder="What are you selling?" value="{{ old('title') }}" required>
                    </label>
                    <label class="field field-wide">Description
                        <textarea name="description" placeholder="Tell buyers what makes it special" rows="4" required>{{ old('description') }}</textarea>
                    </label>
                    <label class="field">Category
                        <select name="category" required>
                            <option value="" disabled @selected(old('category') === null)>Select category</option>
                            <option value="electronics" @selected(old('category') === 'electronics')>Electronics</option>
                            <option value="clothing" @selected(old('category') === 'clothing')>Clothing</option>
                            <option value="books" @selected(old('category') === 'books')>Books</option>
                            <option value="other" @selected(old('category') === 'other')>Other</option>
                        </select>
                    </label>
                    <label class="field">Condition
                        <select name="condition" required>
                            <option value="" disabled @selected(old('condition') === null)>Select condition</option>
                            <option value="new" @selected(old('condition') === 'new')>New</option>
                            <option value="like_new" @selected(old('condition') === 'like_new')>Like new</option>
                            <option value="good" @selected(old('condition') === 'good')>In good condition</option>
                            <option value="acceptable" @selected(old('condition') === 'acceptable')>Acceptable</option>
                            <option value="worn_out" @selected(old('condition') === 'worn_out')>Well loved</option>
                        </select>
                    </label>
                    <label class="field">Starting price (€)
                        <input type="number" name="starting_price" placeholder="0.00" value="{{ old('starting_price') }}" min="0" step="0.01" required>
                    </label>
                    <label class="field">Auction duration
                        <select name="duration_days" required>
                            <option value="" disabled @selected(old('duration_days') === null)>Select duration</option>
                            @foreach ([1, 3, 5, 7, 10, 14, 30] as $days)
                                <option value="{{ $days }}" @selected((string) old('duration_days') === (string) $days)>{{ $days }} {{ Str::plural('day', $days) }}</option>
                            @endforeach
                        </select>
                    </label>
                    <div class="field field-action"><span>&nbsp;</span><button class="btn" type="submit">List this item</button></div>
                </form>
            </section>

            <section class="listing-section" id="listings">
                <div class="results-head">
                    <div><h2>All listings</h2></div>
                    <span>{{ $listings->count() }} {{ Str::plural('item', $listings->count()) }}</span>
                </div>
                @forelse ($listings as $listing)
                    <article class="item">
                        <div class="thumb" aria-hidden="true">{{ strtoupper(substr($listing->title, 0, 1)) }}</div>
                        <div class="info">
                            <h3 class="title">{{ $listing->title }}</h3>
                            <p class="description">{{ $listing->description }}</p>
                            <p class="meta-line">{{ ucfirst($listing->category) }} <span aria-hidden="true">·</span> {{ ucfirst(str_replace('_', ' ', $listing->condition)) }}</p>
                            <p class="seller">Listed by <strong>{{ $listing->user?->name ?? 'Unknown seller' }}</strong></p>
                        </div>
                        <div class="side">
                            <span class="price">€{{ number_format((float) $listing->starting_price, 2) }}</span>
                            <span class="price-caption">Starting bid</span>
                        </div>
                    </article>
                @empty
                    <div class="empty"><h3>No listings yet</h3><p>Be the first to put something up for auction.</p></div>
                @endforelse
            </section>
        @else
            <section class="auth-grid">
                <div class="auth-panel">
                    <h1>Register</h1>
                    <p>Create an account to list items.</p>
                    <form class="auth-form" action="/register" method="POST">
                        @csrf
                        <label class="field">Your name<input name="name" type="text" placeholder="Name" value="{{ old('name') }}" required></label>
                        <label class="field">Email address<input name="email" type="email" placeholder="you@example.com" value="{{ old('email') }}" required></label>
                        <label class="field">Password<input name="password" type="password" placeholder="8-20 characters" required></label>
                        <button class="btn" type="submit">Create account</button>
                    </form>
                </div>
                <div class="auth-panel signin-panel">
                    <h2>Sign in</h2>
                    <form class="auth-form" action="/login" method="POST">
                        @csrf
                        <label class="field">Name<input name="loginname" type="text" placeholder="Name" required></label>
                        <label class="field">Password<input name="loginpassword" type="password" placeholder="Password" required></label>
                        <button class="btn btn-secondary" type="submit">Sign in</button>
                    </form>
                </div>
            </section>
        @endauth
    </main>
</body>
</html>
