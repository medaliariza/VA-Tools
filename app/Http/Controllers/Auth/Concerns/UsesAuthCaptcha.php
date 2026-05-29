<?php

namespace App\Http\Controllers\Auth\Concerns;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

trait UsesAuthCaptcha
{
    protected function prepareCaptcha(Request $request): array
    {
        $challenge = $request->session()->get('auth_captcha');

        if (! is_array($challenge) || ! isset($challenge['prompt'], $challenge['start_rotation'], $challenge['answer'])) {
            $challenge = $this->refreshCaptcha($request);
        }

        return $challenge;
    }

    protected function refreshCaptcha(Request $request): array
    {
        $rotations = [90, 180, 270];
        $startRotation = $rotations[array_rand($rotations)];
        $pieces = [
            ['label' => 'VA', 'shape' => 'triangle'],
            ['label' => 'SKU', 'shape' => 'arrow'],
            ['label' => 'OTP', 'shape' => 'compass'],
            ['label' => 'BOX', 'shape' => 'diamond'],
            ['label' => 'KEY', 'shape' => 'badge'],
            ['label' => 'GO', 'shape' => 'shield'],
        ];
        $piece = $pieces[array_rand($pieces)];

        $challenge = [
            'prompt' => 'Rotate the puzzle piece until it is upright.',
            'start_rotation' => $startRotation,
            'answer' => 0,
            'piece' => $piece,
        ];

        $request->session()->put('auth_captcha', $challenge);

        return $challenge;
    }

    protected function validateCaptcha(Request $request): void
    {
        $challenge = $this->prepareCaptcha($request);
        $answer = ((int) $request->input('captcha_rotation', -1)) % 360;

        $this->refreshCaptcha($request);

        if ($answer !== (int) $challenge['answer']) {
            throw ValidationException::withMessages([
                'captcha_rotation' => 'Rotate the CAPTCHA piece upright before continuing.',
            ]);
        }
    }
}
