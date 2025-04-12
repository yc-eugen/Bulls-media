<?php

use App\Console\Commands\GoogleSheetWorkerCommand;
use Illuminate\Support\Facades\Schedule;

Schedule::command(GoogleSheetWorkerCommand::class, [1])->everyMinute();

