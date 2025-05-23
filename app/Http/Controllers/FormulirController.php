<?php

namespace App\Http\Controllers;

use App\Models\Formulir;
use App\Models\Document;
use App\Models\Title;
use App\Models\Header;
use App\Models\Item;
use App\Models\Sub;
use Illuminate\Http\Request;
use PDF;
use DB;
use QrCode;
use Intervention\Image\Facades\Image;
use Exception;

class FormulirController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $da = Document::where('type','formulir')->get();
        $data = "Formulir";
        return view('master.formulir.index',compact('da','data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = "Tambah formulir";
        return view('master.formulir.create',compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rule = [                   
            'name' => 'required',           
            'title'=> 'required'                                    
            ];

        $request->validate($rule);
 
        $item = new Document;        
        $item->name = $request->name; 
        $item->tag = $request->tag; 
        $item->titles = $request->title; 
        $item->type = 'formulir';    
        $item->save();

        toastr()->success('Tambah Data berhasil', ['timeOut' => 5000]);
        return redirect()->route('formulir.index');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {

            $title = Document::has('header')->where(DB::raw('md5(id)'),$id)->where('type','formulir')->first();
            if(!$title)
            {
                throw new Exception("Dokumen item header belum di set", 1);                
            }
            
            if($title->name == 'bak')
            {           
                $form = $title;
                $qrCode = base64_encode(QrCode::format('png')->size(100)->generate('hello qrcode'));
    
                $data = compact('form','qrCode');
                $preview = true;
    
                $pdf = PDF::loadView('doc.bak', $data)->setPaper('legal', 'potrait','preview');    
                return $pdf->stream();
            }
        
            $title = Document::has('footer')->where(DB::raw('md5(id)'),$id)->where('type','formulir')->first();
            if(!$title)
            {
                throw new Exception("Dokumen item footer belum di set", 1);                
            }

            $title = Document::has('title')->where(DB::raw('md5(id)'),$id)->where('type','formulir')->first();
            if(!$title)
            {
                throw new Exception("Dokumen item title belum di set", 1);                
            }     
            
            $form = $title;
            $qrCode = base64_encode(QrCode::format('png')->size(100)->generate('hello qrcode'));

            $data = compact('form','qrCode');

            $pdf = PDF::loadView('doc.formulir.index', $data)->setPaper('legal', 'potrait');    
            return $pdf->stream();
            return view('doc.formulir.index',$data);

            
        } catch (Exception $e) {
            toastr()->error($e->getMessage(), ['timeOut' => 5000]);
            return back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Formulir $formulir)
    {
        $data = "Edit Formulir";
        return view('master.formulir.create',compact('data','formulir'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Formulir $formulir)
    {
        $rule = [                   
            'name' => 'required', 
            'type'=> 'required',                                       
            ];

        $request->validate($rule);
 
        $item = $formulir;        
        $item->name = $request->name; 
        $item->tag = $request->tag; 
        $item->titles = $request->title; 
        $item->type = 'formulir';         
        $item->save();

        toastr()->success('Update Data berhasil', ['timeOut' => 5000]);
        return redirect()->route('formulir.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Formulir $formulir)
    {
        $header = Header::where('doc',$formulir->id)->first();
        if($header)
        {
            $header->delete();
        }

        $title = Title::where('doc',$formulir->id);
        if($title->exists())
        {
            foreach($title->get() as $item)
            {
                $items = Item::where('titles_id',$item->id);
                if($items->exists())
                {
                    foreach($items->get() as $sub)
                    {
                        $subs = Sub::where('items_id',$sub->id);
                        if($subs->exists())
                        {
                            $subs->delete();
                        }
                    }

                    $items->delete();
                }
            }
            $title->delete();
        }


        $formulir->delete();
        toastr()->success('Hapus Data berhasil', ['timeOut' => 5000]);
        return back();
    }
}
