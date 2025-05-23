<?php

namespace App\Http\Controllers\Formulir;

use App\Http\Controllers\Controller;

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

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $da = Document::all();
        $data = "Dokumen";
        return view('master.formulir.document.index',compact('da','data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {        
        $data = "Tambah Dokumen";
        return view('master.formulir.document.create',compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rule = [            
            'name' => 'required|unique:documents,name', 
            'title'=> 'required'                                    
            ];

        $request->validate($rule);
 
        $item = new Document;        
        $item->name = $request->name; 
        $item->titles = $request->title;  
        $item->type = $request->type;        
        $item->save();

        toastr()->success('Tambah Data berhasil', ['timeOut' => 5000]);
        return redirect()->route('document.index');
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
            $pdf = PDF::loadView('pdf', $data)->setPaper('a4', 'potrait');    
            return $pdf->stream();

            
        } catch (Exception $e) {
            toastr()->error($e->getMessage(), ['timeOut' => 5000]);
            return back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Document $document)
    {
        $data = "Edit Dokumen";
        return view('master.formulir.document.create',compact('data','document'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Document $document)
    {
        $rule = [            
            'name' => 'required|unique:documents,name, '.$document->id, 
            'title'=> 'required'                                                                        
            ];

        $request->validate($rule);
 
        $item = $document;        
        $item->name = $request->name;       
        $item->titles = $request->title;  
        $item->type = $request->type;  
        $item->save();

        toastr()->success('Update Data berhasil', ['timeOut' => 5000]);
        return redirect()->route('document.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Document $document)
    {

        $header = Header::where('doc',$document->id)->first();
        if($header)
        {
            $header->delete();
        }

        $title = Title::where('doc',$document->id);
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


        $document->delete();
        toastr()->success('Hapus Data berhasil', ['timeOut' => 5000]);
        return back();
    }
}
