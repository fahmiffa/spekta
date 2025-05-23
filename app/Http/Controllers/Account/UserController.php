<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User; 
use App\Models\Role;
use App\Models\Setting;
use DB;


class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('IsPermission:master');
    }

    public function shst()
    {
        $val = Setting::first();
        $data = "Retribusi";
        return view('master.shst.create',compact('data','val'));
    }


    public function drop(Request $request, $id)
    {

        $user = User::where(DB::raw('md5(id)'), $id)->first();
        $user->status = $user->status == 1 ? 0 : 1;
        $user->note = $request->noted;
        $user->save();
                
        toastr()->success('Update Berhasil', ['timeOut' => 5000]);
        return redirect()->route('user.index');

    }


    public function shsts(Request $request)
    {
        $val = Setting::first();
        $val->shst = $request->value;
        $val->timer = $request->timer;
        $val->save();

        toastr()->success('Setting Data berhasil', ['timeOut' => 5000]);
        return back();
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $da = User::all();
        $data = "User";
        return view('master.account.user.index',compact('da','data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = "Tambah User";
        $role = Role::all();
        return view('master.account.user.create',compact('data','role'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rule = [            
            'name' => 'required|unique:users,name',     
            'password' => 'required|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@!$#%&? "]).+$/|min:12',
            'email'=>'required|unique:users,email'                        
            ];

        $messages   =   [
            'required'  =>  ':attribute harus di isi',                        
            'unique'    =>  ':attribute sudah ada',         
            'required'  =>  'Password harus di isi',         
            'min'       =>  'Password harus minimal 12 digit',         
            'regex'     =>  'Password harus kombinasi Huruf besar kecil, angka dan simbol'                                                                                      
        ];

        $request->validate($rule,$messages);
 
        $item = new User;        
        $item->name = $request->name;
        $item->email = $request->email;
        $item->role = $request->role;
        $item->password = bcrypt($request->password);
        $item->save();

        return redirect()->route('user.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {      
        $data = "Edit User";
        $role = Role::all();
        return view('master.account.user.create',compact('data','user','role'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $rule = [            
            'name'     => 'required|unique:users,name,'.$user->id,     
            'email'    => 'required|unique:users,email,'.$user->id,    
            'nip'      => 'required_if:role,5'            
            ];

        $messages   =   [
            'name.required'      =>  'Nama harus di isi',                        
            'email.required'     =>  'Email harus di isi',                                                                                 
            'email.unique'       =>  'Email sudah ada',         
            'nip.required_if'    =>  ':attribute harus di isi',
            'password.required'  =>  'Password harus di isi',         
            'password.min'       =>  'Password harus minimal 12 digit',         
            'password.regex'     =>  'Password harus kombinasi Huruf besar kecil, angka dan simbol'                                                                                             
        ];

        if($request->password)
        {
            $rule = array_merge($rule,['password' => 'required|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@!$#%&? "]).+$/|min:12']);
        }

        $request->validate($rule,$messages);
 
        $item = $user;        
        $item->name = $request->name;
        $item->email = $request->email;
        $item->role = $request->role;
        $item->nip = $request->nip;
        if($request->password)
        {
            $item->password = bcrypt($request->password);
        }
        $item->save();

        return redirect()->route('user.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return back();
    }
}
