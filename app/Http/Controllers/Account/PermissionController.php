<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;

use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
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
        $da = Permission::all();
        $data = "Permisson";
        return view('master.account.permission.index',compact('da','data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = "Tambah Permission";
        return view('master.account.permission.create',compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rule = [            
            'name' => 'required|unique:permissions,name',    
            'parameter' => 'required|unique:permissions,parameter',                             
            ];

        $request->validate($rule);
 
        $item = new Permission;        
        $item->name = $request->name;
        $item->parameter = $request->parameter;
        $item->save();

        return redirect()->route('permission.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Permission $permission)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permission $permission)
    {
        $data = "Edit Permisson";
        return view('master.account.permission.create',compact('data','permission'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Permission $permission)
    {
        $rule = [            
            'name' => 'required|unique:permissions,name,'.$permission->id,  
            'parameter' => 'required|unique:permissions,parameter,'.$permission->id,                             
            ];

        $request->validate($rule);
 
        $item = $permission;        
        $item->name = $request->name;
        $item->parameter = $request->parameter;
        $item->save();

        return redirect()->route('permission.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {
        $permission->delete();
        return back();
    }
}
