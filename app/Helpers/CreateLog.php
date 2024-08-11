<?php

namespace App\Helpers;

use App\Models\Log;
use Illuminate\Support\Facades\Auth;

class CreateLog
{
    public static function createLog($activity)
    {
        $createLog = Log::create([
            'user_id' => Auth::user()->id,
            'activity' => $activity
        ]);

        return $createLog;
    }
}
