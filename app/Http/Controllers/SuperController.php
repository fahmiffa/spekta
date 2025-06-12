<?php

namespace App\Http\Controllers;

use App\Models\Attach;
use App\Models\Head;
use App\Models\Meet;
use App\Models\News;
use App\Models\Signed;
use App\Models\Tax;
use DB;
use Illuminate\Http\Request;
use App\Imports\RegionImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Village as Desa;
use App\Models\District;

class SuperController extends Controller
{
    public function __construct()
    {
        $this->middleware('IsPermission:master');
    }

    public function inputBak($id)
    {
        $dis  = District::all();
        $news = News::where(DB::raw('md5(head)'), $id)->first();
        $head = Head::where(DB::raw('md5(id)'), $id)->first();

        if ($news) {
            $data = "Dokumen " . $news->doc->nomor;
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

    public function destroyBak(Request $request, $id)
    {
        $item = News::where(DB::raw('md5(id)'), $id)->first();
        Signed::where('head', $item->head)->update(['bak' => null]);
        Head::where('head', $item->head)->update(['do' => 0]);
        // Meet::where('head', $item->head)->forceDelete();
        // Attach::where('head', $item->head)->forceDelete();
        // Tax::where('head', $item->head)->forceDelete();
        $item->forceDelete();
        toastr()->success('Hapus Berhasil', ['timeOut' => 5000]);
        return back();
    }

    public function hold(Request $request, $id)
    {
        $head = Head::where(DB::raw('md5(id)'), $id)->first();
        $head->hold = $head->hold ? null : 1;
        $head->save();
        toastr()->success('Pending Berhasil', ['timeOut' => 5000]);
        return back();
    }

    public function inputBarp($id)
    {
        $meet = Meet::where(DB::raw('md5(head)'), $id)->first();
        $head = Head::where(DB::raw('md5(id)'), $id)->first();

        if (!$meet) {
            $his = $head->barpTemp->whereNotNull('deleted_at');
            if ($his->count() > 0) {
                $meet = ($meet) ? $meet : $his[0];
            }
        }

        $data = "Dokumen " . $head->number;
        return view('document.barp.create', compact('data', 'head', 'meet'));
    }

    public function destroyBarp(Request $request, $id)
    {
        $item = Meet::where(DB::raw('md5(id)'), $id)->first();
        Signed::where('head', $item->head)->update(['bak' => null, 'barp' => null]);
        Head::where('head', $item->head)->update(['do' => 0]);
        // Attach::where('head', $item->head)->forceDelete();
        // Tax::where('head', $item->head)->forceDelete();
        $item->forceDelete();
        toastr()->success('Hapus Berhasil', ['timeOut' => 5000]);
        return back();
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx'
        ]);

        Excel::import(new RegionImport, $request->file('file'));
        toastr()->success('Import Berhasil', ['timeOut' => 5000]);
        return back();

    }

    public function reset()
    {
        toastr()->success('Reset Berhasil', ['timeOut' => 5000]);
        return back();
    }
}
