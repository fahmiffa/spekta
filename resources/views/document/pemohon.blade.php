@php
    $header = (array) json_decode($head->header);
    
@endphp
<div class="row my-3">
    <div class="col-4">
        <h6>No Registrasi</h6>
        {{ $head->reg }}
    </div>
    <div class="col-4">
        <h6>Nama Pemohon</h6>
        {{ $header ? $header[2] : null }}
    </div>
    <div class="col-4 mb-3">
        <h6>Alamat Pemohon</h6>
        {{ $header ? $header[4] : null }}
    </div>
    <div class="col-4">
        <h6>{{ $head->type == 'umum' ? 'Fungsi' : 'Koordinat' }}</h6>
        {{ $head->type == 'umum' ? str_replace('_',' ',ucfirst($header[6])) : $header[8] }}
    </div>
    <div class="col-4">
        <h6>Nama Bangunan</h6>
        {{ $header ? $header[5] : null }}
    </div>
    <div class="col-4">
        <h6>Lokasi Bangunan</h6>
        {{ $header ? $header[7] : null }}<br>
        {{ $head->region ? 'Desa/Kel. ' . $head->region->name : null }}
        {{ $head->region ? ' Kec. ' . $head->region->kecamatan->name : null }}
    </div>
    <div class="col-4">
        <h6>Pengajuan</h6>
        {{ $header && isset($header[1]) ? strtoupper($header[1]) : null }}
    </div>
    @if (auth()->user()->ijin('bak') && Request::segment(2) == 'input-bak' || Request::segment(2) == 'bak-input' ) 
        <div class="col-4">
            <button type="button" class="btn btn-sm btn-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#myModal">Update</button>
        </div>

        <div class="modal fade" id="myModal" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Update Data {{ $header ? $header[2] : null }} </h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        <form action="{{ route('pemohon.update.news',['id'=>md5($head->id)]) }}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-md-10">
                                    <div class="form-group mb-3">
                                        <label>No. Registrasi</label>
                                        <input type="text" name="noreg" value="{{ isset($head) ? $head->reg : old('noreg') }}"
                                            class="form-control">
                                        @error('noreg')
                                            <div class='small text-danger text-left'>{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <!-- pemohon -->
                                <div class="col-md-8">
                                    <div class="form-group mb-3">
                                        <label>Nama Pemohon</label>
                                        <input type="text" name="namaPemohon" value="{{ $header[2] }}"
                                            class="form-control">
                                        @error('namaPemohon')
                                            <div class='small text-danger text-left'>{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group mb-3">
                                        <label>Email</label>
                                        <input type="email" name="email" value="{{ $head->email }}"
                                            class="form-control">
                                        @error('email')
                                            <div class='small text-danger text-left'>{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label>No. Telp. / HP :</label>
                                        <input type="text" name="hp" value="{{ $header[3] }}"
                                            class="form-control">
                                        @error('hp')
                                            <div class='small text-danger text-left'>{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label>Alamat Pemohon</label>
                                        <textarea class="form-control" name="alamatPemohon" rows="2">{{ $header[4] }}</textarea>
                                        @error('alamatPemohon')
                                            <div class='small text-danger text-left'>{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Bangunan -->
                                <div class="col-md-12 row">
                                    <div class="col-md-6 form-group mb-3">
                                        <label>Nama Bangunan</label>
                                        <input type="text" name="namaBangunan"
                                            value="{{ $header[5] }}" class="form-control">
                                        @error('namaBangunan')
                                            <div class='small text-danger text-left'>{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 form-group mb-3">
                                        <label>No. Dokumen Tanah</label>
                                        <input type="text" name="land"
                                            value="{{ $header[9] }}" class="form-control">
                                        @error('land')
                                            <div class='small text-danger text-left'>{{ $message }}</div>
                                        @enderror
                                    </div> 
                                </div>
                                <div class="col-md-12 row">
                                    <div class="form-group mb-3 col-md-6">
                                        <label id="con">Fungsi</label>       
                                        <select class="form-control" name="fungsi">
                                            <option value="">Pilih Fungsi</option>
                                            <option value="hunian" @selected($header[6] == 'hunian')>Hunian
                                            </option>
                                            <option value="keagamaan" @selected($header[6] == 'keagamaan')>Keagamaan
                                            </option>
                                            <option value="usaha" @selected($header[6] == 'usaha')>Usaha
                                            </option>
                                            <option value="sosial_budaya" @selected($header[6] == 'sosial_budaya')>Sosial Budaya
                                            </option>
                                            <option value="khusus" @selected($header[6] == 'khusus')>Khusus
                                            </option>
                                            <option value="campuran" @selected($header[6] == 'campuran')>Campuran
                                            </option>
                                        </select>
                                        @error('fungsi')
                                            <div class='small text-danger text-left'>{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-3 col-md-6">
                                        <label id="con">Koordinat</label>
                                        <input type="text" name="koordinat" value="{{ $header[8] }}"
                                            class="form-control">
                                        @error('koordinat')
                                            <div class='small text-danger text-left'>{{ $message }}</div>
                                        @enderror      
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label>Alamat Bangunan</label>
                                        <textarea class="form-control" name="alamatBangunan" rows="2">{{ $header[7] }}</textarea>
                                        @error('alamatBangunan')
                                            <div class='small text-danger text-left'>{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label>Kecamatan</label>
                                        <select class="select-field form-select" name="dis" id="dis">
                                            <option value="">Pilih Kecamatan</option>
                                            @foreach ($dis as $item)
                                                <option value="{{ $item->id }}" @selected(isset($head) && $head->region->kecamatan->id == $item->id)>{{ ucfirst($item->name) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('dis')
                                            <div class='small text-danger text-left'>{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label>Desa</label>
                                        <select class="select-field form-select" name="des" id="des">
                                            <option value="">Pilih Desa</option>
                                            @isset($head)
                                                @foreach ($head->region->kecamatan->desa as $item)
                                                    <option value="{{ $item->id }}" @selected(isset($head) && $head->village == $item->id)>{{ ucfirst($item->name) }}
                                                    </option>
                                                @endforeach
                                                @endif
                                            </select>
                                            @error('des')
                                                <div class='small text-danger text-left'>{{ $message }}</div>
                                            @enderror
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <div class="col-md-12">
                                        <button class="btn btn-primary rounded-pill">Save</button>                         
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                    </div>

                 </div>
                </div>
            </div>
        </div>
    @endif
</div>
