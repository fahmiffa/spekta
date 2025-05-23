<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\Formulir;
use App\Models\Head;
use App\Models\Step;
use App\Models\Verification;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class VerificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('IsPermission:doc_formulir');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $val = Head::withTrashed()->latest();
        $val = Head::latest();
        $da = $val->get();
        $data = "Dokumen";
        return view('verifikator.index', compact('da', 'data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $doc = Head::all();
        $data = "Tambah Formulir";
        return view('document.verifikasi.create', compact('data', 'doc'));
    }

    public function step($id)
    {

        $head = Head::where(DB::raw('md5(id)'), $id)->first();
        $vl3 = Step::where(DB::raw('md5(head)'), $id)->where('kode', 'VL3')->first();
        $vl2 = Step::where(DB::raw('md5(head)'), $id)->where('kode', 'VL2')->first();

        $kode = Auth::user()->roles->kode;
        $step = Step::where(DB::raw('md5(head)'), $id)->where('kode', $kode)->where('status', 1)->first();

        if ($head->grant == 1) {
            toastr()->error('Dokumen dalam proses', ['timeOut' => 5000]);
            return redirect()->route('verification.index');
        }

        if ($head->open == 0) {
            toastr()->error('Dokumen belum di buka', ['timeOut' => 5000]);
            return redirect()->route('verification.index');
        }

        $doc = Formulir::where('name', $head->type)->first();
        $dis = District::all();
        $data = 'Verifikasi ' . $head->type;

        return view('document.verifikasi.index', compact('head', 'doc', 'data', 'head', 'dis', 'vl2', 'vl3'));
    }

    public function modif($id)
    {

        $head = Head::where(DB::raw('md5(id)'), $id)->first();
        $doc = Formulir::where('name', 'umum')->first();
        $dis = District::all();
        $data = 'Edit Verifikasi ' . $head->type;

        return view('document.umum.edit', compact('head', 'doc', 'data', 'head', 'dis'));
    }

    public function next(Request $request, $id)
    {

        $head = Head::where(DB::raw('md5(id)'), $id)->first();
        $step = Step::where(DB::raw('md5(head)'), $id)->first();

        $level = Auth::user()->roles->kode;

        try {

            DB::beginTransaction();

            if ($request->itemDa) {
                $da['item'] = $request->itemDa;
                $da['saranItem'] = $request->saranItemDa;
            }

            if ($request->subDa) {
                $subda = $request->subDa;

                foreach ($subda as $key => $value) {
                    $subDa[] = [
                        'title' => $key,
                        'value' => $value,
                        'saran' => $request->saranSubDa,
                    ];
                }
                $da['sub'] = $subDa;
                $item['dokumen_administrasi'] = $da;
            }

            if ($request->itemDt) {
                $dt['item'] = $request->itemDt;
            }

            if ($request->saranItemDt) {
                $dt['saranItem'] = $request->saranItemDt;
            }

            if ($request->subDt) {
                $subdt = $request->subDt;

                foreach ($subdt as $key => $value) {
                    $subDt[] = [
                        'title' => $key,
                        'value' => $value,
                        'saran' => $request->saranSubDt,
                    ];
                }
                $dt['sub'] = $subDt;
                $type = ($head->type == 'umum') ? 'dokumen_teknis' : 'persyaratan_teknis';
                $item['' . $type . ''] = $dt;
            }

            if ($request->itemDpl) {
                $dpl['item'] = $request->itemDpl;
            }

            if ($request->saranItemDpl) {
                $dpl['saranItem'] = $request->saranItemDpl;
            }

            if ($request->subDpl) {
                $subdpl = $request->subDpl;

                foreach ($subdpl as $key => $value) {
                    $subDpl[] = [
                        'title' => $key,
                        'value' => $value,
                        'saran' => $request->saranSubDpl,
                    ];
                }
                $dpl['sub'] = $subDpl;

                $item['dokumen_pendukung_lainnya'] = $dpl;
            }

            $head->saran = $request->content;
            $head->status = 2;
            if (!$head->save()) {
                DB::rollback();
            }

            if (!$step) {
                $step = new Step;
            }

            if ($request->nameOther) {
                $other = $request->nameOther;

                foreach ($other as $key => $value) {
                    if ($value) {
                        $others[] = [
                            'name' => $value,
                            'value' => $request->item[$key],
                            'saran' => $request->saranOther[$key],
                        ];
                    } else {
                        $others = null;
                    }
                }
                $step->other = $others ? json_encode($others) : null;
            }
            $step->kode = $level;
            $step->head = $head->id;
            $step->item = json_encode($item);
            if (!$step->save()) {
                DB::rollback();
            }

            DB::commit();
            toastr()->success('Input Complete', ['timeOut' => 5000]);
            return redirect()->route('verification.index');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollback();
            dd($e);
            return back()->with('status', 'Terjadi kesalahan di modular');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollback();
            dd($e);
            return back()->with('status', 'Terjadi kesalahan dalam melanjutkan transaksi');
        } catch (\ErrorException $e) {
            dd($e);
            DB::rollback();
            return back()->with('status', 'Terjadi kesalahan dalam melakukan proses transaksi');
        }
    }

    public function pub(Request $request, $id)
    {

        $head = Head::where(DB::raw('md5(id)'), $id)->first();
        $step = Step::where(DB::raw('md5(head)'), $id)->first();

        $level = Auth::user()->roles->kode;

        try {

            DB::beginTransaction();

            if ($request->itemDa) {
                $da['item'] = $request->itemDa;
                $da['saranItem'] = $request->saranItemDa;
            }

            if ($request->subDa) {
                $subda = $request->subDa;

                foreach ($subda as $key => $value) {
                    $subDa[] = [
                        'title' => $key,
                        'value' => $value,
                        'saran' => $request->saranSubDa,
                    ];
                }
                $da['sub'] = $subDa;
                $item['dokumen_administrasi'] = $da;
            }

            if ($request->itemDt) {
                $dt['item'] = $request->itemDt;
            }

            if ($request->saranItemDt) {
                $dt['saranItem'] = $request->saranItemDt;
            }

            if ($request->subDt) {
                $subdt = $request->subDt;

                foreach ($subdt as $key => $value) {
                    $subDt[] = [
                        'title' => $key,
                        'value' => $value,
                        'saran' => $request->saranSubDt,
                    ];
                }
                $dt['sub'] = $subDt;
                $type = ($head->type == 'umum') ? 'dokumen_teknis' : 'persyaratan_teknis';
                $item['' . $type . ''] = $dt;
            }

            if ($request->itemDpl) {
                $dpl['item'] = $request->itemDpl;
            }

            if ($request->saranItemDpl) {
                $dpl['saranItem'] = $request->saranItemDpl;
            }

            if ($request->subDpl) {
                $subdpl = $request->subDpl;

                foreach ($subdpl as $key => $value) {
                    $subDpl[] = [
                        'title' => $key,
                        'value' => $value,
                        'saran' => $request->saranSubDpl,
                    ];
                }
                $dpl['sub'] = $subDpl;

                $item['dokumen_pendukung_lainnya'] = $dpl;
            }

            $head->saran = $request->content;
            $head->status = 1;
            if (!$head->save()) {
                DB::rollback();
            }

            if (!$step) {
                $step = new Step;
            }

            if ($request->nameOther) {
                $other = $request->nameOther;

                foreach ($other as $key => $value) {
                    if ($value) {
                        $others[] = [
                            'name' => $value,
                            'value' => $request->item[$key],
                            'saran' => $request->saranOther[$key],
                        ];
                    } else {
                        $others = null;
                    }
                }
                $step->other = $others ? json_encode($others) : null;
            }
            $step->kode = $level;
            $step->head = $head->id;
            $step->item = json_encode($item);
            if (!$step->save()) {
                DB::rollback();
            }

            DB::commit();
            toastr()->success('Input Complete', ['timeOut' => 5000]);
            return redirect()->route('verification.index');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollback();
            dd($e);
            return back()->with('status', 'Terjadi kesalahan di modular');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollback();
            dd($e);
            return back()->with('status', 'Terjadi kesalahan dalam melanjutkan transaksi');
        } catch (\ErrorException $e) {
            dd($e);
            DB::rollback();
            return back()->with('status', 'Terjadi kesalahan dalam melakukan proses transaksi');
        }
    }

    public function back(Request $request, $id)
    {
        $head = Head::where(DB::raw('md5(id)'), $id)->first();
        $step = Step::where(DB::raw('md5(head)'), $id)->first();

        $old = json_decode($step->item);

        if ($head->status == 2) {
            $new = (array) json_decode($step->item);
            Arr::forget($new, 'dokumen_pendukung_lainnya');
            $item = $old->dokumen_pendukung_lainnya->item;
            $saranItem = $old->dokumen_pendukung_lainnya->saranItem;
            $sub = $old->dokumen_pendukung_lainnya->sub;
            $saranSub = $old->dokumen_pendukung_lainnya->saranSub;

            foreach ($saranItem as $key => $value) {
                $data_history['saranItem[' . $key . ']'] = $value;
            }

            foreach ($item as $key => $value) {
                $data_history['item[' . $key . ']'] = $value;
            }

            foreach ($sub as $key => $value) {
                $data_history['sub[' . $key . ']'] = $value;
            }

            foreach ($saranSub as $key => $value) {
                $data_history['saranSub[' . $key . ']'] = $value;
            }

            $step->item = json_encode($new);
            $step->save();

            $head->status = 3;
            $head->save();

            // dd($data_history);
        } elseif ($head->status == 3) {
            $new = (array) json_decode($step->item);
            Arr::forget($new, 'dokumen_teknis');
            $sub = $old->dokumen_teknis->sub;
            $saranSub = $old->dokumen_teknis->saranSub;
            foreach ($sub as $key => $value) {
                $data_history['sub[' . $key . ']'] = $value;
            }

            foreach ($saranSub as $key => $value) {
                $data_history['saranSub[' . $key . ']'] = $value;
            }

            $step->item = json_encode($new);
            $step->save();

            $head->status = 4;
            $head->save();
        } elseif ($head->status == 4) {
            $item = $old->dokumen_administrasi->item;
            $saranItem = $old->dokumen_administrasi->saranItem;

            foreach ($saranItem as $key => $value) {
                $data_history['saranItem[' . $key . ']'] = $value;
            }

            foreach ($item as $key => $value) {
                $data_history['item[' . $key . ']'] = $value;
            }

            if (isset($old->dokumen_administrasi->sub)) {
                $sub = $old->dokumen_administrasi->sub;
                foreach ($sub as $key => $value) {
                    $data_history['sub[' . $key . ']'] = $value;
                }
            }

            if (isset($old->dokumen_administrasi->saranSub)) {
                $saranSub = $old->dokumen_administrasi->saranSub;
                foreach ($saranSub as $key => $value) {
                    $data_history['saranSub[' . $key . ']'] = $value;
                }
            }

            $step->delete();

            $head->status = 5;
            $head->save();
        }

        return back()->withInput($data_history);
        // return redirect()->route('verifikasi.create')->withInput($data_history);
    }

    public function nexts(Request $request, $id)
    {
        $head = Head::where(DB::raw('md5(id)'), $id)->first();
        $step = Step::where(DB::raw('md5(head)'), $id)->first();
        $level = Auth::user()->roles->kode;
        $vl3 = Step::where(DB::raw('md5(head)'), $id)->where('kode', 'VL3')->first();
        $vl2 = Step::where(DB::raw('md5(head)'), $id)->where('kode', 'VL2')->first();

        if ($level == 'VL2') {

            $step = ($vl2) ? $vl2 : new Step;

            if ($head->type == 'umum') {
                if ($request->itemdt) {
                    $da['item'] = $request->itemdt;
                }

                if ($request->saranItemdt) {
                    $da['saranItem'] = $request->saranItemdt;
                }

                if ($request->subdt) {
                    $subdt = $request->subdt;

                    foreach ($subdt as $key => $value) {
                        $subDt[] = [
                            'title' => $key,
                            'value' => $value,
                            'saran' => $request->saranSubdt,
                        ];
                    }
                    $da['sub'] = $subDt;
                }

                $item['dokumen_teknis'] = $da;

                if ($request->itemdl) {
                    $da['item'] = $request->itemdl;
                }

                if ($request->saranItemdl) {
                    $da['saranItem'] = $request->saranItemdl;
                }

                if ($request->subdl) {
                    $subdl = $request->subdl;

                    foreach ($subdl as $key => $value) {
                        $subDl[] = [
                            'title' => $key,
                            'value' => $value,
                            'saran' => $request->saranSubdl,
                        ];
                    }
                    $da['sub'] = $subDl;
                }

                $item['dokumen_pendukung_lainnya'] = $da;

                if ($request->nameOther) {
                    $other = $request->nameOther;

                    foreach ($other as $key => $value) {
                        if ($value) {
                            $others[] = [
                                'name' => $value,
                                'value' => $request->item[$key],
                                'saran' => $request->saranOther[$key],
                            ];
                        } else {
                            $others = null;
                        }
                    }
                    $step->other = $others ? json_encode($others) : null;
                }
            }

            if ($head->type == 'menara') {

                if ($request->itemDt) {
                    $dt['item'] = $request->itemDt;
                }

                if ($request->saranItemDt) {
                    $dt['saranItem'] = $request->saranItemDt;
                }

                if ($request->subDt) {
                    $subdt = $request->subDt;

                    foreach ($subdt as $key => $value) {
                        $subDt[] = [
                            'title' => $key,
                            'value' => $value,
                            'saran' => $request->saranSubDt,
                        ];
                    }
                    $dt['sub'] = $subDt;
                }

                $item['persyaratan_teknis'] = $dt;
            }

            $step->kode = $level;
            $step->head = $head->id;
            $step->item = json_encode($item);
            $step->save();

            $head->saran = $request->content;
        }

        if ($level == 'VL3') {

            $step = ($vl3) ? $vl3 : new Step;

            if ($request->itemDa) {
                $da['item'] = $request->itemDa;
                $da['saranItem'] = $request->saranItemDa;
            }

            if ($request->subDa) {
                $subda = $request->subDa;

                foreach ($subda as $key => $value) {
                    $subDa[] = [
                        'title' => $key,
                        'value' => $value,
                        'saran' => $request->saranSubDa,
                    ];
                }
                $da['sub'] = $subDa;
            }
            $item['dokumen_administrasi'] = $da;

            $step->head = $head->id;
            $step->item = json_encode($item);
            $step->kode = $level;
            $step->save();
        }

        $vl2 = Step::where(DB::raw('md5(head)'), $id)->where('kode', 'VL2')->first();
        $vl3 = Step::where(DB::raw('md5(head)'), $id)->where('kode', 'VL3')->first();
        $head->status = ($vl2 && $vl3) ? 3 : 3;
        $head->save();

        toastr()->success('Input Complete', ['timeOut' => 5000]);
        return redirect()->route('verification.index');

    }

    public function pubs(Request $request, $id)
    {
        $head = Head::where(DB::raw('md5(id)'), $id)->first();
        $step = Step::where(DB::raw('md5(head)'), $id)->first();
        $level = Auth::user()->roles->kode;
        $vl3 = Step::where(DB::raw('md5(head)'), $id)->where('kode', 'VL3')->first();
        $vl2 = Step::where(DB::raw('md5(head)'), $id)->where('kode', 'VL2')->first();

        if ($level == 'VL2') {

            $step = ($vl2) ? $vl2 : new Step;

            if ($head->type == 'umum') {
                if ($request->itemdt) {
                    $da['item'] = $request->itemdt;
                }

                if ($request->saranItemdt) {
                    $da['saranItem'] = $request->saranItemdt;
                }

                if ($request->subdt) {
                    $subdt = $request->subdt;

                    foreach ($subdt as $key => $value) {
                        $subDt[] = [
                            'title' => $key,
                            'value' => $value,
                            'saran' => $request->saranSubdt,
                        ];
                    }
                    $da['sub'] = $subDt;
                }

                $item['dokumen_teknis'] = $da;

                if ($request->itemdl) {
                    $da['item'] = $request->itemdl;
                }

                if ($request->saranItemdl) {
                    $da['saranItem'] = $request->saranItemdl;
                }

                if ($request->subdl) {
                    $subdl = $request->subdl;

                    foreach ($subdl as $key => $value) {
                        $subDl[] = [
                            'title' => $key,
                            'value' => $value,
                            'saran' => $request->saranSubdl,
                        ];
                    }
                    $da['sub'] = $subDl;
                }

                $item['dokumen_pendukung_lainnya'] = $da;

                if ($request->nameOther) {
                    $other = $request->nameOther;

                    foreach ($other as $key => $value) {
                        if ($value) {
                            $others[] = [
                                'name' => $value,
                                'value' => $request->item[$key],
                                'saran' => $request->saranOther[$key],
                            ];
                        } else {
                            $others = null;
                        }
                    }
                    $step->other = $others ? json_encode($others) : null;
                }
            }

            if ($head->type == 'menara') {

                if ($request->itemDt) {
                    $dt['item'] = $request->itemDt;
                }

                if ($request->saranItemDt) {
                    $dt['saranItem'] = $request->saranItemDt;
                }

                if ($request->subDt) {
                    $subdt = $request->subDt;

                    foreach ($subdt as $key => $value) {
                        $subDt[] = [
                            'title' => $key,
                            'value' => $value,
                            'saran' => $request->saranSubDt,
                        ];
                    }
                    $dt['sub'] = $subDt;
                }

                $item['persyaratan_teknis'] = $dt;
            }

            $step->kode = $level;
            $step->head = $head->id;
            $step->item = json_encode($item);
            $step->save();

            $head->saran = $request->content;
        }

        if ($level == 'VL3') {

            $step = ($vl3) ? $vl3 : new Step;

            if ($request->itemDa) {
                $da['item'] = $request->itemDa;
                $da['saranItem'] = $request->saranItemDa;
            }

            if ($request->subDa) {
                $subda = $request->subDa;

                foreach ($subda as $key => $value) {
                    $subDa[] = [
                        'title' => $key,
                        'value' => $value,
                        'saran' => $request->saranSubDa,
                    ];
                }
                $da['sub'] = $subDa;
            }
            $item['dokumen_administrasi'] = $da;

            $step->head = $head->id;
            $step->item = json_encode($item);
            $step->kode = $level;
            $step->save();
        }

        $vl2 = Step::where(DB::raw('md5(head)'), $id)->where('kode', 'VL2')->first();
        $vl3 = Step::where(DB::raw('md5(head)'), $id)->where('kode', 'VL3')->first();
        // $head->status = ($vl2 && $vl3) ? 1 : 3;
        $head->status = 1;
        $head->save();

        toastr()->success('Input Complete', ['timeOut' => 5000]);
        return redirect()->route('verification.index');

    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Verification $verification)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Verification $verification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Verification $verification)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Verification $verification)
    {
        //
    }
}
