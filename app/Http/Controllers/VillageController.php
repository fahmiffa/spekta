<?php

namespace App\Http\Controllers;

use App\Models\Village as Desa;
use App\Models\District;
use Illuminate\Http\Request;

class VillageController extends Controller
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
        $da = Desa::all();
        $data = "desa";
        return view('master.desa.index',compact('da','data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = "Tambah Desa";
        $kec = District::all();
        return view('master.desa.create',compact('data','kec'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rule = [                   
            'name' => 'required|unique:districts,name',
            'districts' => 'required'                                                
            ];
        $message = ['required'=>'Field ini harus diisi', 'unique'=>'Field ini sudah ada'];
        $request->validate($rule,$message);

        $desa = new Desa;
        $desa->name = $request->name;
        $desa->districts_id = $request->districts;
        $desa->save();

        toastr()->success('Tambah Data berhasil', ['timeOut' => 5000]);
        return redirect()->route('desa.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Desa $desa)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Desa $desa)
    {        
        $data = "Edit Desa";
        $kec = District::all();
        return view('master.desa.create',compact('data','kec','desa'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Desa $desa)
    {
        $rule = [                   
            'name' => 'required|unique:districts,name',
            'districts' => 'required'                                                
            ];
        $message = ['required'=>'Field ini harus diisi', 'unique'=>'Field ini sudah ada'];
        $request->validate($rule,$message);

        $desa->name = $request->name;
        $desa->districts_id = $request->districts;
        $desa->save();

        toastr()->success('Update Data berhasil', ['timeOut' => 5000]);
        return redirect()->route('desa.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Desa $desa)
    {
        $desa->delete();
        toastr()->success('Delete Data berhasil', ['timeOut' => 5000]);
        return back();
    }
}
