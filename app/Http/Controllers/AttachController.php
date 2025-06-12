<?php

namespace App\Http\Controllers;

use App\Models\Attach;
use App\Models\Head;
use App\Models\Tax;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PDF;
use QrCode;
use App\Models\Setting;
use Illuminate\Support\Facades\Log;
use Auth;

class AttachController extends Controller
{
    public function __construct()
    {
        $this->middleware('IsPermission:lampiran');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $val = Head::whereNull('hold')->has('kons')->latest();
        $da = $val->get();

        $data = "Lampiran";
        $ver = false;
        return view('document.attach.home', compact('da', 'data', 'ver'));

    }

    public function doc($id)
    {
        $head = Head::where(DB::raw('md5(id)'), $id)->first();
        $link = $head->links->where('ket', 'lampiran')->first();
        if($link)
        {
            $qrCode = base64_encode(QrCode::format('png')->size(200)->generate(route('link', ['id' => $link->short])));
        }
        else
        {
            $qrCode = null;
        }

        $data = compact('qrCode', 'head');
        
        $pdf = PDF::loadView('document.attach.doc.index', $data)->setPaper('legal', 'potrait');
        return $pdf->stream();
        return view('document.attach.doc.index', $data);
    }

    public function docs($id)
    {
        $head = Head::where(DB::raw('md5(id)'), $id)->first();
        $qrCode = base64_encode(QrCode::format('png')->size(200)->generate($head->nomor));
        $data = compact('qrCode', 'head');

        $pdf = PDF::loadView('document.tax.doc.index', $data)->setPaper('legal', 'potrait');        
        return $pdf->stream();
    }

    public function step($id)
    {
        $attach = Attach::where(DB::raw('md5(head)'), $id)->first();
        $head = Head::where(DB::raw('md5(id)'), $id)->first();

        $data = "Lampiran PBG dan/atau SLF No. " . $head->number;
        return view('document.attach.create', compact('data', 'head', 'attach'));

    }

    public function tax()
    {
        $val = Setting::first();
        $val = Head::whereNull('hold')->has('kons')->latest();
        $da = $val->get();

        $data = "Perhitungan Retribusi";
        $ver = false;
        return view('document.tax.home', compact('da', 'data', 'ver'));

    }

    public function stepr($id)
    {
        $val = Setting::first();
        $tax = Tax::where(DB::raw('md5(head)'), $id)->first();
        $head = Head::where(DB::raw('md5(id)'), $id)->first();
        $data = "Dokumen " . $head->number;
        return view('document.tax.step', compact('data', 'head', 'tax','val'));

    }

    public function storeTax(Request $request, $id)
    {

        $input = $request->input();
        array_shift($input);

        $tax = Tax::where(DB::raw('md5(head)'), $id)->first();
        $head = Head::where(DB::raw('md5(id)'), $id)->first();

        $item = $tax? $tax :  new Tax;
        $item->head = $head->id;
        $item->tanggal = $request->tanggal;
        $item->parameter = json_encode($input);
        if(!Auth::user()->ijin('master'))
        {
            $item->status = 2;
        }    
        $item->save();

        toastr()->success('Input Data berhasil', ['timeOut' => 5000]);
        return redirect()->route('tax.index');

    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rule = [
            'doc' => 'required',
            'luas' => 'required',
            'bukti' => 'required',   
            'koordinat' => 'required',
            'pile_loc' => 'nullable|mimes:jpeg,png,jpg,|max:2048',
            'pile_land' => 'nullable|mimes:jpeg,png,jpg|max:2048',
            'pile_map.*' => 'nullable|mimes:jpeg,png,jpg|max:2048',
        ];
        
        $message = ['required' => 'Field ini harus diisi',
                     'mimes' => 'Ektensi file harus jpeg, jpg atau png',
                     'max' => 'Ukuran file Maksimal 2 Mb',
                   ];

        $request->validate($rule, $message);

        $head = Head::where(DB::raw('md5(id)'), $request->doc)->first();
        $item = $head->attach ? $head->attach : new Attach;

        $item->head = $head->id;
        $item->bukti = $request->bukti;
        $pile_loc = $request->file('pile_loc');
        if ($pile_loc) {
            $ext = $pile_loc->getClientOriginalExtension();
            $path = $pile_loc->storeAs(
                'assets/data/'.uniqid().'.' . $ext, ['disk' => 'public']
            );
            $item->pile_loc = $path;
        }

        $pile_map = $request->file('pile_map');
        if(is_array($pile_map))
        {
            $filePaths = [];
            foreach ($pile_map as $key => $value) {
                
                $ext = $value->getClientOriginalExtension();
                $path = $value->storeAs(
                    // 'assets/data/'.uniqid().'-'.$key.'.' . $ext, ['disk' => 'public']
                    'assets/data/'.uniqid().'.' . $ext, ['disk' => 'public']
                );
                $filePaths[] = $path;
            }
            $item->pile_map = json_encode($filePaths);
        }

        $pile_land = $request->file('pile_land');
        if ($pile_land) {
            $ext = $pile_land->getClientOriginalExtension();
            $path = $pile_land->storeAs(
                'assets/data/'.uniqid().'.' . $ext, ['disk' => 'public']
            );
            $item->pile_land = $path;
        }
        $item->luas = $request->luas;
        $item->persil = $request->persil;
        $item->koordinat = $request->koordinat;
        $item->save();

        shortLink($head->id,'lampiran');

        toastr()->success('Input Data berhasil', ['timeOut' => 5000]);
        return redirect()->route('attach.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Attach $attach)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Attach $attach)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Attach $attach)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attach $attach)
    {
        //
    }
}
