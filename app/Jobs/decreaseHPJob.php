<?php

namespace App\Jobs;

use App\Models\Tamagotchi;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class decreaseHPJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $tamagotchis = Tamagotchi::all();

        foreach ($tamagotchis as $tamagotchi) {
            $tamagotchi->energy = max(0, $tamagotchi->energy - 10);
            $tamagotchi->hunger = max(0, $tamagotchi->hunger - 10);
            $tamagotchi->health_points = max(0, $tamagotchi->health_points - 10);
            $tamagotchi->save();
        }

        Log::info('Tamagotchi HP decreased successfully at ' . now());
    }
}
