<?php

namespace App\Http\Controllers;

use App\Models\Head;
use App\Models\Meet;
use App\Models\Notulen;
use App\Models\Signed;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use PDF;
use Illuminate\Support\Facades\Log;

class MeetController extends Controller
{
    public function __construct()
    {
        $this->middleware('IsPermission:barp');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(Auth::user()->roles->kode == 'SU')
        {
            $val = Meet::latest();
        }
        else
        {
            $val = Signed::has('doc')->where('user', Auth::user()->id)->latest();
        }

        $da = $val->get();

        $data = "Berita Acara Rapat Pleno";
        $ver = false;
        return view('document.barp.home', compact('da', 'data', 'ver'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $meet = meet::whereNot('status', 1)->first();
        if ($meet) {
            return redirect()->route('step.meet', ['id' => md5($meet->id)]);
        } else {
            $doc = head::has('surat')->doesnthave('barp')->whereHas('bak', function ($q) {
                $q->where('grant', 1);
            })->latest()->get();
            $data = "Tambah BARP";
            return view('document.barp.create', compact('data', 'doc'));
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rule = [
            'doc' => 'required',
            'jenis' => 'required',
            'status' => 'required',
            'fungsi' => 'required',
            'date' => 'required',
            'val' => 'required'
        ];

        $message = ['required' => 'Field ini harus diisi','val.required'=>'Belum ada opsi yang dipilih'];
        $request->validate($rule, $message);

        $input = $request->input();
        
        $filter = ['jenis', '_token', 'fungsi','val','text2','text3','status','files', 'nib', 'doc', 'date', 'uraian', 'pengajuan', 'disetujui', 'keterangan','tang','place'];
        $input = Arr::except($input, $filter);
        
       $val = $request->val;
       $text = null;

        if($val == 2)
        {
            $text = $request->text2;
        }
        
        if($val == 3)
        {
            $text = $request->text3;
        }
        

        $input = array_merge($input, ['val' => $val, 'text'=>$text]);
        
        $header = [
            'nib' => $request->nib,
            'jenis' => $request->jenis,
            'status' => $request->status,
            'fungsi' => $request->fungsi,
        ];

        $ur = $request->uraian;
        $peng = $request->pengajuan;
        $appr = $request->disetujui;
        $ket = $request->keterangan;
        $other = [];
        if ($ur) {
            for ($i = 0; $i < count($ur); $i++) {
                $other[] = [
                    'uraian' => $ur[$i],
                    'pengajuan' => $peng[$i],
                    'disetujui' => $appr[$i],
                    'keterangan' => $ket[$i],
                ];
            }
        }

        $head = Head::where(DB::raw('md5(id)'), $request->doc)->first();
        $item = $head->barp ? $head->barp : new Meet;
        $item->val = $val;
        $item->head = $head->id;
        $item->tanggal = $request->date;
        $item->place = $request->place;
        $item->date = $request->tang;
        $item->header = json_encode($header);
        $item->item = json_encode($input);
        $item->other = json_encode($other);
        $item->type = 'pleno';
        if(!Auth::user()->ijin('master'))
        {
            $item->status = 2;
        }        
        $item->save();

        toastr()->success('Tambah Data berhasil', ['timeOut' => 5000]);
        return back();
    }

    private function genPDF($news)
    {
        $head = $news->doc;
        $data = compact('news', 'head');

        $name = 'konsultasi_' . $head->reg . '.pdf';
        $dir = 'assets/data/';
        $path = $dir . $name;

        $pdf = PDF::loadView('document.bak.doc.index', $data)->setPaper('a4', 'potrait');
        Storage::disk('public')->put($path, $pdf->output());
        // return view('document.bak.doc.index',$data);
        // return $pdf->stream();

        $news->files = $path;
        $news->save();
    }

    public function step($id)
    {
        $meet = Meet::where(DB::raw('md5(head)'), $id)->first();
        $head = Head::where(DB::raw('md5(id)'), $id)->first();

        if (!$meet) {
            $his = $head->barpTemp->whereNotNull('deleted_at');
            if ($his->count() > 0) {
                $meet = ($meet) ? $meet : $his[0];
            }
        }

        $data = "Formulir Berita Acara Rapat Pleno (BARP) <br>No. " .$head->number;
        return view('document.barp.create', compact('data', 'head', 'meet'));

    }

    public function doc($id)
    {
        $meet = Meet::where(DB::raw('md5(id)'), $id)->first();
        $news = $meet->doc->bak;
        $head = $meet->doc;
        $data = compact('news', 'head', 'meet');

        $pdf = PDF::loadView('document.barp.doc.index', $data)->setPaper('legal', 'potrait');
        // return view('document.barp.doc.index', $data);
        return $pdf->stream();
    }

    public function sign($id)
    {
        $uri = [];
        $news = Meet::where(DB::raw('md5(id)'), $id)->first();
        $uri []= route('doc.meet', ['id' => md5($news->id)]);  
        if ($news->status == 1) {
            toastr()->error('Dokumen Sudah di publish', ['timeOut' => 5000]);
            return back();
        }

        if($news->doc->bak)
        {
            $uri []= route('doc.news', ['id' => md5($news->doc->bak->id)]);  
        }
        if($news->doc->attach)
        {
            $uri []= route('doc.attach', ['id' => md5($news->head)]);  
        }
        if($news->doc->tax)
        {
            $uri []= route('doc.tax', ['id' => md5($news->head)]);       
        }

        $sign = $news->doc->sign->where('user', Auth::user()->id)->first();
        $val = Notulen::where('users', Auth::user()->id)->where('head', $news->head)->first();
        $lead = $sign->type == 'lead' ? true : false;
        $single = true;
        $title = 'Tanda Tangan Dokumen BARP';
        $doc = 'barp';
        return view('document.barp.sign', compact('news', 'single', 'title', 'lead', 'doc', 'sign','uri'));
    }

    public function signed(Request $request, $id)
    {
        $pile = $request->file('sign');
        $base64_image = 'data:image/png;base64,'.blobImage($pile);

        $meet = Meet::where(DB::raw('md5(id)'), $id)->first();
        if ($request->user == 'pemohon' || $request->user == 'petugas') {
            if ($base64_image && $meet->primary == 'TPA') {
                if ($request->user == 'petugas') {
                    $meet->sign = $base64_image;
                } else {
                    $meet->signs = $base64_image;
                }

                $meet->sign = $base64_image;
                $meet->save();
                toastr()->success('Tanda tangan berhasil, Complete', ['timeOut' => 5000]);
            } else {
                toastr()->error('Invalid Data', ['timeOut' => 5000]);
            }

        } else {
            $sign = Signed::where(DB::raw('md5(user)'), $request->user)->where('head', $meet->head)->first();
            if ($sign) {
                if ($base64_image) {
                    $sign->barp = $base64_image;
                    $sign->save();
                    toastr()->success('Tanda tangan berhasil, Complete', ['timeOut' => 5000]);
                } else {
                    toastr()->error('Invalid Data', ['timeOut' => 5000]);
                }

            } else {
                toastr()->error('Invalid Data petugas', ['timeOut' => 5000]);
            }

        }

        return back();

    }

    public function pub(Request $request, $id)
    {
        $meet = Meet::where(DB::raw('md5(id)'), $id)->first();
        if ($meet) {
            $sign = $meet->doc->sign->whereNull('barp')->first();
            $val = Notulen::where('users', Auth::user()->id)->where('head', $meet->head)->first();

            if ($sign) {
                toastr()->error('Petugas ' . $sign->users->name . ' belum tanda tangan', ['timeOut' => 5000]);
                return back();
            } else {
                $meet->status = 1;
                $meet->save();
                toastr()->success('Publish  berhasil, Complete', ['timeOut' => 5000]);
                return redirect()->route('meet.index');
            }
        } else {
            toastr()->error('Invalid Data', ['timeOut' => 5000]);
        }
        return back();

    }

    public function next(Request $request, $id)
    {
        $meet = Meet::where(DB::raw('md5(id)'), $id)->first();
        if ($meet) {

            if ($meet->status == 2) {
                $input = $request->input();
                array_shift($input);
                $meet->item = json_encode($input);
                $meet->status = 1;
                $meet->save();

                toastr()->success('Tambah Data berhasil, Complete', ['timeOut' => 5000]);
                return redirect()->route('meet.index');
            }

        } else {
            toastr()->error('Invalid Data', ['timeOut' => 5000]);
            return back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Meet $meet)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Meet $meet)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Meet $meet)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Meet $meet)
    {
        //
    }
}
