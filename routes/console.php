<?php

use App\Console\Commands\ScrapSymfonyBlogCommand;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Schedule::command(ScrapSymfonyBlogCommand::class)->everyMinute();
