<?php

namespace App\Http\Controllers\Formulir;

use App\Http\Controllers\Controller;

use App\Models\Item;
use App\Models\Sub;
use Illuminate\Http\Request;

class SubController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $da = Sub::all();
        $data = "Sub";
        return view('master.formulir.sub.index',compact('da','data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $da = Item::all();
        $data = "Tambah Sub";
        return view('master.formulir.sub.create',compact('da','data'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rule = [
            'name'      => 'required|unique:subs,name', 
            'item'     =>'required',                                             
            ];
        $message = [
                'required'      =>'Field harus diisi',                    
                'unique'        =>'Field nama sudah ada',                           
            ];
    

        $request->validate($rule,$message);            
   
        $sub = new Sub;            
        $sub->name = $request->name;   
        $sub->items_id = $request->item;                      
        $sub->save();
    
        toastr()->success('Tambah Data berhasil', ['timeOut' => 5000]);
        return redirect()->route('sub.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Sub $sub)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sub $sub)
    {
        $da = Item::all();
        $data = "Tambah Sub";
        return view('master.formulir.sub.create',compact('da','data','sub'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sub $sub)
    {
        $rule = [
            'name'      => 'required|unique:subs,name,'.$sub->id, 
            'item'     =>'required',                                             
            ];
        $message = [
                'required'      =>'Field harus diisi',                    
                'unique'        =>'Field nama sudah ada',                           
            ];
    

        $request->validate($rule,$message);            
       
        $sub->name = $request->name;   
        $sub->items_id = $request->item;                      
        $sub->save();
    
        toastr()->success('Tambah Data berhasil', ['timeOut' => 5000]);
        return redirect()->route('sub.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sub $sub)
    {
        $sub->delete();
        toastr()->success('Hapus Data berhasil', ['timeOut' => 5000]);
        return back();
    }
}
