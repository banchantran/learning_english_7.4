<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TextSpeechService
{
    public function getAudio($text, $languageType = 1, $voiceSpeed = 0)
    {
        $voiceId = config('config.voice_type')[$languageType];

        $text = addslashes($text);
        $post_data = [
            'text' => $text,
            'voiceService' => 'servicebin',
            'voiceID' => $voiceId,
            'voiceSpeed' => $voiceSpeed,
        ];

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://ttsfree.com/api/v1/tts",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($post_data),
            CURLOPT_HTTPHEADER => array(
                "apikey: " . config('app.ttsfree_api_key'),
                "content-type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            Log::error('Get voice failed: ' . json_encode($post_data));

            return null;
        } else {

            $data = json_decode($response, true);
            $wave_mp3 = base64_decode($data['audioData']);

            return $wave_mp3;
        }

    }

    public function saveAudio($text, $categoryId, $lessonId, $languageType = 1)
    {
        $audio = $this->getAudio($text, $languageType);

        if ($audio) {
            $fileName = trim($text) . '_' . time() . '.mp3';
            $pathAudio = config('app.path_audio') . '/user_' . Auth::user()->id . '/category_' . $categoryId . '/lesson_' . $lessonId . '/' . $fileName;
            Storage::put($pathAudio, $audio);

            return ['fileName' => $fileName, 'filePath' => Str::replace('public/', 'storage/', $pathAudio)];
        }

        return [];
    }
}
