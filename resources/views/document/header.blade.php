@isset($verifikasi)
    @php
        $header = json_decode($verifikasi->header);
    @endphp
    @endif
    <div class="row">
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label>No. Registrasi</label>
                <input type="text" name="noreg" value="{{ isset($verifikasi) ? $verifikasi->reg : old('noreg') }}"
                    class="form-control">
                @error('noreg')
                    <div class='small text-danger text-left'>{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label>Pengajuan</label>
                <select class="form-control" name="pengajuan" placeholder="Pengajuan">
                    <option value="">Pilih Pengajuan</option>
                    <option value="pbg" @selected(old('pengajuan') == 'pbg') @selected(isset($verifikasi) && $header[1] == 'pbg')>PBG</option>
                    <option value="slf" @selected(old('pengajuan') == 'slf') @selected(isset($verifikasi) && $header[1] == 'slf')>SLF</option>
                    <option value="lainnya" @selected(old('pengajuan') == 'lainnya') @selected(isset($verifikasi) && $header[1] == 'lainnya')>Lainnya</option>
                </select>
                @error('pengajuan')
                    <div class='small text-danger text-left'>{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group mb-3">
                <label>Nama Pemohon</label>
                <input type="text" name="namaPemohon" value="{{ isset($verifikasi) ? $header[2] : old('namaPemohon') }}"
                    class="form-control">
                @error('namaPemohon')
                    <div class='small text-danger text-left'>{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group mb-3">
                <label>Email</label>
                <input type="email" name="email" value="{{ isset($verifikasi) ? $verifikasi->email : old('email') }}"
                    class="form-control">
                @error('email')
                    <div class='small text-danger text-left'>{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group mb-3">
                <label>No. Telp. / HP :</label>
                <input type="text" name="hp" value="{{ isset($verifikasi) ? $header[3] : old('hp') }}"
                    class="form-control">
                @error('hp')
                    <div class='small text-danger text-left'>{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group mb-3">
                <label>Alamat Pemohon</label>
                <textarea class="form-control" name="alamatPemohon" rows="2">{{ isset($verifikasi) ? $header[4] : old('alamatPemohon') }}</textarea>
                @error('alamatPemohon')
                    <div class='small text-danger text-left'>{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="col-md-12 row">
            <div class="col-md-6 form-group mb-3">
                <label>Nama Bangunan</label>
                <input type="text" name="namaBangunan"
                    value="{{ isset($verifikasi) ? $header[5] : old('namaBangunan') }}" class="form-control">
                @error('namaBangunan')
                    <div class='small text-danger text-left'>{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6 form-group mb-3">
                <label>No. Dokumen Tanah</label>
                <input type="text" name="land"
                    value="{{ isset($verifikasi) && isset($header[9]) ? $header[9] : old('land') }}" class="form-control">
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
                    <option value="hunian" @selected(isset($verifikasi) && $header[6] == 'hunian')>Hunian
                    </option>
                    <option value="keagamaan" @selected(isset($verifikasi) && $header[6] == 'keagamaan')>Keagamaan
                    </option>
                    <option value="usaha" @selected(isset($verifikasi) && $header[6] == 'usaha')>Usaha
                    </option>
                    <option value="sosial_budaya" @selected(isset($verifikasi) && $header[6] == 'sosial_budaya')>Sosial Budaya
                    </option>
                    <option value="khusus" @selected(isset($verifikasi) && $header[6] == 'khusus')>Khusus
                    </option>
                    <option value="campuran" @selected(isset($verifikasi) && $header[6] == 'campuran')>Campuran
                    </option>
                </select>
                @error('fungsi')
                    <div class='small text-danger text-left'>{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group mb-3 col-md-6">
                <label id="con">Koordinat</label>
                <input type="text" name="koordinat" value="{{ isset($verifikasi) ? $header[8] : old('koordinat') }}"
                    class="form-control">
                @error('koordinat')
                    <div class='small text-danger text-left'>{{ $message }}</div>
                @enderror      
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group mb-3">
                <label>Alamat Bangunan</label>
                <textarea class="form-control" name="alamatBangunan" rows="2">{{ isset($verifikasi) ? $header[7] : old('alamatBangunan') }}</textarea>
                @error('alamatBangunan')
                    <div class='small text-danger text-left'>{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group row mb-3">
                <label>Kecamatan</label>
                <select class="select-field form-select" name="dis" id="dis">
                    <option value="">Pilih Kecamatan</option>
                    @foreach ($dis as $item)
                        <option value="{{ $item->id }}" @selected(isset($verifikasi) && $verifikasi->region->kecamatan->id == $item->id)>{{ ucfirst($item->name) }}
                        </option>
                    @endforeach
                </select>
                @error('dis')
                    <div class='small text-danger text-left'>{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group row mb-3">
                <label>Desa</label>
                <select class="select-field form-select" name="des" id="des">
                    <option value="">Pilih Desa</option>
                    @isset($verifikasi)
                        @foreach ($verifikasi->region->kecamatan->desa as $item)
                            <option value="{{ $item->id }}" @selected(isset($verifikasi) && $verifikasi->village == $item->id)>{{ ucfirst($item->name) }}
                            </option>
                        @endforeach
                        @endif
                    </select>
                    @error('des')
                        <div class='small text-danger text-left'>{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
