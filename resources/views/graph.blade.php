<div class="row">
    <div class="col-12">
        <div class="card card-body">
            <div class="row">
                <div class="col col-md-4">
                    <div class="d-flex jusitify-content-between">
                        <div class="p-1">
                            <div class="stats-icon mb-2" style="background-color : #ff5722;">
                                <i class="iconly-boldPaper"></i>
                            </div>
                        </div>
                        <div class="p-1">
                            <h6 class="text-muted font-semibold">Jumlah Permohonan</h6>
                            <h6 class="font-extrabold mb-0">{{$head->count()}}</h6>
                        </div>
                    </div>
                </div>
                <div class="col col-md-4">
                    <div class="d-flex jusitify-content-between">
                        <div class="p-1">
                            <div class="stats-icon mb-2" style="background-color: #ffc107">
                                <i class="iconly-boldPaper"></i>
                            </div>
                        </div>
                        <div class="p-1">
                            <h6 class="text-muted font-semibold">Tahap Verifikasi</h6>
                            <h6 class="font-extrabold mb-0">{{$verif}}</h6>
                        </div>
                    </div>
                </div>
                <div class="col col-md-4">
                    <div class="d-flex jusitify-content-between">
                        <div class="p-1">
                            <div class="stats-icon mb-2 blue">
                                <i class="iconly-boldPaper"></i>
                            </div>
                        </div>
                        <div class="p-1">
                            <h6 class="text-muted font-semibold">Penjadwalan Konsultasi</h6>
                            <h6 class="font-extrabold mb-0">{{$jadwal}}</h6>
                        </div>
                    </div>
                </div>
                <div class="col col-md-4">
                    <div class="d-flex jusitify-content-between">
                        <div class="p-1">
                            <div class="stats-icon purple mb-2">
                                <i class="iconly-boldPaper"></i>
                            </div>
                        </div>
                        <div class="p-1">
                            <h6 class="text-muted font-semibold">Tahap Konsultasi</h6>
                            <h6 class="font-extrabold mb-0">{{$kons}}</h6>
                        </div>
                    </div>
                </div>                                   
                <div class="col col-md-4">
                    <div class="d-flex jusitify-content-between">
                        <div class="p-1">
                            <div class="stats-icon green mb-2">
                                <i class="iconly-boldPaper"></i>
                            </div>
                        </div>
                        <div class="p-1">
                            <h6 class="text-muted font-semibold">Selesai</h6>
                            <h6 class="font-extrabold mb-0">{{$head->where('do',1)->count()}}</h6>
                        </div>
                    </div>
                </div>
            </div>             
        </div>
    </div>        
</div>
<div class="row">
    <div class="col-12">
        <div class="card card-body">
            <div class="row">
                <div class="col col-md-4">
                    <div class="d-flex jusitify-content-between">
                        <div class="p-1">
                            <div class="stats-icon green mb-2">
                                <i class="iconly-boldPaper"></i>
                            </div>
                        </div>
                        <div class="p-1">
                            <h6 class="text-muted font-semibold"> Permohonan Disetujui</h6>
                            <h6 class="font-extrabold mb-0">{{$res[0]}}</h6>
                        </div>
                    </div>
                </div>         
                <div class="col col-md-4">
                    <div class="d-flex jusitify-content-between">
                        <div class="p-1">
                            <div class="stats-icon mb-2" style="background-color: #0dcaf0">
                                <i class="iconly-boldPaper"></i>
                            </div>
                        </div>
                        <div class="p-1">
                            <h6 class="text-muted font-semibold">Permohonan Diulang</h6>
                            <h6 class="font-extrabold mb-0">{{$res[1]}}</h6>
                        </div>
                    </div>
                </div>
                <div class="col col-md-4">
                    <div class="d-flex jusitify-content-between">
                        <div class="p-1">
                        <div class="stats-icon mb-2" style="background-color: red">
                                <i class="iconly-boldPaper"></i>
                            </div>
                        </div>
                        <div class="p-1">
                            <h6 class="text-muted font-semibold">Permohonan Ditolak</h6>
                            <h6 class="font-extrabold mb-0">{{$res[2]}}</h6>
                        </div>
                    </div>
                </div>                                                
            </div>             
        </div>
    </div>        
</div>        
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <form>
                    <div class="row">
                        <div class="col-4 form-group mb-3">
                            <label>Tahun</label>
                            <select class="form-control" name="tahun">
                                @for($i=2024;$i <= date('Y'); $i++)
                                     <option value="{{$i}}"  @selected($i == $year)>{{$i}}</option>
                                @endfor
                            </select>
                            @error('tahun')
                                <div class='small text-danger text-left'>{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-4 form-group mb-3">
                            <label id="con">Fungsi</label>       
                            <select class="form-control" name="fungsi">
                                <option value="">Semua Fungsi</option>
                                <option value="hunian" @selected($fungsi == 'hunian')>Hunian
                                </option>
                                <option value="keagamaan" @selected($fungsi == 'keagamaan')>Keagamaan
                                </option>
                                <option value="usaha" @selected($fungsi == 'usaha')>Usaha
                                </option>
                                <option value="sosial_budaya" @selected($fungsi == 'sosial_budaya')>Sosial Budaya
                                </option>
                                <option value="khusus" @selected($fungsi == 'khusus')>Khusus
                                </option>
                                <option value="campuran" @selected($fungsi == 'campuran')>Campuran
                                </option>
                            </select>
                            @error('fungsi')
                                <div class='small text-danger text-left'>{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-4 form-group mb-3">
                            <label>Pengajuan</label>
                            <select class="form-control" name="pengajuan" placeholder="Pengajuan">
                                <option value="">Semua Pengajuan</option>
                                <option value="pbg" @selected($pengajuan == 'pbg')>PBG</option>
                                <option value="slf" @selected($pengajuan == 'slf')>SLF</option>
                                <option value="lainnya" @selected($pengajuan == 'lainnya')>Lainnya</option>
                            </select>
                            @error('pengajuan')
                                <div class='small text-danger text-left'>{{ $message }}</div>
                            @enderror
                        </div>
                        <button class="btn btn-primary rounded-pill w-25">FIlter</button>
                    </div>
                </form>
            </div>
            <div class="card-body">
                <div id="chart-profile-visit" style="min-height: 315px;">
                </div>
            </div>
        </div>
    </div>
</div>