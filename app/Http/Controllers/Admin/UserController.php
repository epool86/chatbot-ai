<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $status = isset($_GET['status']) ? $_GET['status'] : null;
        $search = isset($_GET['search']) ? $_GET['search'] : null;

        $users = User::with('department');

        if($search !== null && $search != ''){

            $users->where(function($query) use ($search){
                $query->where('name', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%')
                    ->orWhereHas('department', function($query2) use ($search){
                        $query2->where('name', 'like', '%'.$search.'%');
                    });
            });

        }


        if($status !== null && $status != ''){
            $users->where('status', $status);
        }

        $users = $users->paginate(3);

        return view('admin.user_index', compact('users', 'status', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = new User;
        return view('admin.user_form', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:5|max:50',
            'phone' => 'nullable',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'password_confirmation' => 'required|min:8',
            'status' => 'required|in:0,1',
        ],[
            'name.required' => 'Ruangan nama wajib',
            'name.min' => 'Paling kurang 5 huruf',
        ]);

        $user = new User;

        //$user->fill($request->except(['status','password']));
        //$user->status = 1;
        //$user->password = bcrypt($request['password']);
        //$user->save();

        $user->name = $request['name'];
        $user->email = $request['email'];
        $user->department_id = $request['department_id'];
        $user->password = bcrypt($request['password']);
        $user->status = $request['status'];

        $user->save();

        return redirect()->route('user.index')
                        ->with('message', 'New user successfully created!');
        //return redirect()->route('user.show', $user->id);
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::find($id);
        return view('admin.user_form', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'name' => 'required|min:5|max:50',
            'phone' => 'nullable',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'nullable|min:8|confirmed',
            'password_confirmation' => 'nullable|min:8',
            'status' => 'required|in:0,1',
        ]);

        $user = User::find($id);

        $user->name = $request['name'];
        $user->email = $request['email'];
        $user->department_id = $request['department_id'];
        if($request['password']){
            $user->password = bcrypt($request['password']);
        }
        $user->status = $request['status'];

        $user->save();

        return redirect()->route('user.index')
                        ->with('message', 'User details has been updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);
        $user->delete();

        return redirect()->route('user.index')
                        ->with('message', 'User has been deleted!');

    }
}
