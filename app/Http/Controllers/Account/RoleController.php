<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Models\Permission;
use DB;
use App\Models\User;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('IsPermission:master');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $da = Role::all();
        $data = "Role";
        return view('master.account.role.index',compact('da','data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = "Tambah Role";
        $per = Permission::all();
        return view('master.account.role.create',compact('data','per'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rule = [            
            'name' => 'required|unique:roles,name',
            'kode' =>'required'                             
            ];

        $request->validate($rule);


        $permission = null;
        $permit = $request->permit;

        if($permit)
        {            
            foreach($permit as $item => $key)
            {
                $per = Permission::where(DB::raw('md5(id)'),$item)->first();
                if($per)
                {
                    $par []= $per->id;
                }

            }            
        }

        $permission = implode(', ',$par);

 
        $item = new Role;        
        $item->name = $request->name;
        $item->kode = $request->kode;
        $item->permission = $permission;
        $item->save();

        return redirect()->route('role.index');
    }

    
    public function permit(Request $request, $id)
    {
        $role = Role::where(DB::raw('md5(id)'),$id)->first();
        $permit = $request->permit;

        if($permit && $role)
        {            
            foreach($permit as $item => $key)
            {
                $per = Permission::where(DB::raw('md5(id)'),$item)->first();
                if($per)
                {
                    $permits = Permit::where(DB::raw('md5(permission_id)'),$item)->where('role_id',$role->id)->first();
                    if($permits)
                    {             
                        $permits->role_id = $role->id;
                        $permits->permission_id = $per->id;
                        $permits->save();
                    }
                    else
                    {
                        $permit = New Permit;
                        $permit->role_id = $role->id;
                        $permit->permission_id = $per->id;
                        $permit->save();
                    }
                }

            }            
        }

       return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {        
        $data = "Permission";
        $per = Permission::all();
        return view('master.account.role.permit',compact('data','role','per'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        $per = Permission::all();
        $data = "Edit Role";
        return view('master.account.role.create',compact('data','role','per'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $rule = [            
            'name' => 'required|unique:roles,name,'.$role->id, 
            'kode' =>'required'                                         
            ];

        $request->validate($rule);

        $permission = null;
        $permit = $request->permit;

        if($permit)
        {            
            foreach($permit as $item => $key)
            {
                $per = Permission::where(DB::raw('md5(id)'),$item)->first();
                if($per)
                {
                    $par []= $per->id;
                }

            }            

            $permission = implode(', ',$par);
        }

        $item = $role;
        $item->permission = $permission;   
        $item->kode = $request->kode;
        $item->name = $request->name;
        $item->save();

        return redirect()->route('role.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        $role->delete();
        return back();
    }
}
