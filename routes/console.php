<?php

use App\Console\Commands\FetchNews;
use Illuminate\Support\Facades\Schedule;

Schedule::command(FetchNews::class)->everyFifteenMinutes();
