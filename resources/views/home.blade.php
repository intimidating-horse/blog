<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

@auth

    <p> you are logged in </p>
<form action="/logout" method="POST">
@csrf
<button>  log out  </button>
</form>

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <div style="border: 3px solid black;">
       <h2>create a new listing</h2>
        <form action="/createListing" method="POST">
            @csrf
            <input type="text" name="title" placeholder="Title" value="{{ old('title') }}" required>
            <textarea name="description" placeholder="Description" required>{{ old('description') }}</textarea>
            <label for="category">Category</label>
            <select id="category" name="category" required>
                <option value="" disabled @selected(old('category') === null)>Select category</option>
                <option value="electronics" @selected(old('category') === 'electronics')>Electronics</option>
                <option value="clothing" @selected(old('category') === 'clothing')>Clothing</option>
                <option value="books" @selected(old('category') === 'books')>Books</option>
                <option value="other" @selected(old('category') === 'other')>Other</option>
            </select>
            <label for="starting_price">Starting price (€)</label>
            <input id="starting_price" type="number" name="starting_price" placeholder="Starting price in euros" value="{{ old('starting_price') }}" min="0" step="0.01" required>
            <label for="condition">Condition</label>
            <select id="condition" name="condition" required>
                <option value="" disabled @selected(old('condition') === null)>Select condition</option>
                <option value="new" @selected(old('condition') === 'new')>New</option>
                <option value="like_new" @selected(old('condition') === 'like_new')>Like new</option>
                <option value="good" @selected(old('condition') === 'good')>In good condition</option>
                <option value="acceptable" @selected(old('condition') === 'acceptable')>In acceptable conditions</option>
                <option value="worn_out" @selected(old('condition') === 'worn_out')>Has given it all</option>
            </select>
            <button>save listing</button>
        </form>
    </div>
@else

    <div style="border: 3px solid black;">
        <h2>Register name(3 - 16 character) password (8 - 20) characters</h2>

        @if ($errors->any())
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <form action="/register" method="POST">
            @csrf
            <input name="name" type="text" placeholder="name" value="{{ old('name') }}">
            <input name="email" type="text" placeholder="email" value="{{ old('email') }}">
            <input name="password" type="password" placeholder="password">
            <button>Register</button>
        </form>
    </div>

    <div style="border: 3px solid black;">
        <h2>log in</h2>
        <form action="/login" method="POST">
            @csrf
            <input name="loginname" type="text" placeholder="name">
            <input name="loginpassword" type="password" placeholder="password">
            <button>Log in</button>
        </form>
    </div>

@endauth



</body>
</html>
