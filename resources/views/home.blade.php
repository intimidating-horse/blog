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

@endauth



</body>
</html>
