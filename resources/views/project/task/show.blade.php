@extends('layouts.app')

@section('title')
    태스크 정보
@endsection

@section('content')

    <div class="col-md-8">

        <div class="form-group">
            <label for="project.task.name">소속 프로젝트</label>
            <div>
                <input type="text" class="form-control" name="project_name" value="{{ $task->project->name }}"
                       readonly="true"/>
            </div>
        </div>

        <div class="form-group">
            <label for="project.task.name">태스크 명</label>
            <div>
                <input type="text" class="form-control" name="name" value="{{ $task->name }}" readonly>
            </div>
        </div>

        <div class="form-group">
            <label for="설명">설명</label>
            <div>
                <textarea class="form-control" rows="3" name="description" readonly>{{ $task->description }}</textarea>
            </div>
        </div>

        <div class="form-group">
            <label>우선순위</label>
            <div>
                <input type="text" class="form-control" name="priority" value="{{ $task->priority }}"
                       readonly="true"/>
            </div>
        </div>
        <div class="form-group">
            <label>상태</label>
            <div>
                <input type="text" class="form-control" name="status" value="{{ $task->status }}"
                       readonly="true"/>
            </div>
        </div>

        <div class="form-group">
            <label for="기한">기한</label>
            <div>
                <input type="text" class="form-control" name="created_at" value="{{ $task->due_date }}"
                       readonly="true"/>
            </div>
        </div>
        <div class="form-group">
            <label for="생성일">생성일</label>
            <div>
                <input type="text" class="form-control" name="created_at" value="{{ $task->created_at }}"
                       readonly="true"/>
            </div>
        </div>
    </div>
@endsection