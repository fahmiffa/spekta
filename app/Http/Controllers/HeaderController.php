<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;
use App\Models\Head;
use DB;
use PDF;
use App\Models\Meet;
use App\Models\Signed;

class HeaderController extends Controller
{
    public function __construct()
    {
        $this->middleware('IsPermission:verifikasi_bak');
    }

    public function validasi($id)
    {
        $head = Head::where(DB::raw('md5(id)'), $id)->first();
        return view('document.pdf', compact('head'));
    }

    public function ba()
    {        
        $val = head::whereHas('bak', function ($q) {
            $q->where('status', 1);
        })
        ->whereHas('barp', function ($q) {
            $q->where('status', 1);
        })->latest();
        $da = $val->get();
        $data = "Berita Acara";
        $ver = true;
        return view('document.ba',compact('da','data','ver'));
    }

    public function baSign($id)
    {
        $head = Head::where(DB::raw('md5(id)'),$id)->first();   
        $bak = $head->bak;
        $barp = $head->barp;

        if(!$bak || $bak->status != 1)
        {
            toastr()->error('Dokumen BAK belum di publish', ['timeOut' => 5000]);
            return back();
        }

        if(!$barp || $barp->status != 1)
        {
            toastr()->error('Dokumen BARP belum di publish', ['timeOut' => 5000]);
            return back();
        }
  
        $single = true;   
        $title = $head->bak->primary == 'TPT' ? 'Tanda Tangan Dokumen' : 'Verifikasi Dokumen';
        $kabid = true;
        return view('document.sign',compact('single','title','head','kabid'));
    }

    public function baSigned(Request $request, $id)
    {
        $pile = $request->file('sign');
        $bak = News::where(DB::raw('md5(head)'), $id)->first();
        
        if($request->type == 0)
        {
            if($bak->primary == 'TPT')
            {
                $base64_image = 'data:image/png;base64,'.blobImage($pile);
                $bak->sign = $base64_image;
            }
            $bak->grant = 1;
            $bak->save();
            toastr()->success('Tanda tangan berhasil Dokumen BAK', ['timeOut' => 5000]);
        }
        else
        {
            if($bak && $bak->grant == 0)
            {
                toastr()->error('Dokumen BAK belum di setujui', ['timeOut' => 5000]);
            }
            else
            {
                $barp = Meet::where(DB::raw('md5(head)'), $id)->first();
                if($bak->primary == 'TPT')
                {
                    $base64_image = 'data:image/png;base64,'.blobImage($pile);
                    $barp->sign = $base64_image;
                }
                $barp->grant = 1;
                $barp->save();

                $head = Head::where(DB::raw('md5(id)'), $id)->first();
                $head->do = 1;
                $head->save();

                toastr()->success('Tanda tangan berhasil BAK', ['timeOut' => 5000]);
            }
        }

        return back();
    }

    public function baVer(Request $request, $id)
    {
        $head = Head::where(DB::raw('md5(id)'), $id)->first();

        if($head)
        {   
            $bak = $head->bak;
            $bak->grant = 1;
            $bak->save();  
            
            $barp = $head->barp;
            $barp->grant = 1;
            $barp->save();  

            $head->do = 1;
            $head->save();
            toastr()->success('Verifikasi Dokumen Berhasil', ['timeOut' => 5000]);
        }
        else
        {
            toastr()->error('Dokumen verifikasi invalid', ['timeOut' => 5000]); 
        }

        return back();
    }

    public function baReject(Request $request, $id)
    {
        if($request->type == 0)
        {
            $bak = News::where(DB::raw('md5(head)'), $id)->first();
            $barp = Meet::where('head', $bak->head)->first();

            $bak->reason = $request->noted;
            $bak->save();            

            $bak->delete();
            $barp->delete();            
            Signed::where(DB::raw('md5(head)'),$id)->update(['bak' => null, 'barp'=>null]);
        }
        else
        {
            $barp = Meet::where(DB::raw('md5(head)'), $id)->first();
            $barp->reason = $request->noted;
            $barp->save();

            $barp->delete();
            Signed::where(DB::raw('md5(head)'),$id)->update(['barp' => null]);
        }

        toastr()->success('Dokumen di tolak berhasil', ['timeOut' => 5000]);
        return redirect()->route('ba.verifikasi');
    }

    public function bak()
    {
        $val = News::where('status',1)->latest();
        $da = $val->get();
        $data = "Berita Acara Konsultasi";
        $ver = true;
        return view('document.bak.index',compact('da','data','ver'));
    }

    public function docBak($id)
    {
        $news = News::where(DB::raw('md5(id)'),$id)->first(); 
        $head = $news->doc;
        $data = compact('news','head');

        $pdf = PDF::loadView('document.bak.doc.index', $data)->setPaper('legal', 'potrait');    
        return $pdf->stream();

        return view('document.bak.doc.index',$data);    
    }

    public function approveBak(Request $request, $id)
    {
        $head = News::where(DB::raw('md5(id)'),$id)->first();   

        if($request->has('sign'))
        {
            $head->sign = $request->sign;
        }
        $head->grant = 1;
        $head->save();
        toastr()->success('Verifikasi Data berhasil', ['timeOut' => 5000]);
        return back();                
    }

    public function rejectBak(Request $request, $id)
    {
        $head = News::where(DB::raw('md5(id)'),$id)->first();   
        $head->reason = $request->noted;
        $head->save();

        $head->delete();
        toastr()->success('Dokumen di tolak berhasil', ['timeOut' => 5000]);
        return back();                
    }

    public function barp()
    {
        $val = Meet::where('status',1)->latest();
        $da = $val->get();
        $data = "Berita Acara Rapat Pleno";
        $ver = true;
        return view('document.barp.index',compact('da','data','ver'));
    }

    public function docBarp($id)
    {
        $meet = Meet::where(DB::raw('md5(id)'),$id)->first(); 
        $news = $meet->doc->bak;
        $head = $meet->doc;
        $data = compact('news','head','meet');

        $pdf = PDF::loadView('document.barp.doc.index', $data)->setPaper('legal', 'potrait');   
        // return view('document.barp.doc.index', $data);
        return $pdf->stream();    
    }

    public function approveBarp(Request $request, $id)
    {
        $head = Meet::where(DB::raw('md5(id)'),$id)->first();   
        if($request->has('sign'))
        {
            $head->sign = $request->sign;
        }
        $head->grant = 1;
        $head->save();
        toastr()->success('Verifikasi Data berhasil', ['timeOut' => 5000]);
        return back();                
    }

    public function rejctBarp(Request $request, $id)
    {
        $head = Meet::where(DB::raw('md5(id)'),$id)->first();   
        $head->reason = $request->noted;
        $head->save();

        $head->delete();
        toastr()->success('Dokumen di tolak berhasil', ['timeOut' => 5000]);      
        return back();        
    }
}
