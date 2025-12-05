<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('rfid-access', function ($user) {
    return true; // Allow all authenticated users to listen
});

