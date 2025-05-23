@extends('layout.base')
@push('css')
    <link rel="stylesheet" href="{{ asset('assets/extensions/choices.js/public/assets/styles/choices.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.rtl.min.css" />
@endpush
@section('main')
    <div class="page-heading">

        <section class="section">
            <div class="card">

                <div class="card-header">
                    <div class="divider">
                        <div class="divider-text">{{$data}}</div>
                    </div> 
                </div>

                <div class="card-body">

                    <form action="{{ route('next.meet', ['id' => md5($meet->id)]) }}" method="post">
                        @csrf
                        <div class="px-5">     
                            @if ($meet->status == 2)
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <p>Sebagaimana terlampir pada Lembar Berita Acara Konsultasi
                                                No.
                                                {{ str_replace('SPm', 'BAK', str_replace('600.1.15', '600.1.15/PBLT', $meet->doc->nomor)) }}
                                                yang
                                                merupakan bagian tidak terpisahkan dari Berita Acara Rapat Pleno ini,
                                                TPT/TPA memberikan masukkan:
                                            </p>
                                            <textarea class="form-control" name="item[]" rows="2"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label>Dan dengan pertimbangan bahwa :</label>
                                            <textarea class="form-control" name="item[]" rows="2"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-check mb-3">
                                            <input type="checkbox"
                                                class="form-check-input form-check-primary form-check-glow" checked=""
                                                name="val[0]">
                                            <label class="form-check-label">Merekomendasikan penerbitan Surat Pernyataan
                                                Pemenuhan Standar Teknis PBG dan/atau SLF dengan :
                                            </label>
                                        </div>
                                        <div class="form-group row mb-3">
                                            <div class="col-md-4 mb-3">
                                                <h6>Uraian</h6>
                                                Luas Total Bangunan termasuk <br>Luas Total Basement (LLt)								                                             
                                            </div>
                                            <div class="col-md-3">
                                                <h6>Pengajuan</h6>                                                							
                                                <input type="text" name="luas[]" class="form-control">
                                            </div>
                                            <div class="col-md-2">
                                                <h6>Disetujui</h6>                                                							
                                                <input type="text" name="luas[]" class="form-control">
                                            </div>
                                            <div class="col-md-3">
                                                <h6>Keterangan</h6>                                                							
                                                <input type="text" name="luas[]" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row mb-3">
                                            <div class="col-md-4 mb-3">
                                                <h6>Uraian</h6>
                                                Prasarana (jika ada)																                                             
                                            </div>
                                            <div class="col-md-3">
                                                <h6>Pengajuan</h6>                                                							
                                                <input type="text" name="pra[]" class="form-control">
                                            </div>
                                            <div class="col-md-2">
                                                <h6>Disetujui</h6>                                                							
                                                <input type="text" name="pra[]" class="form-control">
                                            </div>
                                            <div class="col-md-3">
                                                <h6>Keterangan</h6>                                                							
                                                <input type="text" name="pra[]" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-check mb-3">
                                            <input type="checkbox"
                                                class="form-check-input form-check-primary form-check-glow" checked=""
                                                name="val[1]">
                                            <label class="form-check-label">Merekomendasikan pemohon untuk memperbaiki
                                                dokumen / informasi yang diunggah melalui SIMBG
                                            </label>
                                        </div>
                                        <div class="form-check mb-3">
                                            <input type="checkbox"
                                                class="form-check-input form-check-primary form-check-glow" checked=""
                                                name="val[2]">
                                            <label class="form-check-label">Merekomendasikan pemohon untuk melakukan
                                                pendaftaran ulang PBG dan/atau SLF melalui SIMBG
                                            </label>
                                        </div>
                                        <div class="form-check mb-3">
                                            <input type="checkbox"
                                                class="form-check-input form-check-primary form-check-glow" checked=""
                                                name="val[3]">
                                            <label class="form-check-label">Proses PBG dan/atau SLF tidak dapat dilanjutkan
                                                / ditolak
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            @endif      
                        </div>

                        <div class="col-md-12 px-5 mt-3">
                            <button class="btn btn-primary rounded-pill">Next</button>
                            {{-- <a class="btn btn-danger ms-1 rounded-pill" href="{{route('news.index')}}">Back</a> --}}
                        </div>
                </div>
                </form>
            </div>
    </div>

    </section>

    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/extensions/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/extensions/choices.js/public/assets/scripts/choices.js') }}"></script>
    <script src="{{ asset('assets/static/js/pages/form-element-select.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $('#type').on('change', function() {
            var tipe = $(this).val();

            if (tipe == 'umum') {
                $('#con').html('Fungsi');
            } else {
                $('#con').html('Koordinat');
            }
        });

        $('.select-field').select2({
            theme: 'bootstrap-5'
        });

        $('#dis').on('change', function(e) {
            e.preventDefault();
            $('#des').empty();
            $.ajax({
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('village') }}",
                data: {
                    id: $(this).val()
                },
                success: function(data) {
                    $.each(data, function(key, value) {
                        $('#des').append('<option value="' + key + '">' + value + '</option>');
                    });
                }
            });
        });
    </script>
@endpush
