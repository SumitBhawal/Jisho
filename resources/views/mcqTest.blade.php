<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MCQ Test</title>
    <link rel="stylesheet" href="{{ asset('assets/css/mcqTest.css') }}">
</head>
<body>
    <h1>MCQ Test</h1>

    @if(session('error'))
        <p class="error">{{ session('error') }}</p>
    @endif

    @if(session('score'))
        <p>Your Score: {{ session('score') }}/{{ session('totalQuestions') }}</p>
    @endif

    @if(isset($question))
        <div class="mcq-test">
            <form action="{{ route('submitMcq') }}" method="POST">
                @csrf
                <p><strong>Question {{ $questionNumber }}:</strong> What is the meaning of "{{ $question['word'] }}"?</p>
                <input type="hidden" name="questionNumber" value="{{ $questionNumber }}">
                <input type="hidden" name="correctAnswer" value="{{ $question['correctAnswer'] }}">
                @foreach($question['options'] as $option)
                    <label>
                        <input type="radio" name="answer" value="{{ $option }}" required> {{ $option }}
                    </label><br>
                @endforeach
                <button type="submit">Submit Answer</button>
            </form>
            <form action="{{ route('stopTest') }}" method="POST">
                @csrf
                <button type="submit">Stop the Test</button>
            </form>
        </div>
    @else
        <p>No questions available. Please add more words to your collection.</p>
    @endif
</body>
</html>
