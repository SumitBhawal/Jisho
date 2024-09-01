<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WordMeaning;

class WordController extends Controller
{
    public function saveWord(Request $request)
    {
        // Save the word and its meaning to the database
        WordMeaning::create([
            'word' => $request->input('word'),
            'meaning' => $request->input('meaning'),
        ]);

        return redirect('/saved')->with('success', 'Word saved successfully!');
    }

    public function deleteWord ($word)
    {
        // Remove the word from the database
        WordMeaning::where('word', $word)->delete();

        return redirect('/deleted')->with('success', 'Word removed successfully!');
    }
    public function deleteMyWord ($word)
    {
        // Remove the word from the database
        WordMeaning::where('word', $word)->delete();

        return redirect()->back()->with('success', 'Word removed successfully!');
    }

    public function showSavedWords()
    {
        $wordMeanings = WordMeaning::all();
        return view('myWords', compact('wordMeanings'));
    }

    public function startMcqTest()
    {
        $words = WordMeaning::inRandomOrder()->take(4)->get();
    
        if ($words->count() < 4) {
            return redirect()->back()->with('error', 'Please add at least 4 words to start the test.');
        }
    
        session(['score' => 0, 'totalQuestions' => 0]);
    
        return $this->showNextQuestion(1, $words);
    }
    
    public function submitMcq(Request $request)
    {
        $questionNumber = $request->input('questionNumber');
        $selectedAnswer = $request->input('answer');
        $correctAnswer = $request->input('correctAnswer');
    
        $score = session('score', 0);
        $totalQuestions = session('totalQuestions', 0);
    
        if ($selectedAnswer === $correctAnswer) {
            session(['score' => $score + 1]);
        }
    
        session(['totalQuestions' => $totalQuestions + 1]);
    
        // Prepare the next question or end the test
        $nextQuestionNumber = $questionNumber + 1;
        $words = WordMeaning::inRandomOrder()->take(4)->get();
    
        if ($words->count() >= 4) {
            return $this->showNextQuestion($nextQuestionNumber, $words);
        } else {
            return redirect()->route('word.myWords')->with('score', session('score'))->with('totalQuestions', session('totalQuestions'));
        }
    }
    
    private function showNextQuestion($questionNumber, $words)
    {
        $correctWord = $words->random();
        $options = $words->pluck('meaning')->shuffle();
    
        $question = [
            'word' => $correctWord->word,
            'correctAnswer' => $correctWord->meaning,
            'options' => $options
        ];
    
        return view('mcqTest', compact('question', 'questionNumber'));
    }
    
    public function stopTest()
    {
        $score = session('score');
        $totalQuestions = session('totalQuestions');
    
        return redirect()->route('my.words')->with('score', $score)->with('totalQuestions', $totalQuestions);
    }    
    
}
