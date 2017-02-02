<?php

namespace App\Http\Controllers;

use App\Project;
use App\Task;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Redis;

class WelcomeController extends Controller
{
    /**
     * WelcomeController constructor.
     */
    public function __construct()
    {
        //$this->middleware('web');
    }

    public function index()
    {
        // 3 사용자, 프로젝트, 태스크 수 가져오기. 아직 모델을 생성하지 않았으므로 0으로 설정
        $drv = \Config::get('cache.default');
        if ($drv === 'redis') {
            $userCount = Redis::get('user:count');
            $projectCount = Redis::get('project:count');
            $taskCount = Redis::get('task:count');
        } else {
            $userCount = User::count();
            $projectCount = Project::count();
            $taskCount = Task::count();
        }



        $total = [ 'user' => $userCount,
            'project' => $projectCount,
            'task' => $taskCount,
        ];

        return view('welcome')->with('total', $total);
    }
}
