<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Japanese Text Tokenizer</title>
    <link rel="stylesheet" href="{{ asset('assets/css/search.css') }}">
</head>
<body>
    <div class="container">
        <h1>Japanese to English Translator</h1>
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
            <div class="tokens">
            <h2>Here's the breakdown of the meaning of your sentence:</h2>
            <ul>
                @foreach($tokens as $word => $meaning)
                    <li>
                        <strong>{{ $word }}:</strong> {{ $meaning }}
                        
                        @if(!$wordMeanings->contains('word', $word))
                            <!-- Show Save Word button if the word doesn't exist -->
                            <form action="{{ route('save.word') }}" method="POST" style="display:inline;">
                                @csrf
                                <input type="hidden" name="word" value="{{ $word }}">
                                <input type="hidden" name="meaning" value="{{ $meaning }}">
                                <button type="submit">Save Word</button>
                            </form>
                        @else
                            <!-- Show Remove button if the word already exists -->
                            <form action="{{ route('delete.word', ['word' => $word]) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="remove-btn">Remove</button>
                            </form>
                        @endif
                    </li>
                @endforeach
            </ul>
            </div>
        @elseif(isset($tokens))
        <p class="no-tokens">No tokens found.</p>
        @endif
        <div class="actions">
            <a href="{{ route('my.words') }}" class="btn-show-words">Show My Words</a>
        </div>
    </div>
</body>
</html>
