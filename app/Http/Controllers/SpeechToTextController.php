<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class SpeechToTextController extends Controller
{
    public function transcribeAudio(Request $request)
    {
        if (!$request->hasFile('audio')) {
            return response()->json(['error' => 'No audio file provided'], 400);
        }

        // Save uploaded audio file to storage
        $audioFile = $request->file('audio');
        $filePath = $audioFile->storeAs('audios', 'recorded_audio_' . time() . '.' . $audioFile->getClientOriginalExtension());

        // Use OpenAI Whisper API to transcribe audio
        $apiKey = config('services.openai.api_key');
        $response = Http::withHeaders([
            'Authorization' => "Bearer {$apiKey}",
        ])->attach('file', Storage::get($filePath), basename($filePath))
          ->post('https://api.openai.com/v1/audio/transcriptions', [
              'model' => 'whisper-1',
              'language' => 'en', // Specify the language if needed
          ]);

        // Optionally, delete the audio file after transcription
        Storage::delete($filePath);

        if ($response->successful()) {
            $transcription = $response->json('text');
            return response()->json(['text' => $transcription]);
        }

        return response()->json(['error' => 'Transcription failed'], 500);
    }
}
