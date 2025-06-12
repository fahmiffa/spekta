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
use App\Models\Consultation;
use Illuminate\Support\Facades\Validator;

class PendingController extends Controller
{
    public function __construct()
    {
        $this->middleware('IsPermission:pending');
    }

    public function index()
    {
        $da = Consultation::has('sign')->latest()->get();
        $data = "Berita Acara Konsultasi";
        return view('document.bak.home', compact('da', 'data'));
    }

    public function hold(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
                        'note' => 'required'
                    ]);

        if ($validator->fails()) {
            toastr()->error('Catatan Harus di isi', ['timeOut' => 5000]);
            return back();
        }

        $head = Head::where(DB::raw('md5(id)'), $id)->first();
        $head->hold = $head->hold ? null : 1;
        $head->hold_note = $request->note;
        $head->save();
        toastr()->success('Pending Berhasil', ['timeOut' => 5000]);
        return back();
    }
}
