<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\UserAchievement;
use Illuminate\Http\Request;

class AchievementController extends Controller
{
    public function getAchievements($userId)
    {
        $achievements = Achievement::all();
        $userAchievements = UserAchievement::where('user_id', $userId)->pluck('achievement_id')->toArray();

        foreach ($achievements as $achievement) {
            $achievement->achieved = in_array($achievement->id, $userAchievements);
        }

        return response()->json($achievements);
    }
}
