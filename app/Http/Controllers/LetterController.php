<?php

namespace App\Http\Controllers;

use App\Models\Letter;
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

class LetterController extends Controller
{
   /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $da = Document::where('type','surat')->get();
        $data = "Surat";
        return view('master.letter.index',compact('da','data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = "Tambah Surat";
        return view('master.letter.create',compact('data'));
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
        $item->type = 'surat';    
        $item->save();

        toastr()->success('Tambah Data berhasil', ['timeOut' => 5000]);
        return redirect()->route('letter.index');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {

            $title = Document::has('header')->where(DB::raw('md5(id)'),$id)->where('type','surat')->first();
            if(!$title)
            {
                throw new Exception("Dokumen item header belum di set", 1);                
            }

            $form = $title;
            $qrCode = base64_encode(QrCode::format('png')->size(100)->generate('hello qrcode'));
            $data = compact('form','qrCode');

            $pdf = app()->make(PDF::class);
            
            $pdf = PDF::loadView('doc.letter',$data)
            ->setPaper('a4', 'potrait');
            return $pdf->stream();
            // return view('letter',$data);

            
        } catch (Exception $e) {
            toastr()->error($e->getMessage(), ['timeOut' => 5000]);
            return back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Letter $letter)
    {
        $data = "Edit Surat";
        return view('master.letter.create',compact('data','letter'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Letter $letter)
    {
        $rule = [            
            'name' => 'required|unique:documents,name,'.$letter->id, 
            'title'=> 'required'                                    
            ];

        $request->validate($rule);
 
        $item = $letter;        
        $item->name = $request->name; 
        $item->titles = $request->title; 
        $item->type = 'surat';         
        $item->save();

        toastr()->success('Update Data berhasil', ['timeOut' => 5000]);
        return redirect()->route('letter.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Letter $letter)
    {
        $header = Header::where('doc',$letter->id)->first();
        if($header)
        {
            $header->delete();
        }

        $title = Title::where('doc',$letter->id);
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


        $letter->delete();
        toastr()->success('Hapus Data berhasil', ['timeOut' => 5000]);
        return back();
    }
}
