<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Mail;

class ReminderController extends Controller
{
    public function sendEmailReminder($id, $dueInDay = 7)
    {
        $user = User::findOrFail($id);

        $tasks = $user->tasks()->dueInDays($dueInDay)->get();

        $data = [
            'user' => $user,
            'dueInDay' => $dueInDay,
            'tasks' => $tasks,
        ];

        Mail::send('emails.reminder', $data, function ($m) use ($user) {
            $m->from('no-reply@todolog.app', 'todolog Application');

            $m->to($user->email, $user->name)
                ->subject('태스크 만료 알림');
        });
    }
}
