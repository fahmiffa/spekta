<?php

namespace App\Http\Controllers\Formulir;

use App\Http\Controllers\Controller;

use App\Models\Document;
use App\Models\Header;
use Illuminate\Http\Request;

class HeaderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $da = Header::all();
        $data = "Header";
        return view('master.formulir.header.index',compact('da','data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $da = Header::all();
        $doc = Document::all();
        $data = "Tambah Header";
        return view('master.formulir.header.create',compact('da','data','doc'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rule = [ 
            'document'  =>'required',     
            'item'      => 'required'                                      
            ];
        $message = [
                'required'      =>'Field harus diisi', 
                'item.required' =>'Field Item harus diisi',                       
                'unique'        =>'Field nama sudah ada',                           
            ];
    

        $request->validate($rule,$message);            
        $header = new Header;            
        $header->doc = $request->document;              
        $header->item = json_encode($request->item);              
        $header->save();
    
        toastr()->success('Tambah Data berhasil', ['timeOut' => 5000]);
        return redirect()->route('header.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Header $header)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Header $header)
    {
        $da = Header::all();
        $doc = Document::all();
        $data = "Edit Header";
        return view('master.formulir.header.create',compact('da','data','doc','header'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Header $header)
    {
        $rule = [
            'document'  => 'required',     
            'item'      => 'required'                                      
            ];
        $message = [
                'required'      =>'Field harus diisi', 
                'item.required' =>'Field Item harus diisi',                       
                'unique'        =>'Field nama sudah ada',                           
            ];
    

        $request->validate($rule,$message);                      
        $header->doc = $request->document;              
        $header->item = json_encode($request->item);              
        $header->save();
    
        toastr()->success('Update Data berhasil', ['timeOut' => 5000]);
        return redirect()->route('header.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Header $header)
    {
        $header->delete();
        toastr()->success('Update Data berhasil', ['timeOut' => 5000]);
        return back();
    }
}
