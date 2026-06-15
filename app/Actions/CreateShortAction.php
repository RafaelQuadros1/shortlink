<?php

namespace App\Actions;

use App\Models\Short;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CreateShortAction
{
    public function execute(Request $request, array $validated): Short
    {
        $short = Short::create(
            array_merge($validated, [
                'user_id' => auth()->id(),
            ])
        );

        Log::channel('security')->info('Short link created', [
            'user_id' => auth()->id(),
            'short_id' => $short->id,
            'ip' => $request->ip(),
        ]);

        return $short;
    }
}
