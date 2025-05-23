<?php

namespace App\Http\Controllers\Item;

use App\Http\Controllers\Controller;

use App\Models\Document;
use App\Models\Footer;
use Illuminate\Http\Request;

class FooterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $da = Footer::all();
        $data = "Footer";
        return view('master.formulir.footer.index',compact('da','data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {        
        $doc = Document::all();
        $data = "Tambah Footer";
        return view('master.formulir.footer.create',compact('data','doc'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rule = [         
            'document'  =>'required',                                  
            ];
        $message = [
                'required'      =>'Field harus diisi',                                  
            ];
    

        $request->validate($rule,$message);            
        $footer = new Footer;                
        $footer->doc = $request->document;              
        $footer->item = $request->content;              
        $footer->save();
    
        toastr()->success('Tambah Data berhasil', ['timeOut' => 5000]);
        return redirect()->route('footer.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Footer $footer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Footer $footer)
    {
        $doc = Document::all();
        $data = "Editor Footer";
        return view('master.formulir.footer.create',compact('data','doc','footer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Footer $footer)
    {
        $rule = [         
            'document'  =>'required',                                  
            ];
        $message = [
                'required'      =>'Field harus diisi',                                  
            ];
    

        $request->validate($rule,$message);                       
        $footer->doc = $request->document;              
        $footer->item = $request->content;              
        $footer->save();
    
        toastr()->success('Update Data berhasil', ['timeOut' => 5000]);
        return redirect()->route('footer.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Footer $footer)
    {
        //
    }
}
