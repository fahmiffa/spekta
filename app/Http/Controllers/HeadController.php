<?php

namespace App\Http\Controllers;

use App\Models\Head as Verifikasi;
use Illuminate\Http\Request;
use Auth;
use DB;
use App\Models\Formulir;
use App\Models\Village;
use App\Models\Role;
use App\Models\User;
use App\Models\District;
use PDF;
use QrCode;
use Exception;
use App\Mail\SipMail;
use Mail;
use App\Models\Spj;
use App\Models\SpjSub;
use App\Models\PemohonHead;
use App\Models\SpjTemplate;
use App\Models\Signed;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;


class HeadController extends Controller
{

    public function __construct()
    {
        $this->middleware('IsPermission:master_formulir');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)    
    {
        $val = verifikasi::latest();
        // $val = verifikasi::withTrashed();
        // dd($val->pluck('id'));
        $key = $request->get('key');
        $opsi = $request->get('opsi');
        if($key)
        {
            $val = $val->where($opsi,$key);
        }        
        $da = $val->get();
        $data = "Verifikasi";
        return view('document.index',compact('da','data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = "Tambah Verifikasi";
        $dis  = District::all();
        $user = Role::whereIn('kode',['VL1', 'VL2', 'VL3'])->get(); 
        return view('document.create',compact('data','user','dis'));
    }

    public function approve(Request $request, $id)
    {
        $head = Verifikasi::where(DB::raw('md5(id)'),$id)->first();   
        if($head->kons)
        {
            toastr()->error('Dokumen Verifikasi sudah tahap konsultasi', ['timeOut' => 5000]);
        }
        else
        {
            $head->grant = 1;
            $head->tang = date('Y-m-d H:i:s');
            $head->save();
            genPDF($head->id,'verifikasi');
            toastr()->success('Verifikasi Data berhasil', ['timeOut' => 5000]);
        }
        return back();                
    }


    public function reject(Request $request, $id)
    {
        $old = Verifikasi::where(DB::raw('md5(id)'),$id)->first(); 
        if($old->kons)
        {
            toastr()->error('Dokumen Verifikasi sudah tahap konsultasi', ['timeOut' => 5000]);
        }
        else
        {
            $old->note = $request->noted;       
            $old->save(); 
            
            $head = new Verifikasi;
            $head->parent = $old->id;
            $head->head_id = ($old->parents) ? $old->parents->id : $old->id;
            $head->village = $old->village;
            $head->header = $old->header;
            $head->email = $old->email;
            $head->nomor = $old->nomor;
            $head->type = $old->type;
            $head->reg = $old->reg;
            $head->status = 5;
            $head->verifikator = $old->verifikator;
            $head->step = $old->step;
            $head->sekretariat = Auth::user()->id;
            $head->save();
    
            shortLink($head->id,'verifikasi');
    
            $old->delete();
    
            toastr()->success('Verifikasi Data berhasil', ['timeOut' => 5000]);
        }
        return back();     
    }

    public function open(Request $request, $id)
    {
        $old = Verifikasi::where(DB::raw('md5(id)'),$id)->first(); 
        $old->open = 1;
        $old->save();   

        toastr()->success('Dokumen berhasil dikirim ke Verifikator', ['timeOut' => 5000]);
        return back();   
     
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $rule = [                               
            'type'=> 'required',
            'verifikator'=>'required',
            'namaPemohon' => 'required',           
            'alamatPemohon'=> 'required',                                    
            'namaBangunan'=> 'required',                                    
            'alamatBangunan'=> 'required', 
            'pengajuan'=> 'required', 
            'fungsi'=> 'required_if:type,umum', 
            'koordinat'=> 'required_if:type,menara', 
            'noreg'=> ['required',
            Rule::unique('heads', 'reg')->whereNull('deleted_at'),], 
            'email'=> 'required', 
            'dis'=> 'required', 
            'des'=> 'required', 
            'hp'=> 'required',    
            'task'=> 'required', 
            ];
        $message = ['required'=>'Field ini harus diisi','noreg.unique'=>'Field ini sudah ada', 'required_if'=> 'Field :attribute harus diisi'];
        $request->validate($rule,$message);

        try {

            DB::beginTransaction();
            $ver = $request->verifikator;
            $ver = array_filter($ver, function($value) {
                return !is_null($value);
            });
    
            // validasi tahap
            if(count($ver) > 2)
            {
                toastr()->error('Verifikator maksimal 2', ['timeOut' => 5000]);
                return back()->withInput();
            }
        
            // validasi 2 tahap
            if(count($ver) > 1)
            {
                // case 1
                $VL2 = User::where('id',$ver[0])->where('role',Role::select('id')->where('kode','VL2')->pluck('id'))->exists();              
                $VL3 = User::where('id',$ver[1])->where('role',Role::select('id')->where('kode','VL3')->pluck('id'))->exists();  
                $case1 = ($VL2 && $VL3) ? true : false;
                // case 2
                $VL2s = User::where('id',$ver[1])->where('role',Role::select('id')->where('kode','VL2')->pluck('id'))->exists();              
                $VL3s = User::where('id',$ver[0])->where('role',Role::select('id')->where('kode','VL3')->pluck('id'))->exists();  
                $case2 = ($VL2s && $VL3s) ? true : false;
    
                if(!$case1 && !$case2)
                {
                    toastr()->error('Invalid verifikator', ['timeOut' => 5000]);
                    return back()->withInput();
                } 
    
            }
            // verifikator 1 tahap
            else
            {
                $VL1 = User::where('id',$ver[0])->where('role',Role::select('id')->where('kode','VL1')->pluck('id'))->exists();  
                if(!$VL1)
                {
                    toastr()->error('Invalid verifikator', ['timeOut' => 5000]);
                    return back()->withInput();
                }                 
            }        
    
            $header = [$request->noreg, $request->pengajuan, $request->namaPemohon, $request->hp, $request->alamatPemohon, $request->namaBangunan, $request->fungsi, $request->alamatBangunan, $request->koordinat, $request->land];                
    
            $head = new Verifikasi;
            $head->village = $request->des;
            $head->header = json_encode($header);
            $head->nomor = nomor();
            $head->reg = $request->noreg;
            $head->type = $request->type;
            $head->email = $request->email;
            $head->status = 5;
            $head->open = 1;
            $head->verifikator = implode(",",$ver);
            $head->step = $request->task;
            $head->sekretariat = Auth::user()->id;
            $head->save();

            if (!$head->save()) {
                DB::rollback();
            }
    
    
            $head->head_id = $head->id;
            $head->save();

    
            shortLink($head->id,'verifikasi');
    
            if (env('MAIL')) {
                $this->mail($head);
            }
        
            DB::commit();
            toastr()->success('Tambah Data berhasil', ['timeOut' => 5000]);
            return back();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollback();
            toastr()->error('Terjadi kesalahan modular', ['timeOut' => 5000]);
            return redirect()->route('verifikasi.index');       
        } catch (\Illuminate\Database\QueryException $e) {
            Log::channel('server_log')->error($e->getMessage());
            DB::rollback();
            toastr()->error('Terjadi kesalahan proses input', ['timeOut' => 5000]);
            return redirect()->route('verifikasi.index'); 
        } catch (\ErrorException $e) {
            Log::channel('server_log')->error($e->getMessage());
            DB::rollback();
            toastr()->error('Terjadi kesalahan inputan', ['timeOut' => 5000]);
            return redirect()->route('verifikasi.index'); 
        }

    }

    private function mail($head)
    {
        $doc = Verifikasi::where('id', $head->id)->first();
        $header = json_decode($doc->header);
        $users = explode(",", $doc->verifikator);        
        foreach ($users as $value) {
            $user = User::where('id', $value)->first();
            $mailData = [
                'title' => 'Yth. ' . $user->name,
                'body' => 'Anda mendapatkan tugas untuk melakukan pemeriksaan kelengkapan dokumen terhadap permohonan PBG/SLF dengan <br> Nomor Registrasi :' . $header[0].'<br> Nama Pemohon : '.$header[2],
                'par' => 'Terimakasih',
            ];


            Mail::to($user->email)->send(new SipMail($mailData));
        }
    }

    public function village(Request $request)
    {
        $da = Village::where('districts_id',$request->id)->pluck('name', 'id');
        return response()->json($da);
    }

    public function task(Request $request)
    {
        $val = $request->id == 1 ? ['VL1'] : ['VL2', 'VL3'];

        if($request->id == 1)
        {
            $one = User::where('status',1)->whereIn('role',Role::whereIn('kode',$val)->pluck('id')->toArray())->pluck('name','id');        
            $da = ['satu'=>$one];
            return response()->json($da);

        }
        else
        {
            $one = User::where('status',1)->whereIn('role',Role::whereIn('kode',['VL2'])->pluck('id')->toArray())->pluck('name','id'); 
            $two = User::where('status',1)->whereIn('role',Role::whereIn('kode',['VL3'])->pluck('id')->toArray())->pluck('name','id');        
            $da = ['satu'=>$one,'dua'=>$two];
            return response()->json($da);
        }

    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Verifikasi $verifikasi)
    {
        $role = Role::whereIn('kode',['VL1', 'VL2', 'VL3'])->pluck('id','kode')->toArray(); 
        $user = User::whereIn('role',array_values($role))->get(); 
        $dis  = District::all();
        $data = "Edit Verifikasi";
        return view('document.create',compact('data','verifikasi','user','dis','role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Verifikasi $verifikasi)
    {
        $rule = [                            
            'type'=> 'required',
            'verifikator'=>'required',
            'namaPemohon' => 'required',           
            'alamatPemohon'=> 'required',                                    
            'namaBangunan'=> 'required',                                    
            'alamatBangunan'=> 'required', 
            'pengajuan'=> 'required', 
            'fungsi'=> 'required', 
            'noreg'=> ['required',
            Rule::unique('heads', 'reg')->ignore($verifikasi->id)->whereNull('deleted_at'),], 
            'email'=> 'required',             
            'dis'=> 'required', 
            'des'=> 'required', 
            'task'=> 'required', 
            'hp'=> 'required',                                           
            ];
        $message = ['required'=>'Field ini harus diisi','unique'=>'Field ini sudah ada'];
        $request->validate($rule,$message);

        $ver = $request->verifikator;
        $ver = array_filter($ver, function($value) {
            return !is_null($value);
        });

        // validasi tahap
        if(count($ver) > 2)
        {
            toastr()->error('Verifikator maksimal 2', ['timeOut' => 5000]);
            return back()->withInput();
        }
    
        // validasi 2 tahap
        if(count($ver) > 1)
        {
            // case 1
            $VL2 = User::where('id',$ver[0])->where('role',Role::select('id')->where('kode','VL2')->pluck('id'))->exists();              
            $VL3 = User::where('id',$ver[1])->where('role',Role::select('id')->where('kode','VL3')->pluck('id'))->exists();  
            $case1 = ($VL2 && $VL3) ? true : false;
            // case 2
            $VL2s = User::where('id',$ver[1])->where('role',Role::select('id')->where('kode','VL2')->pluck('id'))->exists();              
            $VL3s = User::where('id',$ver[0])->where('role',Role::select('id')->where('kode','VL3')->pluck('id'))->exists();  
            $case2 = ($VL2s && $VL3s) ? true : false;

            if(!$case1 && !$case2)
            {
                toastr()->error('Invalid verifikator', ['timeOut' => 5000]);
                return back()->withInput();
            } 

        }
        // verifikator 1 tahap
        else
        {
            $VL1 = User::where('id',$ver[0])->where('role',Role::select('id')->where('kode','VL1')->pluck('id'))->exists();  
            if(!$VL1)
            {
                toastr()->error('Invalid verifikator', ['timeOut' => 5000]);
                return back()->withInput();
            }                 
        }

        $tipe = $request->type == 'umum' ? $request->fungsi : $request->koordinat;        
        $header = [$request->noreg, $request->pengajuan, $request->namaPemohon, $request->hp, $request->alamatPemohon, $request->namaBangunan, $request->fungsi, $request->alamatBangunan, $request->koordinat, $request->land];                
        

        $verifikasi->village = $request->des;
        $verifikasi->header = json_encode($header);     
        $verifikasi->type = $request->type;
        $verifikasi->reg = $request->noreg;
        $verifikasi->email = $request->email;
        $verifikasi->verifikator = implode(",",$ver);
        $verifikasi->step = count($ver);
        $verifikasi->save();

        toastr()->success('Update Data berhasil', ['timeOut' => 5000]);
        return redirect()->route('verifikasi.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Verifikasi $verifikasi)
    {
        $verifikasi->delete();
        toastr()->success('Delete Data berhasil', ['timeOut' => 5000]);
        return back();
    }

    public function spjDoc()
    {
        $doc = Spj::latest()->get();
        return view('spj.index',compact('doc'));
    }

    public function spjCreate(Request $request)
    {

        $user = User::where('status',1)->whereIn('role', Role::where('kode','KB')->pluck('id'))->get();
        
        $title = 'SPJ Rapat Pleno PBG';
        $pemohon = PemohonHead::WhereHas('bak',function($q){
            $q->where('grant',1);
        })->get();

        return view('spj.create',compact('pemohon','title','user'));
    }

    public function template()
    {
        $template = SpjTemplate::all();
        return view('spj.template.index',compact('template'));
    }

    public function templateAdd()
    {
        return view('spj.template.create');
    }

    public function templateEdit($id)
    {
        $template = SpjTemplate::where(DB::raw('md5(id)'),$id)->firstOrFail(); 
        return view('spj.template.create',compact('template'));
    }

    public function templateStore(Request $request)
    {
        $rule = [                            
            'tipe'=> 'required',
            'field'=>'required', 
            ];
        $message = ['required'=>'Field ini harus diisi'];
        $request->validate($rule,$message);

        $con = $request->con;

        $template = SpjTemplate::where(DB::raw('md5(id)'),$con)->first(); 

        $doc = $con ? $template : new SpjTemplate;
        $doc->doc = $request->tipe;
        $doc->field = $request->field;
        $doc->save();

        toastr()->success('Tambah Data berhasil', ['timeOut' => 5000]);
        return redirect()->route('spj.template');


    }

    public function spjEdit($id)
    {
        $doc        = Spj::where(DB::raw('md5(id)'),$id)->firstOrFail(); 
        $head       = $doc->sub->pluck('head')->toArray();
        $filter     = $doc->type == 'survey_pbg' ? ['TPT','TPA'] : ['KB'];
        $user       = User::select('id','name')->where('status',1)->whereIn('role', Role::whereIn('kode',$filter)->pluck('id'))->get();
       $pemohon = PemohonHead::WhereHas('bak',function($q){
            $q->where('grant',1);
        })->get();

        $da = [];
        $sign = Signed::whereIn('head',$head);

        foreach($sign->get() as $row)
        {
            array_push($da,$row->users->name);
        }

        $da = array_unique($da);

        return view('spj.create',compact('pemohon','user','doc','head','da'));
    }

    public function spjPreview($id)
    {
        $doc = Spj::where(DB::raw('md5(id)'),$id)->firstOrFail(); 
        $title = 'SPJ '.strtoupper(str_replace('pbg','PBG',str_replace('_',' ',$doc->type)));

        $uri[] = route('spj.doc', ['id' => $id]);

        if($doc->pile)
        {
            $uri[] = asset('storage/' . $doc->pile);
        }

        return view('document.embeds', compact('uri', 'title'));
    }

    public function spjFile($id)
    {
        $doc = Spj::where(DB::raw('md5(id)'),$id)->firstOrFail(); 

        $pemohon = PemohonHead::whereIn('head',$doc->sub->pluck('head'))->get();

        $da = [];
        $sign = Signed::whereIn('head',$doc->sub->pluck('head'));

        foreach($sign->get() as $row)
        {
            array_push($da,$row->users->name);
        }

        $da = array_unique($da);

        $hari = hari($doc->program);
        $tanggal = dateID($doc->time);
        $title = $doc->type;

        if($doc->type == "rapat_pleno")
        {
            $template = SpjTemplate::where('doc',$doc->type)->get();

        }
        else
        {
            $template = SpjTemplate::where('doc',$doc->type)->firstOrFail();
        }


        $data = compact('pemohon','hari','tanggal','template','title','doc','da');
        $view = 'spj.doc.'.$doc->type;
        // return view($view,$data);
        $pdf = PDF::loadView($view, $data)->setPaper('legal', 'potrait');
        return $pdf->stream();
    }

    public function spjStore(Request $request)
    {

        $rule = [                            
            'pemohon'=> 'required',
            'tanggal'=>'required', 
            'program'=>'required', 
            'pelapor'=>'required', 
            'tipe'=>'required',        
            'pile' => 'nullable|file|mimes:pdf|max:5120',                                
            ];
        $message = [
            'required'=>'Field ini harus diisi',
            'mimes' => 'Extension File invalid',
            'max' => 'File size max 2Mb',
            'uploaded' => 'File size max 2Mb',
            ];
        $request->validate($rule,$message);

        try {
            DB::beginTransaction();

            $doc = $request->doc;
            $path = null;
            $pile = $request->file('pile');
            $in = $request->has('in');
            if ($pile && $in) {
                $ext = $pile->getClientOriginalExtension();
                $path = $pile->storeAs(
                    'assets/spj/' . time() . '_spj.' . $ext, ['disk' => 'public']
                );
            }
    
            $doc        = Spj::where(DB::raw('md5(id)'),$doc)->first(); 

            $spj           = $doc ? $doc : new Spj;
            $spj->report   = $request->pelapor;
            $spj->type     = $request->tipe;
            if($in)
            {
                $spj->pile     = $path;
            }
            $spj->survey   = $request->survey ? $request->survey : null;
            $spj->time     = $request->tanggal;
            $spj->program  = $request->program;
            $spj->note     = $request->content;
            $spj->extend   = $request->plus ? json_encode($request->plus) : null;
            if (!$spj->save()) {
                DB::rollback();
            }

            if($doc)
            {
                SpjSub::where('spj',$spj->id)->delete();
            }
    
            foreach($request->pemohon as $var)
            {
                $sub = new SpjSub;
                $sub->spj = $spj->id;
                $sub->head = $var;
                if (!$sub->save()) {
                    DB::rollback();
                }
            }

            DB::commit();
            toastr()->success('Tambah Data berhasil', ['timeOut' => 5000]);
            return redirect()->route('spj.index');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::channel('server_log')->error($e->getMessage());
            DB::rollback();
            toastr()->error('Terjadi kesalahan modular', ['timeOut' => 5000]);
            return redirect()->back();       
        } catch (\Illuminate\Database\QueryException $e) {
            Log::channel('server_log')->error($e->getMessage());
            DB::rollback();
            dd($e);
            toastr()->error('Terjadi kesalahan proses input', ['timeOut' => 5000]);
            return redirect()->back(); 
        } catch (\ErrorException $e) {
            Log::channel('server_log')->error($e->getMessage());
            DB::rollback();
            toastr()->error('Terjadi kesalahan inputan', ['timeOut' => 5000]);
            return redirect()->back(); 
        }


    }

    public function spjDel(Request $request, $id)
    {
       $doc        = Spj::where(DB::raw('md5(id)'),$id)->firstOrFail(); 
       $run = SpjSub::where('spj',$doc->id)->delete();
       $doc->delete();
       toastr()->success('Hapus Data berhasil', ['timeOut' => 5000]);
       return back();
    }

    public function spjData(Request $request)
    {
        $filter = $request->da == 'survey_pbg' ? ['TPT','TPA'] : ['KB'];

        $user = User::select('id','name')->where('status',1)->whereIn('role', Role::whereIn('kode',$filter)->pluck('id'))->get();

        $da = [];
        if($request->sel)
        {
            $pemohon = Signed::whereIn('head',$request->sel);

            foreach($pemohon->get() as $row)
            {
                array_push($da,$row->users->name);
            }
        }

        $data = ['da'=>array_unique($da), 'pelapor'=>$user];

        return response()->json($data);
    }

}
