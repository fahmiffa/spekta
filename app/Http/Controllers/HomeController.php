<?php

namespace App\Http\Controllers;

use App\Models\Attach;
use App\Models\Consultation;
use App\Models\Formulir;
use App\Models\Head;
use App\Models\Links;
use App\Models\Meet;
use App\Models\News;
use App\Models\Schedule;
use App\Models\Signed;
use App\Models\Step;
use App\Models\Tax;
use App\Models\User;
use App\Models\Verifikator;
use App\Models\Setting;
use App\Models\Generals;
use App\Models\PemohonHead;
use App\Rules\MatchOldPassword;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use PDF;
use QrCode;
use App\Exports\HeadExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use App\Mail\SipMail;
use Mail;

class HomeController extends Controller
{

    public function notif(Request $request)
    {
        $user = Auth::user();
        $da = [];
        if($user->roles->kode == 'TPT' || $user->roles->kode == 'TPA')
        {
            $sign = Signed::whereHas('doc',function($q){
                $q->where('do','!=',1);

            })->where('user',$user->id)
            ->select('bak','barp','head')
            ->get();
            $bak = null;
            $barp = null;
            $status = 0;
            foreach ($sign as $key ) {
                $header = json_decode($key->doc->header);

                if($key->doc->bak && $key->doc->bak->signs)
                {
                    $bak = $key->doc->bak ? $key->doc->bak->id : null;
                    $barp = $key->doc->barp ? $key->doc->barp->id : null;
    
                    $da [] = [
                              'penugasan'=>true, 
                              'name'=>$header[2], 
                              'statusBak'=>$key->doc->bak ? $key->doc->bak->status : null,  
                              'statusBarp'=>$key->doc->barp ? $key->doc->barp->status : null,  
                              'bak'=>$key->bak,
                              'barp'=>$key->barp, 
                              'bakUri'=>$bak ? route('sign.news',['id'=>md5($bak)]) : null, 
                              'barpUri'=>$barp ? route('sign.meet',['id'=>md5($barp)]) : null, 
                              'reg'=>$key->doc->reg,
                              'msg'=> $key->bak ? 'Belum di Publish BAK' : 'Belum Tanda Tangan BAK'
                            ];
                }
                
            }
        }

        if($user->roles->kode == 'KB')
        {
                $val = head::select('id','reg','header')
                ->whereHas('bak', function ($q) {
                    $q->where('status', 1);
                })
                ->where('status',1)
                ->where('do',0)
                ->where('grant',1)
                ->whereHas('barp', function ($q) {
                    $q->where('status', 1);
                })
                ->latest();
        
                $val = $val->get();
        
                foreach ($val as $key ) {
                    $header = json_decode($key->header);
                    $da [] = [ 'name'=>$header[2],  'persetujuan'=>true, 'uri'=>route('ba.sign', ['id' => md5($key->id)]),'reg'=>$key->reg];
        
                }
        }

        if (Auth::user()->ijin('doc_formulir')) {
            $task = Verifikator::where('verifikator', Auth::user()->id)
                    ->whereHas('doc', function ($q) {
                        $q->where('grant', 0);
                    })->get();

                foreach ($task as $key ) {

                    $header = json_decode($key->doc->header);

                    if($key->doc->steps->count() < 1 && $key->doc->open == 1 && $key->doc->grant == 0 && $key->doc->status != 1 && $key->doc->temp->whereNotNull('deleted_at')->count() > 0)
                    {
                        $da [] = [ 'name'=>$header[2], 'verifikator'=>true, 'reg'=>$key->noreg, 'par'=>'Verifikasi Ulang', 'uri'=>route('step.verifikasi', ['id' => md5($key->head)])];
                    }

                    if($key->doc->steps->count() < 1 && $key->doc->open == 1 && $key->doc->grant == 0 && $key->doc->status != 1 && $key->doc->temp->whereNotNull('deleted_at')->count() < 1)
                    {
                        $da [] = ['name'=>$header[2],  'verifikator'=>true, 'reg'=>$key->noreg, 'par'=>'Verifikasi Kelengkapan Dokumen', 'uri'=>route('step.verifikasi', ['id' => md5($key->head)])];
                    }
                }
        }


        return response()->json($da);
    }

    public function profile()
    {
        $data = "Data Profil";
        return view('profile', compact('data'));
    }

    public function simulasiRetribusi()
    {
        $val = Setting::first();
        $data = 'Simulasi Retribusi PBG Kabupaten Tegal';
        $tax = null;
        return view('simulasi', compact('data','val','tax'));
    }

    public function export(Request $request)
    {
        $rule = [
            'startDate' => 'required',
            'endDate' => 'required'
        ];
        $message = [
            'required' => 'Field ini harus diisi',     
        ];
        $request->validate($rule, $message);
        return Excel::download(new HeadExport([$request->startDate,$request->endDate]), 'export.xlsx');

    }

    public function profiled(Request $request)
    {
        $rule = [
            'oldpassword' => ['required', new MatchOldPassword],
            'password' => 'required|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@!$#%&? "]).+$/|min:12',    
            'password_confirmation' => 'required',
        ];
        $message = [
            'required' => 'Field ini harus diisi',
            'min'       =>  ':attribute harus minimal 12 digit',         
            'regex'     =>  ':attribute harus kombinasi Huruf besar kecil, angka dan simbol', 
            'confirmed' => 'Field :attribute Konfirm tidak valid',
            'regex' => 'Password harus kombinasi Huruf dan Angka',
        ];
        $request->validate($rule, $message);

        $user = User::where('id', Auth::user()->id)->first();
        $user->password = bcrypt($request->password);
        $user->save();

        toastr()->success('Update Berhasil', ['timeOut' => 5000]);
        return back();
    }

    public function image(Request $request)
    {
        $rule = [
           'image' => 'required|mimes:png,jpg,jpeg|max:2048'
        ];

        $message = [
            'required' => 'Field ini harus diisi',
            'mimes' => 'Field :attribute ektensi tidak valid',
            'max' => 'ukuran maksimal 2MB',
        ];

        $request->validate($rule, $message);

        $pile = $request->file('image');
        $path = null;
        
        $user = User::where('id', Auth::user()->id)->first();      

        if ($pile) {
            $ext = $pile->getClientOriginalExtension();
            $path = $pile->storeAs(
                'assets/user/' .md5($user->id). '_user.' . $ext, ['disk' => 'public']
            );
        }

        $user->img = $path;
        $user->save();


        toastr()->success('Update Berhasil', ['timeOut' => 5000]);
        return back();
    }

    private function chart($request)
    {
        $year = $request->tahun;
        $pengajuan = $request->pengajuan;
        $fungsi = $request->fungsi;
        $currentYear = $year ? $year : Carbon::now()->year;
        $currentMonth = Carbon::now()->month;


        $months = [
            'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
            'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec',
        ];
        // Array untuk nilai default 0 untuk setiap bulan
        $counts = array_fill(0, 12, 0); 
        $done = array_fill(0, 12, 0); 
        $no = array_fill(0, 12, 0); 


        foreach ($months as $key => $value) {

            $data = PemohonHead::whereYear('created_at', $currentYear)
                    ->whereMonth('created_at', $key+1);

            if($fungsi)
            {
                $data = $data->where('tipe',$fungsi);
            }

            if($pengajuan)
            {
                $data = $data->where('cat',$pengajuan);
            }

            $total = $data->count();
            $dones = $data->where('do',1)->count();
            $monthIndex = $key;
            $counts[$monthIndex] = $total;
            $done[$monthIndex]   = $dones;
            $no[$monthIndex]     = $total - $dones;
        }

        return [
            'months' => $months,
            'counts' => $counts,
            'done'   => $done,
            'no'     => $no,
        ];
    }

    private function summmary()
    {
        $res = [
            Meet::where('val',1)->where('grant',1)->count(),
            Meet::where('val',2)->count(),
            Meet::where('val',3)->count(),
        ];
        
        return $res;
    }

    public function index(Request $request)
    {
        $year = $request->tahun ? $request->tahun : date('Y');
        $pengajuan = $request->pengajuan;
        $fungsi = $request->fungsi;

        $res  = $this->summmary();
        $chart = $this->chart($request);
        $head = Head::all();
        $jadwal = Head::doesnthave('kons')->where('grant', 1)->get()->count();
        $verif = Head::doesnthave('kons')->where('grant', 0)->get()->count();
        $kons = Head::has('kons')->where('do', 0)->get()->count();

        $bak = Head::whereHas('bak', function ($q) {
            $q->where('grant', 1);
        })->get()->count();

        $barp = Head::whereHas('barp', function ($q) {
            $q->where('grant', 1);
        })->get()->count();

        // admin, sekretariat
        if (Auth::user()->ijin('master_formulir')) {
            return view('home', compact('head', 'verif', 'kons', 'bak', 'barp', 'chart', 'jadwal','res','year','pengajuan','fungsi'));
        }

        // notulen (teknis)
        if (Auth::user()->ijin('bak')) {

            $comp = head::where('do', 1)->whereHas('sign', function ($q) {
                $q->where('user', Auth::user()->id);
            })->count();

            $task = head::where('do', 0)->whereHas('sign', function ($q) {
                $q->where('user', Auth::user()->id);
            })->count();

            return view('main', compact('task', 'comp','verif', 'kons', 'bak', 'barp', 'chart', 'jadwal','head','res','year','pengajuan','fungsi'));
        }

        // verifikator
        if (Auth::user()->ijin('doc_formulir')) {
            $comp = Verifikator::where('verifikator', Auth::user()->id)
                ->whereHas('doc', function ($q) {
                    $q->where('grant', 1)->where('open', 1);
                })->count();
            $task = Verifikator::where('verifikator', Auth::user()->id)
                ->whereHas('doc', function ($q) {
                    $q->where('grant', 0);
                })->count();
            return view('main', compact('head', 'verif', 'kons', 'bak', 'barp', 'chart', 'jadwal','task','comp','res','year','pengajuan','fungsi'));
        }

        // kabid
        if (Auth::user()->ijin('verifikasi_bak')) {
            $task = head::whereHas('bak', function ($q) {
                $q->where('grant', 0)
                ->where('status', 1);
            })
            ->whereHas('barp', function ($q) {
                $q->where('grant', 0)
                ->where('status', 1);
            })
            ->count();

            $comp = head::whereHas('bak', function ($q) {
                $q->where('grant', 1)
                ->where('status', 1);
            })
            ->whereHas('barp', function ($q) {
                $q->where('grant', 1)
                ->where('status', 1);
            })
            ->count();


            return view('general', compact('task', 'comp','head', 'verif', 'kons', 'bak', 'barp', 'chart', 'jadwal','res','year','pengajuan','fungsi'));
        }

    }

    public function simbg(Request $request,$id)
    {
        $head = Head::where(DB::raw('md5(id)'), $id)->first();
        if($head)
        {
            $head->simbg = $head->simbg == 0 ? 1 : 0;
            $head->save();
            toastr()->success('Update Berhasil', ['timeOut' => 5000]);
        }
        else
        {
            toastr()->error('Update Gagal', ['timeOut' => 5000]);
        }
        return back();
    }

    public function req()
    {
        $val = Head::where('do',1)->has('tax')->latest();
        $da = $val->get();
        $data = "Dokumen Permohonan";
        $ver = true;
        return view('req.index', compact('da', 'data', 'ver'));
    }

    public function monitoring()
    {
        $val = Head::latest();
        $da = $val->get();
        $data = "Monitoring Dokumen";
        $ver = true;
        return view('monitoring', compact('da', 'data', 'ver'));
    }

    public function Pending()
    {
        $val = Head::whereNotNull('hold')->latest();
        $da = $val->get();
        $data = "Dokumen ditunda";
        $ver = true;
        return view('pending', compact('da', 'data', 'ver'));
    }

    public function doc($id)
    {
        $head = Head::where(DB::raw('md5(id)'), $id)->first();
        return view('document.pdf', compact('head'));
    }

    public function dok($id, $par)
    {
        $head = Head::where(DB::raw('md5(id)'), $id)->first();

        if ($par == 'bak') {
            $news = $head->bak;
            $data = compact('news', 'head');

            $pdf = PDF::loadView('document.bak.doc.index', $data)->setPaper('legal', 'potrait');
            return $pdf->stream();
            return view('document.bak.doc.index', $data);
        } else if ($par == 'barp') {
            $meet = $head->barp;
            $news = $head->bak;
            $data = compact('news', 'head', 'meet');

            $pdf = PDF::loadView('document.barp.doc.index', $data)->setPaper('legal', 'potrait');
            return $pdf->stream();
            return view('document.barp.doc.index', $data);

        } else if ($par == 'tax') {
            $qrCode = base64_encode(QrCode::format('png')->size(200)->generate($head->nomor));
            $data = compact('qrCode', 'head');

            $pdf = PDF::loadView('document.tax.doc.index', $data)->setPaper('legal', 'potrait');
            return $pdf->stream();

        } else if ($par == 'attach') {
            $link = $head->links->where('ket', 'lampiran')->first();
            $qrCode = base64_encode(QrCode::format('png')->size(200)->generate(route('link', ['id' => $link->short])));
            $data = compact('qrCode', 'head');

            $pdf = PDF::loadView('document.attach.doc.index', $data)->setPaper('legal', 'potrait');
            // return view('document.attach.doc.index', $data);
            return $pdf->stream();
        } else if ($par == 'verifikasi') {
            return  $this->verifikasi($id);
        }
    }

    public function link($id)
    {
        $uri = [];
        $link = Links::where('short', $id)->first();
        if($link)
        {
            $title = strtoupper(str_replace('_', ' ', $link->ket));
            if ($link->ket == 'surat_undangan') {
                $uri[] = route('surat', ['id' => splitChar($link->head)]);
                if ($link->doc->kons->files) {
                    $uri[] = asset('storage/' . $link->doc->kons->files);
                }
            }
            else if($link->ket == 'verifikasi')
            {
    
                $uri[] = route('req.dok', ['id' => md5($link->head), 'par'=>'verifikasi']);
            }
            else if($link->ket == 'lampiran')
            {
                $uri[] = route('req.dok', ['id' => md5($link->head), 'par'=>'attach']);
            }
    
            return view('document.embeds', compact('uri', 'title'));

        }
        else
        {
            return redirect()->route('home');
        }


    }

    // preview bak
    public function preview($id,$par)
    {    
        $uri = [];
        $title = null;
        if($par == 'bak')
        {
            $news = News::where(DB::raw('md5(id)'), $id)->first();
            $uri []= route('doc.news', ['id' => md5($news->id)]);     
            
            if($news->doc->attach)
            {
                $uri []= route('doc.attach', ['id' => md5($news->head)]);  
            }
            
            if($news->doc->tax)
            {
                $uri []= route('doc.tax', ['id' => md5($news->head)]);       
            }

            if($news->files)
            {
                $uri []= asset('storage/'.$news->files);  
            }                    

            $title = $news->doc->numbDoc('bak');
        }   

        if($par == 'barp')
        {
            $meet = Meet::where(DB::raw('md5(id)'), $id)->first();
            $uri []= route('doc.meet', ['id' => md5($meet->id)]);  

            if($meet->doc->bak)
            {
                $uri []= route('doc.news', ['id' => md5($meet->doc->bak->id)]);  
            }
            
            if($meet->doc->attach)
            {
                $uri []= route('doc.attach', ['id' => md5($meet->head)]);  
            }
            if($meet->doc->tax)
            {
                $uri []= route('doc.tax', ['id' => md5($meet->head)]);       
            }
            
            if($meet->doc->bak->files)
            {
                $uri []= asset('storage/'.$meet->doc->bak->files);  
            }     

            $title = $meet->doc->numbDoc('barp');
        }   

        return view('document.embeds', compact('uri', 'title'));

    }

    public function surat($id)
    {
        $id = str_replace('-', null, $id);
        $schedule = Schedule::where(DB::raw('md5(head)'), $id)->first();
        $link = Links::where('head', $schedule->head)->where('ket', 'surat_undangan')->first();
        $qrCode = base64_encode(QrCode::format('png')->size(200)->generate(route('link', ['id' => $link->short])));
        $data = compact('schedule', 'qrCode');
        $pdf = PDF::loadView('konsultasi.doc.index', $data)->setPaper('legal', 'potrait');
        return $pdf->stream();
        return view('konsultasi.doc.index', $data);
    }

    public function verifikasi($id)
    {
        $head = Head::where(DB::raw('md5(id)'), $id)->withTrashed()->first();
        $num = LogFix($head);
        $docs = Formulir::where('name', $head->type)->first();

        $step = $head->step == 1 ? 0 : 1;

        $link = $head->links->where('ket', 'verifikasi')->first();
        $res = route('link', ['id' => $link->short]);

        $qrCode = base64_encode(QrCode::format('png')->size(200)->generate($res));

        $data = compact('qrCode', 'docs', 'head', 'step', 'num');
        if ($head->step == 1) {
            $pdf = PDF::loadView('verifikator.doc.index', $data)->setPaper('legal', 'potrait');
            return $pdf->stream();
            return view('verifikator.doc.index', $data);
        } else {
            $pdf = PDF::loadView('verifikator.doc.home', $data)->setPaper('legal', 'potrait');
            return $pdf->stream();
            return view('verifikator.doc.home', $data);
        }
        
    }

    public function store(Request $request)
    {
        $rule = [
            'reg' => 'required',
            'doc' => 'required',
        ];
        $message = ['required' => 'Field ini harus diisi',
        ];
        $request->validate($rule, $message);
        $item = Head::where('reg', $request->reg)->where('nomor', $request->doc)->first();

        if($item->old && $item->grant == 0)
        {          
            $link = route('link',['id'=>$item->old->link->short]);
        }
        else
        {
            $link = route('link',['id'=>$item->link->short]);
        }

        $da = [$item,$link];

        if ($item) {
            return back()->with('res', $da)->withInput();
        } else {
            toastr()->error('Dokumen tidak ditemukan', ['timeOut' => 5000]);
            return back();
        }

    }

    public function check(Request $request)
    {

        $messages = [
            'required' => ':attribute harus diisi',        
        ];

        $validator = Validator::make($request->all(), [ 
            'reg' => 'required',
            'doc' => 'required',
        ], $messages);
      
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $validator->errors()
            ], 422); 
        }


        $head = Head::where('reg', $request->reg)->where('nomor', $request->doc)->first();


        
        if($head)
        {
            if($head->old && $head->grant == 0)
            {          
                $link = route('link',['id'=>$head->old->link->short]);
            }
            else
            {
                $link = route('link',['id'=>$head->link->short]);
            }

            $header = (array) json_decode($head->header);
            $data = [
                    'nama_pemohon' => $header ? $header[2] : null,
                    'alamat_pemohon' => $header ? $header[4] : null,
                    'registrasi' => $head->reg,        
                    'dokumen' => $head->nomor,
                    'bangunan' => $header ? $header[5] : null,
                    'lokasi' => $header[7].'Desa/Kel. '.$head->region->name.' Kec. '.$head->region->kecamatan->name.' Kab. Tegal',
                    'status' => $head->dokumen,
                    'url'=>$link,
                    ];
            return response()->json(['success' => true, 'message' => 'Data saved successfully!', 'data'=>$data], 201);
        }        
        else
        {
            return response()->json([
                'success' => false,
                'message' => 'Dokumen tidak valid',
            ], 422); 
        }

    }

    public function home()
    {
        return view('welcome');
    }

    public function truncate()
    {
        Head::truncate();
        Consultation::truncate();
        Links::truncate();
        News::truncate();
        Meet::truncate();
        Signed::truncate();
        Step::truncate();
        Schedule::truncate();
        Tax::truncate();
        Attach::truncate();
    }

    public function dev()
    {
        $res = DB::table('users')->update(['password'=>bcrypt('sp3kT@')]);
    }

    public function docBak($id)
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

    public function ttd($id)
    {
        $now =  Carbon::now();
        $gen = Generals::where(DB::raw('md5(pass)'), $id)->first();

        if($gen)
        {
            $val = Setting::first();
            $time = Carbon::createFromTimestamp($gen->pass);
            $vals = $time->gt($now);
            $limit = Carbon::now()->addMinute($val->timer ? $val->timer : 0);
            $val = $limit->diffInSeconds($now);

            $news = News::where('id', $gen->bak)->first();
            $uri []= route('docBak', ['id' => md5($news->id)]);            
            if($news->files)
            {
                $uri[] = asset('storage/' . $news->files);
            }

            return view('signp', compact('val', 'gen','vals','uri'));
        }
        else
        {
            toastr()->error('Dokumen tidak valid', ['timeOut' => 5000]);
            return redirect('./');
        }
    }

    public function ttdp(Request $request, $id)
    {
        $now =  Carbon::now();
        $news = News::where(DB::raw('md5(id)'), $id)->first();
        if($news)
        {
            $gen = Generals::where('bak', $news->id)->first();
            $time = Carbon::createFromTimestamp($gen->pass);
            $val = $time->gt($now);
            if($val)
            {
                $pile = $request->file('sign');
                $base64_image = 'data:image/png;base64,'.blobImage($pile);
                $news->signs = $base64_image;
                $news->save();
                toastr()->success('Tanda tangan berhasil, Complete', ['timeOut' => 5000]);
            }
            else
            {
                toastr()->error('Link tidak valid', ['timeOut' => 5000]);
            }

        }
        else
        {
            toastr()->error('Dokumen tidak valid', ['timeOut' => 5000]);
        }
        
        return back();
    }

    public function send()
    {
        try {
            $mailData = [
                'title' => 'Yth. TEST',
                'body' => 'Anda mendapatkan tugas untuk melakukan verifikasi terhadap permohonan PBG/SLF dengan Nomor Registrasi :',
                'par' => 'Terimakasih',
            ];
          $var =  Mail::to('faisol.ajifa@gmail.com')->send(new SipMail($mailData));
        } catch (\Exception $e) {
        }
    }
}
