<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Estimated session length (minutes)
    |--------------------------------------------------------------------------
    |
    | Used to estimate how many people are present at any time. Each entry
    | is treated as one person "inside" for this many minutes after access.
    |
    */
    'estimated_session_minutes' => (int) env('GYM_ESTIMATED_SESSION_MINUTES', 60),

];
