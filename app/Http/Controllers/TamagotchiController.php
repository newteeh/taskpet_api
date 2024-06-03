<?php

namespace App\Http\Controllers;

use App\Models\Tamagotchi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TamagotchiController extends Controller
{
    public function createTamagotchi(Request $request){
        $validatedData = $request->validate([
            'user_id' => 'required',
            'name' => 'required',
        ]);

        Tamagotchi::create($validatedData);
        return response()->json(['message' => 'Питомец успешно создан']);
    }
    public function getTamagotchiStatus(Request $request)
    {
        $user_id = $request->input('user_id');

        if ($user_id) {
            $user = User::find($user_id);
            $tamagotchi = Tamagotchi::where('user_id', $user_id)->first();

            if ($user && $tamagotchi) {
                return response()->json([
                    'tamagotchi' => $tamagotchi,
                    'points' => $user->points
                ]);
            } else {
                return response()->json(['error' => 'User or Tamagotchi not found'], 404);
            }
        } else {
            return response()->json(['error' => 'User ID not provided'], 400);
        }
    }
    public function updateTamagotchiStatus(Request $request)
    {
        $user_id = $request->input('user_id');

        if ($user_id) {
            $tamagotchi = Tamagotchi::where('user_id', $user_id)->first();

            if ($tamagotchi) {
                $points = $request->input('points');
                $update_type = $request->input('update_type');

                if ($points >= 10) {
                    $points -= 10;

                    // Обновляем данные тамагочи в зависимости от типа обновления
                    switch ($update_type) {
                        case 'energy':
                            $tamagotchi->energy += 10;
                            break;
                        case 'hunger':
                            $tamagotchi->hunger += 10;
                            break;
                        case 'health_points':
                            $tamagotchi->health_points += 10;
                            break;
                        case 'healAll':
                            $tamagotchi->energy += 10;
                            $tamagotchi->hunger += 10;
                            $tamagotchi->health_points += 10;
                            break;
                        default:
                            return response()->json(['error' => 'Invalid update type'], 400);
                    }

                    // Сохраняем обновленные данные тамагочи и количество баллов
                    $tamagotchi->save();

                    $user = User::findOrFail($user_id);
                    $user->points = $points;

                    $user->save();

                    return response()->json(['message' => 'Tamagotchi status updated successfully', 'points' => $points]);
                } else {
                    return response()->json(['error' => 'Not enough points'], 400);
                }
            } else {
                return response()->json(['error' => 'Tamagotchi not found'], 404);
            }
        } else {
            return response()->json(['error' => 'User ID not provided'], 400);
        }
    }








    public static function decreaseTamagotchiHP()
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
