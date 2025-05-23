<?php

namespace App\Http\Controllers;

use App\Models\District as Kecamatan;
use Illuminate\Http\Request;

class DistrictController extends Controller
{

    public function __construct()
    {
        $this->middleware('IsPermission:master');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $da = Kecamatan::all();
        $data = "kecamatan";
        return view('master.kecamatan.index',compact('da','data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = "Tambah kecamatan";
        return view('master.kecamatan.create',compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rule = [                   
            'name' => 'required|unique:districts,name',                                                
            ];
        $message = ['required'=>'Field ini harus diisi', 'unique'=>'Field ini sudah ada'];
        $request->validate($rule,$message);

        $kecamatan = new Kecamatan;
        $kecamatan->name = $request->name;
        $kecamatan->save();

        toastr()->success('Tambah Data berhasil', ['timeOut' => 5000]);
        return redirect()->route('kecamatan.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Kecamatan $kecamatan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kecamatan $kecamatan)
    {
        $data = "Edit kecamatan";
        return view('master.kecamatan.create',compact('data','kecamatan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kecamatan $kecamatan)
    {
        $rule = [                   
            'name' => 'required|unique:districts,name,'.$kecamatan->id,                                                
            ];
        $message = ['required'=>'Field ini harus diisi', 'unique'=>'Field ini sudah ada'];
        $request->validate($rule,$message);

        $kecamatan->name = $request->name;
        $kecamatan->save();

        toastr()->success('Update Data berhasil', ['timeOut' => 5000]);
        return redirect()->route('kecamatan.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kecamatan $kecamatan)
    {
        $kecamatan->delete();
        toastr()->success('Delete Data berhasil', ['timeOut' => 5000]);
        return back();
    }
}
