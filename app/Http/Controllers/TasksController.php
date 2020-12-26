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
/* 
        // getでtasksにアクセスされた場合の一覧表示処理
       $tasks = Task::all();
        
        return view('tasks.index', [
            'tasks' => $tasks
            ]);
*/

        $data = [];
        // ユーザがログインしているならば
        if (\Auth::check()) {
            $user = \Auth::user();
            $tasks = $user->tasks()->orderBy('created_at', 'desc')->paginate(10);
            
            $data = [
                'user' => $user,
                'tasks' => $tasks
            ];
        }
        return view('welcome', $data);
    }

    /**
     * Show the form for creating a new resource.
     * getでtasks/create にアクセスされた場合の新規登録画面表示処理
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tasks = new Task;
        
        // ログインしているならば
        if (\Auth::check()) {
            return view('tasks.create', [
                'tasks' => $tasks
                ]);
        } else {
            return redirect('/');
        }
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
            'status'  => 'required|max:10', 
            'content' => 'required|max:191'
        ]);
            
        //$task = new Task;
        //$task->status = $request->status;
        //$task->content = $request->content;
        //$task->save();
        
        $request->user()->tasks()->create([
            'content' => $request->content, 
            'status'  => $request->status
            ]);
        
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
        
        if(\Auth::id() === $task->user_id) {
            return view('tasks.show', ['task' => $task]);
        } else {
           return redirect('/'); 
        }
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
        
        if(\Auth::id() === $task->user_id) {
            return view('tasks.edit', [
                'task' => $task
                ]);
        } else {
            return redirect('/'); 
        }
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
        
        if(\Auth::id() === $task->user_id) {
            $task->delete();
            return redirect('/');
        } 
    }
}
