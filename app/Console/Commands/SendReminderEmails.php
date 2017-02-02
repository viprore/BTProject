<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;

class SendReminderEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminder:email
                        {user?}
                        {--due=7}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Reminder Emails';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $userId = $this->argument('user');

        $dueInDay = $this->option('due');

        if ($userId != null) {
            $users = collect([
                User::findOrFail($userId)
            ]);
        } else {
            $users = User::all();
        }

        foreach ($users as $user) {
            $tasks = $user->tasks()->dueInDays($dueInDay)->get();

            $data = [
                'user' => $user,
                'dueInDay' => $dueInDay,
                'tasks' => $tasks,
            ];

            \Mail::send('emails.reminder', $data, function ($m) use ($user) {
                $m->from('no-reply@todolog.app', 'todolog Application');

                $m->to($user->email, $user->name)
                    ->subject('태스크 만료 알림');
            });

            $this->info("$user->id 에게 태스크 알림 메일 전송");
        }

        $this->info($users->count() . ' 건의 태스크 알림 메일 전송 완료');
    }
}
