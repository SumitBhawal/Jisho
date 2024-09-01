<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Word Meanings</title>
    <link rel="stylesheet" href="{{ asset('assets/css/myWords.css') }}">
</head>
<body>
    <h1>Word Meanings</h1>
    <div class="word-meanings">
        @if($wordMeanings->isEmpty())
            <p>No words available.</p>
        @else
            <ul>
                @foreach($wordMeanings as $wordMeaning)
                    <li>
                        <strong>{{ $wordMeaning->word }}</strong>: {{ $wordMeaning->meaning }}
                        <form action="{{ route('delete.my.word', $wordMeaning->word) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="remove-btn">Remove</button>
                            </form>
                    </li>
                @endforeach
            </ul>
        @endif
        <div class="buttons">
            <div class="actions">
                <a href="{{ route('home') }}" class="btn-show-words">Back to Search</a>
            </div>
            <div class="actions">
                <a href="{{ route('mcq.test') }}" class="btn-show-words">Play a MCQ Test</a>
            </div>
        </div>
    </div>
</body>
</html>
