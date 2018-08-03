<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Task;

use App\User;


class TasklistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Auth::check()は現在ログイン中か確認、Auth::user()はログインユーザーを取得
        $data = [];
        if (\Auth::check()) {
            $user = \Auth::user();
            
            // $user->tasks()とした場合、User.phpのtasks()メソッドと対応する
            // $tasks は変数
            // $user->tasks() はUser.phpの中にあるメソッド（関数的なもの)
            // コンテンツとユーザーIIDを日付順に降順で10ページづつ表示させる
            $tasks = $user->tasks()->orderBy('created_at', 'desc')->paginate(10);
            
            
            
            // $tasklistsというデータに'kadai-tasklit'という名前(ラベル)を付与している
            // ['kadai-tasklist' => $tasklists,] // 左と右は別物
            // $data['kadai-tasklist'] === $tasklists // この2つは同じ
            $data = [
                'user' => $user,
                'tasks' => $tasks,
            ];
            $data += $this->counts($user);
        
            return view('tasks.index', $data);
        }else {
            return view('welcome');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $task = new Task;
        
        return view('tasks.create',[
            'task' => $task,
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'status' => 'required|max:10',
            'content' => 'required|max:10',
            ]);
        
        $task = new Task;
        $task->user_id = auth()->user()->id;
        $task->status = $request->status;
        $task->content = $request->content;
        $task->save();
        
        return redirect('/');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $task = Task::find($id);
        
        if (\Auth::id() === $task->user_id) {
        return view('tasks.show', [
            'task' => $task,
            ]);
        }else{
            return redirect('/');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $task = Task::find($id);
        
        if (\Auth::id() === $task->user_id) {
        return view('tasks.edit',[
            'task' => $task,
            ]);
        }else{
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
       $this->validate($request, [
            'status' => 'required|max:10',  
            'content' => 'required|max:10',
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
        
        if (\Auth::id() === $task->user_id){
        $task->delete();
        
        return redirect('/');
        }else{
            return redirect('/');
        }
    }
}
