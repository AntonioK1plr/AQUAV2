<?php
use Illuminate\Support\Facades\Schedule;
Schedule::command('aquarium:expire-orders')->everyMinute();
