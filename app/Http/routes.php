<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

use App\Task;
use Illuminate\Http\Request;

Route::group(['middleware' => ['web']], function () {

    /**
     * Show index.html page from file
     */
    Route::get('/grocery_list', function () {
        $index = file_get_contents('index.html');
        return $index;
    });

    /**
     * Show Task Dashboard
     */
    Route::get('/grocery_list', function () {
        return view('tasks', [
            'tasks' => Task::orderBy('created_at', 'asc')->get()
        ]);
    });


    /**
     * Add New Task
     */
    Route::post('/task', function (Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return redirect('/grocery_list')
                ->withInput()
                ->withErrors($validator);
        }

        $task = new Task;
        $task->name = $request->name;
        $task->save();

        return redirect('/grocery_list');
    });

    /**
     * Delete Task
     */
    Route::delete('/task/{id}', function ($id) {
        Task::findOrFail($id)->delete();

        return redirect('/grocery_list');
    });
});
