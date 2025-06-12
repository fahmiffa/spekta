<?php

namespace App\Http\Controllers;

use App\Models\Head;
use App\Models\News;
use App\Models\Notulen;
use App\Models\Signed;
use App\Models\User;
use App\Models\Generals;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use PDF;
use Carbon\Carbon;
use App\Models\District;
use App\Models\Village;
use App\Rules\IsReg;
use Illuminate\Support\Facades\Log;
use App\Models\Consultation;

class NewsController extends Controller
{
    public function __construct()
    {
        $this->middleware('IsPermission:bak');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(Auth::user()->roles->kode == 'SU')
        {
            $val = Consultation::has('sign')->latest();
        }
        else
        {
            $val = Signed::has('kons')->wherehas('doc',function($q){
                $q->whereNull('hold');
            })->where('user', Auth::user()->id)->latest();
        }

        $da = $val->get();


        $data = "Berita Acara Konsultasi";
        return view('document.bak.home', compact('da', 'data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $news = News::whereNot('status', 1)->first();
        if ($news) {
            return redirect()->route('step.news', ['id' => md5($news->id)]);
        } else {
            $doc = head::has('surat')->doesnthave('bak')->latest()->get();
            $data = "Tambah BAK";
            return view('document.bak.create', compact('data', 'doc'));
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $rule = [
            'doc' => 'required',
            'north' => 'required',
            'east' => 'required',
            'west' => 'required',
            'south' => 'required',
            'kondisi' => 'required',
            'permanensi' => 'required',
            'build' => 'required',
            'pile' => 'nullable|file|mimes:pdf|max:5120',
        ];
        $message = [
            'required' => 'Field ini harus diisi',
            'mimes' => 'Extension File invalid',
            'max' => 'File size max 5Mb',
        ];
        $request->validate($rule, $message);

        $path = null;
        $pile = $request->file('pile');
        $in = $request->in;

        if($in == null)
        {
            if ($pile) {
                $ext = $pile->getClientOriginalExtension();
                $path = $pile->storeAs(
                    'assets/bak/' . time() . '_bak.' . $ext, ['disk' => 'public']
                );
            }
        }


        $state = $request->has('state');

        $header = [
            'north' => $request->north,
            'east' => $request->east,
            'west' => $request->west,
            'south' => $request->south,
            'kondisi' => $request->kondisi,
            'permanensi' => $request->permanensi,
        ];

        $head = Head::where(DB::raw('md5(id)'), $request->doc)->first();
        $old = $head->bakTemp()->whereNotNull('deleted_at')->latest()->first();
        $item = $head->bak ? $head->bak : new News;
        $item->head = $head->id;
        $item->plan = $request->build;
        $item->header = json_encode($header);
        if ($request->par) {
            $par = $request->par;
            $pard = $request->par_d;
            $parc = $request->par_c;

            foreach ($par as $key => $par) {
                $pars[] = [$par, $pard[$key], $parc[$key]];
            }

            $item->ibg = json_encode($pars);
        }
        $item->type = 'konsultasi';

        $val = $request->val;
        $width = $request->width;
        for ($i = 0; $i < count($val); $i++) {
            if($val[$i])
            {
                $iu[] = ['uraian' => $val[$i], 'value' => $width[$i]];
            }
        }
        $da['informasi_umum'] = $iu;

        $input = $request->input();
        $filter = ['in','note', '_token', 'idb', 'idp', 'doc', 'north', 'east', 'west', 'south', 'val', 'width', 'kondisi', 'build', 'permanensi', 'files', 'par', 'par_d', 'par_c','place'];
        if ($state) {
            $filter = array_merge(['state'], $filter);
        }
        $input = Arr::except($input, $filter);



        foreach ($input as $key => $value) {
            $ibg[] = ['uraian' => $value[0], 'dimensi' => $value[1], 'note' => $value[2]];
        }

        $da['informasi_bangunan_gedung'] = $ibg;

        $da['idb'] = $request->idb;
        $da['idp'] = $request->idp;

        $item->item = json_encode($da);
        $item->note = $request->note;
        if(!Auth::user()->ijin('master'))
        {
            $item->status = 2;
        }    
        $item->place = $request->place;     

        if($pile)
        {
            $item->files = $path;
        }

        if($in)
        {
            $item->files = null;
        }


        if($old)
        {
            $item->signs = $old->signs;
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
        $news = News::where(DB::raw('md5(head)'), $id)->first();
        $head = Head::where(DB::raw('md5(id)'), $id)->first();
        $dis  = District::all();

        if ($news) {
            if ($news->grant == 1) {
                toastr()->success('Dokumen selesai', ['timeOut' => 5000]);
                return back();
            }
            $data = "Formulir Berita Acara Konsultasi (BAK)";
            return view('document.bak.create', compact('data', 'news', 'head','dis'));
        } else {
            $his = $head->bakTemp->whereNotNull('deleted_at');
            $data = "Dokumen " . $head->nomor;
            if ($his->count() > 0) {
                $news = $his[0];
                return view('document.bak.create', compact('data', 'head', 'news','dis'));
            } else {
                return view('document.bak.create', compact('data', 'head','dis'));
            }
        }
    }

    public function doc($id)
    {
        $news = News::where(DB::raw('md5(id)'), $id)->first();
        $head = $news->doc;
        $data = compact('news', 'head');

        if ($news->type == 'konsultasi') {
            $pdf = PDF::loadView('document.bak.doc.index', $data)->setPaper('legal', 'potrait')->setOption('margin-bottom', '20mm');
            return $pdf->stream();
            return view('document.bak.doc.index', $data);
        } else {
            $pdf = PDF::loadView('verifikator.doc.home', $data)->setPaper('legal', 'potrait');
            return $pdf->stream();
            return view('verifikator.doc.home', $data);
        }
    }

    public function generals(Request $request)
    {
      
        $news = News::where(DB::raw('md5(id)'), $request->id)->first();
        if($news)
        {
            $gen = Generals::where('bak',$news->id)->first();
            $gen = $gen ? $gen :  new Generals;
            $gen->bak = $news->id;
            $gen->pass = Carbon::now()->addMinutes(10)->timestamp;
            $gen->save();

            return route('ttd',['id'=>md5($gen->pass)]);
        }
    }

    public function sign($id)
    {
        $uri = [];
        $news = News::where(DB::raw('md5(id)'), $id)->first();
        if ($news->status == 1) {
            toastr()->error('Dokumen Sudah di publish', ['timeOut' => 5000]);
            return back();
        }

        $uri []= route('doc.news', ['id' => md5($news->id)]); 
        if($news->files)
        {
            $uri []= asset('storage/'.$news->files);  
        }    

        $sign = $news->doc->sign->where('user', Auth::user()->id)->first();
        $val = Notulen::where('users', Auth::user()->id)->where('head', $news->head)->first();
        $lead = $sign->type == 'lead' ? true : false;
        $single = true;
        $title = 'Tanda Tangan Dokumen BAK';
        $doc = 'bak';
        return view('document.bak.sign', compact('news', 'single', 'title', 'lead', 'doc', 'sign','uri'));
    }

    public function signed(Request $request, $id)
    {

        $pile = $request->file('sign');
        $base64_image = 'data:image/png;base64,'.blobImage($pile);

        $news = News::where(DB::raw('md5(id)'), $id)->first();

        if ($request->user == 'pemohon' || $request->user == 'petugas') {

            if($request->in)
            {
                $news->in_name = $request->req;
            }

            if ($request->user == null) {
                toastr()->error('User belum di pilih', ['timeOut' => 5000]);
            }

            if ($news == null) {
                toastr()->error('Invalid Data', ['timeOut' => 5000]);
            }

            if ($news->status == 1) {
                toastr()->error('Dokumen Sudah di publish', ['timeOut' => 5000]);
            }

         

            if ($base64_image) {
                if ($request->user == 'petugas' && $news->primary == 'TPA') {
                    $news->sign = $base64_image;
                } else {
                    $news->signs = $base64_image;
                }

                $news->save();
                toastr()->success('Tanda tangan berhasil, Complete', ['timeOut' => 5000]);
            } else {
                toastr()->error('Invalid Data', ['timeOut' => 5000]);
            }

        } else {
            $sign = Signed::where(DB::raw('md5(user)'), $request->user)->where('head', $news->head)->first();
            if ($sign) {

                if ($base64_image) {
                    $sign->bak = $base64_image;
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
        $news = News::where(DB::raw('md5(id)'), $id)->first();
        if ($news) {
            $sign = $news->doc->sign->whereNull('bak')->first();
            $val = Notulen::where('users', Auth::user()->id)->where('head', $news->head)->first();

            if ($news->signs == null) {
                toastr()->error('Pemohon belum tanda tangan', ['timeOut' => 5000]);
                return back();
            } else if ($sign) {
                toastr()->error('Petugas '.$sign->users->name.' belum tanda tangan', ['timeOut' => 5000]);
                return back();
            } else {
                $news->status = 1;
                // if ($val->user->roles->kode == 'TPA') {
                //     $news->grant = 1;
                // }
                $news->save();
                toastr()->success('Publish  berhasil, Complete', ['timeOut' => 5000]);
                return redirect()->route('news.index');
            }
        } else {
            toastr()->error('Invalid Data', ['timeOut' => 5000]);
            return back();
        }

    }

    public function next(Request $request, $id)
    {
        $news = News::where(DB::raw('md5(id)'), $id)->first();
        if ($news) {
            if ($news->status == 5) {

                $val = $request->val;
                $width = $request->width;

                for ($i = 0; $i < count($val); $i++) {
                    $item[] = ['uraian' => $val[$i], 'value' => $width[$i]];
                }
                $news->item = json_encode(['informasi_umum' => $item]);
                $news->status = 4;
                $news->save();

                toastr()->success('Tambah Data berhasil, lanjutkan', ['timeOut' => 5000]);
                return back();
            }

            if ($news->status == 4) {
                $input = $request->input();
                array_shift($input);

                foreach ($input as $key => $value) {
                    $item[] = ['uraian' => $value[0], 'dimensi' => $value[1], 'note' => $value[2]];
                }
                $old = (array) json_decode($news->item);
                $item = array_merge(['informasi_bangunan_gedung' => $item], $old);

                $news->item = json_encode($item);
                $news->status = 3;
                $news->save();

                toastr()->success('Tambah Data berhasil, lanjutkan', ['timeOut' => 5000]);
                return back();
            }

            if ($news->status == 3) {
                $input = $request->input();
                $old = (array) json_decode($news->item);

                array_shift($input);
                $item = array_merge($input, $old);
                $news->item = json_encode($item);
                $news->status = 2;
                $news->save();

                toastr()->success('Tambah Data berhasil, lanjutkan', ['timeOut' => 5000]);
                return back();
            }

            if ($news->status == 2) {
                $news->note = $request->note;
                $news->status = 1;
                $news->save();

                $this->genPDF($news);
                toastr()->success('Tambah Data berhasil, Complete', ['timeOut' => 5000]);
                return redirect()->route('news.index');
            }

        } else {
            toastr()->error('Invalid Data', ['timeOut' => 5000]);
            return back();
        }
    }

    public function village(Request $request)
    {
        $da = Village::where('districts_id',$request->id)->pluck('name', 'id');
        return response()->json($da);
    }

    public function pemohon(Request $request, $id)
    {
        $head = Head::where(DB::raw('md5(id)'), $id)->firstOrFail();

        $rule = [                               
            'namaPemohon' => ['required','regex:/^(?!.*<script>).*$/i'],           
            'alamatPemohon'=> ['required','regex:/^(?!.*<script>).*$/i'],                                    
            'namaBangunan'=> ['required','regex:/^(?!.*<script>).*$/i'],                                    
            'alamatBangunan'=> ['required','regex:/^(?!.*<script>).*$/i'], 
            'fungsi'=> ['required','regex:/^(?!.*<script>).*$/i'], 
            'koordinat'=> ['required','regex:/^(?!.*<script>).*$/i'], 
            'dis'=> ['required','regex:/^(?!.*<script>).*$/i'], 
            'des'=> ['required','regex:/^(?!.*<script>).*$/i'], 
            'hp'=> ['required','regex:/^(?!.*<script>).*$/i'],    
            'land'=> ['required','regex:/^(?!.*<script>).*$/i'], 
            'noreg'=> ['required', new IsReg($head)],
            'email'=> 'required', 
            ];
            
        $message = ['required'=> 'Field :attribute harus diisi','regex'=> 'Field :attribute tidak valid'];
        $request->validate($rule,$message);

      
        
        if($head)
        {
            $header = json_decode($head->header);
            if($request->email)
            {
                $head->email = $request->email;
            }
            $header[2] = $request->namaPemohon;
            $header[3] = $request->hp;
            $header[4] = $request->alamatPemohon;
            $header[5] = $request->namaBangunan;
            $header[6] = $request->fungsi;
            $header[7] = $request->alamatBangunan;
            $header[8] = $request->koordinat;
            $header[9] = $request->land;
            
            $head->reg = $request->noreg;
            $head->email = $request->email;
            $head->village = $request->des;
            $head->header = json_encode($header);
            $head->save();

            toastr()->success('Update berhasil, Complete', ['timeOut' => 5000]);
        }
        else
        {
            toastr()->error('Update gagal', ['timeOut' => 5000]);
        }

        return back();

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(News $news)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, News $news)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(News $news)
    {
        //
    }
}
