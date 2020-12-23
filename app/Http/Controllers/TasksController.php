<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Task;    // 追加

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // getでtasksにアクセスされた場合の一覧表示処理
        $tasks = Task::all();
        
        return view('tasks.index', [
            'tasks' => $tasks
            ]);
    }

    /**
     * Show the form for creating a new resource.
     * getでtasks/create にアクセスされた場合の新規登録画面表示処理
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tasks = new Task;
        
        return view('tasks.create', [
            'tasks' => $tasks
            ]);
    }

    /**
     * Store a newly created resource in storage.
     * postでmessages/にアクセスされた場合の「新規登録処理」
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // バリデーションチェック
        $this->validate($request, [
            'status' => 'required|max:10',
            'content' => 'required|max:191'
            ]);
            
        $task = new Task;
        $task->status = $request->status;
        $task->content = $request->content;
        $task->save();
        
        return redirect('/');
    }

    /**
     * Display the specified resource.
     * getでtasks/idにアクセスされた場合の「取得表示処理」
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $task = Task::find($id);
        
        return view('tasks.show', [
            'task' => $task
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     * getでtasks/id/edit　にアクセスされた場合の「更新画面表示処理」
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $task = Task::find($id);
        
        return view('tasks.edit', [
            'task' => $task
            ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // バリデーションチェック
        $this->validate($request, [
            'status' =>'required|max:10',
            'content' => 'required|max:191',
        ]);
        
        
        $task = Task::find($id);
        $task->status = $request->status;
        $task->content = $request->content;
        $task->save();

        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task = Task::find($id);
        $task->delete();

        return redirect('/');
    }
}
