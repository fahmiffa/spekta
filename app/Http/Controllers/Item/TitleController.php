<?php

namespace App\Http\Controllers\Item;

use App\Http\Controllers\Controller;

use App\Models\Title;
use App\Models\Document;
use Illuminate\Http\Request;

class TitleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $da = Title::all();
        $data = "Title";
        return view('master.formulir.title.index',compact('da','data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {        
        $doc = Document::all();
        $data = "Tambah Title";
        return view('master.formulir.title.create',compact('data','doc'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rule = [
            'name'      => 'required', 
            'document'  =>'required',                                             
            ];
        $message = [
                'required'      =>'Field harus diisi',                    
                'unique'        =>'Field nama sudah ada',                           
            ];
    

        $request->validate($rule,$message);            
        $title = new Title;            
        $title->name = $request->name;   
        $title->doc = $request->document;                      
        $title->save();
    
        toastr()->success('Tambah Data berhasil', ['timeOut' => 5000]);
        return redirect()->route('title.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Title $title)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Title $title)
    {
        $doc = Document::all();
        $data = "Edit Title";
        return view('master.formulir.title.create',compact('data','doc','title'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Title $title)
    {
        $rule = [
            'name'      => 'required', 
            'document'  =>'required',                                             
            ];
        $message = [
                'required'      =>'Field harus diisi',                    
                'unique'        =>'Field nama sudah ada',                           
            ];
    

        $request->validate($rule,$message);                     
        $title->name = $request->name;   
        $title->doc = $request->document;                      
        $title->save();
    
        toastr()->success('Tambah Data berhasil', ['timeOut' => 5000]);
        return redirect()->route('title.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Title $title)
    {
        $title->delete();
        toastr()->success('Update Data berhasil', ['timeOut' => 5000]);
        return back();
    }
}
