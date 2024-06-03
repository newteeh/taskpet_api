<?php

namespace App\Http\Controllers;

use App\Mail\ConfirmEmail;
use App\Models\verifiedUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use MongoDB\Driver\Session;

class ConfirmationCodeController extends Controller
{
    public function sendConfirmationCode(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email'
        ]);



        $confirmationCode = mt_rand(100000, 999999); // Генерация случайного кода

        $array = [
            'code' => $confirmationCode,
            'email' => $validatedData['email']
        ];

        // Сохранение кода в базе данных или временном хранилище (например, Redis)
        verifiedUser::create($array);
        // Отправка кода на указанный email
        Mail::to($validatedData['email'])->send(new ConfirmEmail($confirmationCode));


        return response()->json(['message' => 'Код подтверждения отправлен на указанный email']);
    }
//
    public function ConfirmEmail(Request $request){
        $validatedData = $request->validate([
            'email' => 'required',
            'code' => 'required'
        ]);
        $userCode = $validatedData['code'];

        $code = verifiedUser::where('code', $validatedData['code'])->latest()->first();

//         Возвращаем значения пользовательского и куки кодов для проверки
//        return response()->json(['userCode' => $userCode, 'cookieCode' => $code['code'] ]);

        // Проверяем, совпадают ли коды
        if ($code === null || $userCode !== $code['code']) {
            return response()->json(['message' => 'Неправильный код подтверждения'], 422);
        }

        verifiedUser::where('email', $validatedData['email'])->delete();
        // Верный код подтверждения
        return response()->json(['message' => 'Код подтверждения верный']);

    }
}
