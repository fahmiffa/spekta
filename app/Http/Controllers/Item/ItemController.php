<?php

namespace App\Http\Controllers\Item;

use App\Http\Controllers\Controller;

use App\Models\Item;
use Illuminate\Http\Request;
use App\Models\Title;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $da = Item::all();
        $title = Title::all();
        $data = "Item";
        return view('master.formulir.item.index',compact('da','data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $da = Title::all();
        $data = "Tambah Item";
        return view('master.formulir.item.create',compact('data','da'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rule = [
            'name'      => 'required|unique:items,name', 
            'title'     =>'required',                                             
            ];
        $message = [
                'required'      =>'Field harus diisi',                    
                'unique'        =>'Field nama sudah ada',                           
            ];
    

        $request->validate($rule,$message);            
        $item = new Item;            
        $item->name = $request->name;   
        $item->titles_id = $request->title;                      
        $item->save();
    
        toastr()->success('Tambah Data berhasil', ['timeOut' => 5000]);
        return redirect()->route('item.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Item $item)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item)
    {
        $da = Title::all();
        $data = "Edit Item";
        return view('master.formulir.item.create',compact('data','da','item'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Item $item)
    {
        $rule = [
            'name'      => 'required|unique:items,name,'.$item->id, 
            'title'     =>'required',                                             
            ];
        $message = [
                'required'      =>'Field harus diisi',                    
                'unique'        =>'Field nama sudah ada',                           
            ];
    

        $request->validate($rule,$message);                 
        $item->name = $request->name;   
        $item->titles_id = $request->title;                      
        $item->save();
    
        toastr()->success('Update Data berhasil', ['timeOut' => 5000]);
        return redirect()->route('item.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        $item->delete();
        toastr()->success('Hapus Data berhasil', ['timeOut' => 5000]);
        return back();
    }
}
