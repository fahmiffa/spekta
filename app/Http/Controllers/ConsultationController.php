<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Mail\SipMail;
use App\Models\Consultation;
use App\Models\Head;
use App\Models\Links;
use App\Models\Meet;
use App\Models\News;
use App\Models\Role;
use App\Models\Schedule;
use App\Models\Signed;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Mail;
use PDF;
use QrCode;
use setasign\Fpdi\Fpdi;
use DB;
use Illuminate\Support\Facades\Log;


class ConsultationController extends Controller
{

    public function __construct()
    {
        $this->middleware('IsPermission:master_formulir');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $da = Consultation::latest()->get();
        $data = "Panugasan & Penjadwalan TPT/TPA";
        return view('konsultasi.index', compact('da', 'data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = "Panugasan & Penjadwalan TPT TPA";
        $doc = head::doesnthave('kons')->where('grant', 1)->latest()->get();
        $user = Role::whereIn('kode', ['TPT', 'TPA'])->get();
        return view('konsultasi.create', compact('data', 'user', 'doc'));
    }

    public function store(Request $request)
    {

        $pile = $request->file('pile');

        $rule = [
            'doc' => 'required',
            'notulen' => 'required',
            'konsultan' => 'required',
            'tanggal' => 'required',
            'timeStart' => 'required',
            'timeEnd' => 'required',
            'date' => 'required',
            'jenis' => 'required',
            'place' => 'required',            
        ];

        if($pile)
        {
            $rule = array_merge(['pile' => 'file|mimes:pdf|max:2048'],$rule);
        }

        $message = [
            'required' => 'Field ini harus diisi',
            'mimes' => 'Extension File invalid',
            'max' => 'File size max 2Mb',
        ];
        $request->validate($rule, $message);

        $intersection = array_intersect($request->notulen, $request->konsultan);

        if (count($intersection) > 0) {
            toastr()->error('Ada Ketua/Notulen di dalam input anggota konsultasi', ['timeOut' => 5000]);
            return back()->withInput();
        }

        if (count($request->notulen) > 2) {
            toastr()->error('Notulen maksimal 2', ['timeOut' => 5000]);
            return back()->withInput();
        }

        try {
            DB::beginTransaction();

            if(Consultation::where('head',$request->doc)->exists())
            {
                toastr()->error('Terjadi double inputan', ['timeOut' => 5000]);
                return redirect()->back(); 
            }
            
            $path = null;
            $kons = new Consultation;
            $kons->head = $request->doc;
            $kons->notulen = implode(",", $request->notulen);
    
            if ($pile) {
                $ext = $pile->getClientOriginalExtension();
                $path = $pile->storeAs(
                    'assets/konsultasi/' . time() . '_konsultasi.' . $ext, ['disk' => 'public']
                );
            }
    
            $kons->konsultan = implode(",", $request->konsultan);
            $kons->files = $path;
            if (!$kons->save()) {
                DB::rollback();
            }

            $sch = Schedule::where('head',$request->doc)->first();
    
            $ch = $sch ? $sch : new Schedule;
            $ch->head = $request->doc;
            $ch->jenis = $request->jenis;
            $ch->tanggal = $request->tanggal;
            $ch->waktu = $request->timeStart . '#' . $request->timeEnd . '#' . $request->date;
            $ch->tempat = $request->place . '#' . $request->place_des;
            $ch->keterangan = $request->content;
            if (!$ch->save()) {
                DB::rollback();
            }


            Signed::where('task', $kons->id)->where('type', 'member')->delete();
            foreach ($request->konsultan as $par) {
                $sign = new Signed;
                $sign->head = $request->doc;
                $sign->user = $par;
                $sign->task = $kons->id;
                $sign->type = 'member';
                if (!$sign->save()) {
                    DB::rollback();
                }
            }
    
            Signed::where('task', $kons->id)->where('type', 'lead')->delete();
            foreach ($request->notulen as $var) {
                $sign = new Signed;
                $sign->head = $request->doc;
                $sign->user = $var;
                $sign->task = $kons->id;
                $sign->type = 'lead';
                if (!$sign->save()) {
                    DB::rollback();
                }
            }
    
            
            shortLink($request->doc,'surat_undangan');
            
            DB::commit();
            
            if (env('MAIL')) {
                $this->mail($kons->head);
            }
            
            toastr()->success('Tambah Data berhasil', ['timeOut' => 5000]);

            return redirect()->route('consultation.index');
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::channel('server_log')->error($e->getMessage());
            DB::rollback();
            toastr()->error('Terjadi kesalahan modular', ['timeOut' => 5000]);
            return redirect()->back();       
        } catch (\Illuminate\Database\QueryException $e) {
            Log::channel('server_log')->error($e->getMessage());
            DB::rollback();
            toastr()->error('Terjadi kesalahan proses input', ['timeOut' => 5000]);
            return redirect()->back(); 
        } catch (\ErrorException $e) {
            Log::channel('server_log')->error($e->getMessage());
            DB::rollback();
            toastr()->error('Terjadi kesalahan inputan', ['timeOut' => 5000]);
            return redirect()->back(); 
        }

    }

    private function mail($head)
    {
        $doc = head::where('id', $head)->first();
        $header = json_decode($doc->header);
        foreach ($doc->sign as $value) {

            $mailData = [
                'title' => 'Yth. ' . $value->users->name,
                'body' => 'Anda mendapatkan tugas untuk melakukan verifikasi terhadap permohonan PBG/SLF dengan Nomor Registrasi :' . $header[0],
                'par' => 'Terimakasih',
            ];

            Mail::to($value->users->email)->send(new SipMail($mailData));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Consultation $consultation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Consultation $consultation)
    {
        $schedule = Schedule::where('head', $consultation->head)->first();
        $data = "Edit Konsultasi";
        $doc = head::where('grant', 1)->latest()->get();
        $user = Role::whereIn('kode', ['TPT', 'TPA'])->get();
        return view('konsultasi.create', compact('data', 'user', 'doc', 'consultation', 'schedule'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Consultation $consultation)
    {
        $rule = [
            'doc' => 'required',
            'notulen' => 'required',
            'konsultan' => 'required',
            'tanggal' => 'required',
            'timeStart' => 'required',
            'timeEnd' => 'required',
            'date' => 'required',
            'jenis' => 'required',
            'place' => 'required',
            'pile' => 'nullable|file|mimes:pdf|max:2048',
        ];

        $message = [
            'required' => 'Field ini harus diisi',
            'mimes' => 'Extension File invalid',
            'max' => 'File size max 2Mb',
        ];
        $request->validate($rule, $message);

        $intersection = array_intersect($request->notulen, $request->konsultan);

        if (count($intersection) > 0) {
            toastr()->error('Ada Ketua/Notulen di dalam input anggota konsultasi', ['timeOut' => 5000]);
            return back()->withInput();
        }

        if (count($request->notulen) > 2) {
            toastr()->error('Notulen maksimal 2', ['timeOut' => 5000]);
            return back()->withInput();
        }

        $kons = $consultation;
        $path = null;
        $pile = $request->file('pile');

        if ($pile) {
            $ext = $pile->getClientOriginalExtension();
            $path = $pile->storeAs(
                'assets/konsultasi/' . time() . '_konsultasi.' . $ext, ['disk' => 'public']
            );
        }
        $kons->notulen = implode(",", $request->notulen);
        $kons->head = $request->doc;
        $kons->konsultan = implode(",", $request->konsultan);
        // if($pile)
        // {
        //     $kons->files = $path;
        // }
        $kons->files = $path;
        $kons->save();

        $ch = Schedule::where('head', $consultation->head)->first();
        $ch->head = $request->doc;
        $ch->nomor = $request->nomor;
        $ch->jenis = $request->jenis;
        $ch->tanggal = $request->tanggal;
        $ch->waktu = $request->timeStart . '#' . $request->timeEnd . '#' . $request->date;
        $ch->tempat = $request->place . '#' . $request->place_des;
        $ch->keterangan = $request->content;
        $ch->save();

        Signed::where('task', $consultation->id)->where('type', 'member')->delete();

        foreach ($request->konsultan as $par) {
            $sign = new Signed;
            $sign->head = $request->doc;
            $sign->user = $par;
            $sign->task = $kons->id;
            $sign->type = 'member';
            $sign->save();
        }

        Signed::where('task', $consultation->id)->where('type', 'lead')->delete();

        foreach ($request->notulen as $var) {

            $sign = new Signed;
            $sign->head = $request->doc;
            $sign->user = $var;
            $sign->task = $kons->id;
            $sign->type = 'lead';
            $sign->save();
        }

        // shortLink($request->doc,'surat_undangan');

        toastr()->success('Update Data berhasil', ['timeOut' => 5000]);
        return redirect()->route('consultation.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Consultation $consultation)
    {
        $consultation->delete();

        $old = Head::where('id',$consultation->head)->first();     
        $old->grant = 0;
        $old->save();

        Schedule::where('head', $consultation->head)->delete();
        Signed::where('head', $consultation->head)->delete();
        News::where('head', $consultation->head)->delete();
        Meet::where('head', $consultation->head)->delete();

        toastr()->success('Rollback Data berhasil', ['timeOut' => 5000]);
        return back();
    }
}
