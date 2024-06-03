<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\Task;
use App\Models\User;
use App\Models\UserAchievement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class TaskController extends Controller
{
    //
    public function getTasks(Request $request){
        $user_id = $request->user_id;
        $task_date = $request->task_date;
        return response()->json(Task::where('user_id', $user_id)->whereDate('task_date', $task_date)->get());
    }

    public function addTask(Request $request)
    {
        // Валидация данных
        $request->validate([
            'user_id' => 'required',
            'task_name' => 'required|string',
            'task_date' => 'required|date',
            'task_startTime' => 'required|date_format:H:i',
            'task_endTime' => 'required|date_format:H:i',
            'task_reminder' => 'required|string',
        ]);

        // Создание новой задачи
        $task = new Task();
        $task->user_id = $request->user_id;
        $task->task_name = $request->task_name;
        $task->task_date = $request->task_date;
        $task->task_startTime = $request->task_startTime;
        $task->task_endTime = $request->task_endTime;
        $task->task_reminder = $request->task_reminder;

        // Сохранение задачи
        $task->save();

        // Ответ в формате JSON
        return response()->json(['message' => 'Задача успешно добавлена'], 200);
    }

    // app/Http/Controllers/TaskController.php



    public function updateTaskStatus(Request $request)
    {
        $task = Task::find($request->task_id);
        if ($task) {
            // Если статус задачи не изменился, нет необходимости продолжать выполнение
            if ($task->task_status == $request->task_status) {
                return response()->json(['status' => 'success', 'message' => 'Task status already updated']);
            }

            $task->task_status = $request->task_status;

            // Если задача выполнена и баллы еще не начислены
            if ($request->task_status == 1 && !$task->points_awarded) {
                // Проверяем, что задача не была отменена ранее
                if ($task->points_awarded !== true) {
                    $task->points_awarded = true;

                    // Проверка, если это первая выполненная задача пользователя
                    $user_id = $request->input('user_id');
                    $user = User::find($user_id);

                    if ($user) {
                        $completedTasks = $user->tasks()->where('task_status', 1)->count();
                        if ($completedTasks === 1) {
                            // Присваиваем достижение за первое выполненное задание
                            $achievement = Achievement::where('name', 'Добро пожаловать')->first();
                            if ($achievement) {
                                $userAchievement = UserAchievement::where('user_id', $user->id)
                                    ->where('achievement_id', $achievement->id)
                                    ->first();

                                // Если запись уже существует, не создаем новую
                                if (!$userAchievement) {
                                    // Создаем новую запись, если не найдено существующих
                                    UserAchievement::create([
                                        'user_id' => $user->id,
                                        'achievement_id' => $achievement->id,
                                    ]);
                                }
                            }
                        }
                        // Добавляем пользователю 10 баллов за выполнение задачи
                        $user->points += 10;
                        $user->save();
                    }
                }
            }

            $task->save();
            return response()->json(['status' => 'success', 'message' => 'Task status updated successfully']);
        }
        return response()->json(['status' => 'error', 'message' => 'Task not found'], 404);
    }




}
