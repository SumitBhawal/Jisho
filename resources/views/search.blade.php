<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Japanese Text Tokenizer</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>
    <div class="container">
        <h1>Japanese Text Tokenizer</h1>
        <form action="{{ route('tokenize') }}" method="POST">
            @csrf
            <label for="japaneseText">Enter Japanese Text:</label>
            <input type="text" id="japaneseText" name="text" required>
            <button type="submit">Search</button>
        </form>

        @if(isset($errorMessage))
            <p class="error">{{ $errorMessage }}</p>
        @endif

        @if(isset($tokens) && !empty($tokens))
            <h2>Tokenized Text:</h2>
            <div class="tokens">
                <ul>
                    @foreach($tokens as $token)
                        <li>{{ $token['surface_form'] }}</li>
                    @endforeach
                </ul>
            </div>
        @elseif(isset($tokens))
            <p class="no-tokens">No tokens found.</p>
        @endif
    </div>
</body>
</html>
