@php
$temp = $head->temp->where('id',$head->parent)->first();
$draft = $head;
if($temp)
{
    $head = $temp;
    $remain = true;      
}
else
{
    $remain = false;
}
@endphp
@if ($level == 'VL2')
    @php 
        if($temp)
        {            
            $draftVL2 = $draft->steps->where('kode', 'VL2')->first();   
            if($draftVL2)
            {
                $old = $draftVL2;
                $head = $draft;
            }
            else
            {
                $old = $temp->steps->where('kode', 'VL2')->first();   
            }
        }
        else
        {
            $old = $head->steps->where('kode', 'VL2')->first();                    
        }
    @endphp
    @if ($head->type == 'umum')
        @php
            if ($remain) {
                $other = json_decode($old->other);
                $da = json_decode($old->item);
                $subt = (array) $da->dokumen_teknis->sub;
                foreach ($subt as $key => $value) {
                    $subdt[$value->title] = ['saran' => (array) $value->saran, 'value' => (array) $value->value];
                }
                $itemdl = (array) $da->dokumen_pendukung_lainnya->item;
                $saranItemdl = (array) $da->dokumen_pendukung_lainnya->saranItem;
                $sub = (array) $da->dokumen_pendukung_lainnya->sub;
                foreach ($sub as $key => $value) {
                    $subdl[$value->title] = ['saran' => (array) $value->saran, 'value' => (array) $value->value];
                }
            }
        @endphp
        @foreach ($doc->title as $row)
            @if ($row->name == doc(3, $head->type))
                <div class="col-md-12">
                    <h6>{{ $row->name }}</h6>
                    @foreach ($row->items as $item)
                        @if (count($item->sub) > 0)
                            <p> {{ $loop->iteration }}. {{ $item->name }}</p>
                            @foreach ($item->sub as $sub)
                                <div class="row mb-3 g-0">
                                    <div class="col-md-4 d-flex">
                                        <div class="ms-3">
                                            {{ abjad($loop->index) }}.
                                        </div>
                                        <p class="ms-1">{{ $sub->name }}</p>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group d-flex justify-content-center">
                                            <div class="form-check d-inline-block">
                                                <input class="form-check-input" type="radio"
                                                    name="subdl[{{ $item->id }}][{{ $sub->id }}]"
                                                    value="1"
                                                    {{ $remain && $subdl[$item->id]['value'][$sub->id] == 1 ? 'checked' : null }}>
                                                <label class="form-check-label">Ada</label>
                                            </div>
                                            <div class="form-check d-inline-block mx-3">
                                                <input class="form-check-input" type="radio"
                                                    name="subdl[{{ $item->id }}][{{ $sub->id }}]"
                                                    value="0"
                                                    {{ $remain && $subdl[$item->id]['value'][$sub->id] == 0 ? 'checked' : null }}>
                                                <label class="form-check-label">Tidak Ada</label>
                                            </div>
                                            <div class="form-check d-inline-block">
                                                @if ($remain)
                                                    <input class="form-check-input" type="radio"
                                                        name="subdl[{{ $item->id }}][{{ $sub->id }}]"
                                                        value="2"
                                                        {{ $remain && $subdl[$item->id]['value'][$sub->id] == 2 ? 'checked' : null }}>
                                                @else
                                                    <input class="form-check-input" type="radio"
                                                        name="subdl[{{ $item->id }}][{{ $sub->id }}]"
                                                        value="2" checked>
                                                @endif
                                                <label class="form-check-label">Tidak Perlu</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <textarea class="form-control" name="saranSubdl[{{ $sub->id }}]" rows="2">{{ old('saranSubdl[' . $sub->id . ']') }}</textarea>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="row mb-3">
                                <div class="col-md-4 d-flex">
                                    {{ $loop->iteration }}. <p class="ms-2">{{ $item->name }}</p>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group d-flex justify-content-center">
                                        <div class="form-check d-inline-block">
                                            <input class="form-check-input" type="radio"
                                                name="itemdl[{{ $item->id }}]" value="1"
                                                {{ $remain && $itemdl[$item->id] == 1 ? 'checked' : null }}>
                                            <label class="form-check-label">Ada</label>
                                        </div>
                                        <div class="form-check d-inline-block mx-3">
                                            <input class="form-check-input" type="radio"
                                                name="itemdl[{{ $item->id }}]" value="0"
                                                {{ $remain && $itemdl[$item->id] == 0 ? 'checked' : null }}>
                                            <label class="form-check-label">Tidak Ada</label>
                                        </div>
                                        <div class="form-check d-inline-block">
                                            @if ($remain)
                                                <input class="form-check-input" type="radio"
                                                    name="itemdl[{{ $item->id }}]" value="2"
                                                    {{ $remain && $itemdl[$item->id] == 2 ? 'checked' : null }}>
                                            @else
                                                <input class="form-check-input" type="radio"
                                                    name="itemdl[{{ $item->id }}]" value="2" checked>
                                            @endif
                                            <label class="form-check-label">Tidak Perlu</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <textarea class="form-control" name="saranItemdl[{{ $item->id }}]" rows="2">{{ $remain ? $saranItemdl[$item->id] : null }}</textarea>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif
            @if ($row->name == doc(4, $head->type))
                <div class="col-md-12">
                    <h6>{{ $row->name }} </h6>
                    @foreach ($row->items as $item)
                        @if (count($item->sub) > 0)
                            <p> {{ $no++ }}. {{ $item->name }}</p>
                            @foreach ($item->sub as $sub)
                                <div class="row mb-3 g-0">
                                    <div class="col-md-4 d-flex">
                                        <div class="ms-3">
                                            {{ abjad($loop->index) }}.
                                        </div>
                                        <p class="ms-1">{{ $sub->name }}</p>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group d-flex justify-content-center">
                                            <div class="form-check d-inline-block">
                                                <input class="form-check-input" type="radio"
                                                    name="subdt[{{ $item->id }}][{{ $sub->id }}]"
                                                    value="1"
                                                    {{ $remain && $subdt[$item->id]['value'][$sub->id] == 1 ? 'checked' : null }}>
                                                <label class="form-check-label">Ada</label>
                                            </div>
                                            <div class="form-check d-inline-block mx-3">
                                                @if ($remain)
                                                    <input class="form-check-input" type="radio"
                                                        name="subdt[{{ $item->id }}][{{ $sub->id }}]"
                                                        value="0"
                                                        {{ $remain && $subdt[$item->id]['value'][$sub->id] == 0 ? 'checked' : null }}>
                                                @else
                                                    <input class="form-check-input" type="radio"
                                                        name="subdt[{{ $item->id }}][{{ $sub->id }}]"
                                                        value="0" checked>
                                                @endif
                                                <label class="form-check-label">Tidak Ada</label>
                                            </div>
                                            <div class="form-check d-inline-block">
                                                <input class="form-check-input" type="radio"
                                                    name="subdt[{{ $item->id }}][{{ $sub->id }}]"
                                                    value="2"
                                                    {{ $remain && $subdt[$item->id]['value'][$sub->id] == 2 ? 'checked' : null }}>
                                                <label class="form-check-label">Tidak Perlu</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <textarea class="form-control" name="saranSubdt[{{ $sub->id }}]" rows="2">{{ $remain ? $subdt[$item->id]['saran'][$sub->id] : null }}</textarea>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="row mb-3">
                                <div class="col-md-4 d-flex">
                                    {{ $no++ }}. <p class="ms-2">{{ $item->name }}</p>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group d-flex justify-content-center">
                                        <div class="form-check d-inline-block">
                                            <input class="form-check-input" type="radio"
                                                name="itemdt[{{ $item->id }}]" value="1"
                                                {{ old('itemdt[' . $item->id . ']') == '1' ? 'checked' : null }}>
                                            <label class="form-check-label">Ada</label>
                                        </div>
                                        <div class="form-check d-inline-block mx-3">
                                            @if ($remain)
                                                <input class="form-check-input" type="radio"
                                                    name="itemdt[{{ $item->id }}]" value="0"
                                                    {{ old('itemdt[' . $item->id . ']') == '0' ? 'checked' : null }}>
                                            @else
                                                <input class="form-check-input" type="radio"
                                                    name="itemdt[{{ $item->id }}]" value="0" checked>
                                            @endif
                                            <label class="form-check-label">Tidak Ada</label>
                                        </div>
                                        <div class="form-check d-inline-block">
                                            <input class="form-check-input" type="radio"
                                                name="itemdt[{{ $item->id }}]" value="2"
                                                {{ old('itemdt[' . $item->id . ']') == '2' ? 'checked' : null }}>
                                            <label class="form-check-label">Tidak Perlu</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <textarea class="form-control" name="saranItemdt[{{ $item->id }}]" rows="2">{{ old('saranItemdt[' . $item->id . ']') }}</textarea>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif
        @endforeach
        <div class="form-group">
            <h6>Lain-lain</h6>
            <div class="row mb-3" id="master">
                @if ($old && $old->other)
                    @for ($i = 0; $i < count($other); $i++)
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="nameOther[{{ $i }}]"
                                value="{{ $other[$i]->name }}" placeholder="Lain-lain">
                        </div>
                        <div class="col-md-5">
                            <div class="d-flex justify-content-center">
                                <div class="form-check d-inline-block">
                                    <input class="form-check-input" type="radio" name="item[{{ $i }}]"
                                        value="1" {{ $other[$i]->value == '1' ? 'checked' : null }}>
                                    <label class="form-check-label">Ada</label>
                                </div>
                                <div class="form-check d-inline-block mx-3">
                                    <input class="form-check-input" type="radio" name="item[{{ $i }}]"
                                        value="0" {{ $other[$i]->value == '0' ? 'checked' : null }}>
                                    <label class="form-check-label">Tidak Ada</label>
                                </div>
                                <div class="form-check d-inline-block">
                                    <input class="form-check-input" type="radio" name="item[{{ $i }}]"
                                        value="2" {{ $other[$i]->value == '2' ? 'checked' : null }}>
                                    <label class="form-check-label">Tidak Perlu</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <textarea class="form-control" name="saranOther[{{ $i }}]" rows="2">{{ $other[$i]->saran }}</textarea>
                        </div>
                    @endfor
                @else
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="nameOther[0]" placeholder="item name">
                    </div>
                    <div class="col-md-5">
                        <div class="d-flex justify-content-center">
                            <div class="form-check d-inline-block">
                                <input class="form-check-input" type="radio" name="item[0]" value="1">
                                <label class="form-check-label">Ada</label>
                            </div>
                            <div class="form-check d-inline-block mx-3">
                                <input class="form-check-input" type="radio" name="item[0]" value="0"
                                    checked>
                                <label class="form-check-label">Tidak Ada</label>
                            </div>
                            <div class="form-check d-inline-block">
                                <input class="form-check-input" type="radio" name="item[0]" value="2">
                                <label class="form-check-label">Tidak Perlu</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <textarea class="form-control" name="saranOther[0]" rows="2"></textarea>
                    </div>
                @endif
            </div>
            <div id="input"></div>
            <button class="btn btn-success btn-sm rounded-pill" type="button" id="add-item">Tambah</button>
        </div>
        <div class="form-group">
            <textarea class="form-control summernote" name="content" rows="2">
            {!! $head ? $head->saran : null !!}
            </textarea>                                             
        </div>
    @else
        @php
            if ($remain) {
                $da = json_decode($old->item);
                $itemPt = (array) $da->persyaratan_teknis->item;
                $saranItemPt = (array) $da->persyaratan_teknis->saranItem;
                $sub = (array) $da->persyaratan_teknis->sub;
                foreach ($sub as $key => $value) {
                    $subPt[$value->title] = ['saran' => (array) $value->saran, 'value' => (array) $value->value];
                }
            }
        @endphp
        @foreach ($doc->title as $row)
            @if ($row->name == doc(4, $head->type))
                <div class="col-md-12">
                    <h6>{{ $row->name }}</h6>
                    @foreach ($row->items as $item)
                        @if (count($item->sub) > 0)
                            <p> {{ $no++ }}. {{ $item->name }}</p>
                            @foreach ($item->sub as $sub)
                                <div class="row mb-3 g-0">
                                    <div class="col-md-4 d-flex">
                                        <div class="ms-3">
                                            {{ abjad($loop->index) }}.
                                        </div>
                                        <p class="ms-1">{{ $sub->name }}</p>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group d-flex justify-content-center">
                                            <div class="form-check d-inline-block">
                                                <input class="form-check-input" type="radio"
                                                    name="subDt[{{ $item->id }}][{{ $sub->id }}]"
                                                    value="1"
                                                    {{ $remain && $subPt[$item->id]['value'][$sub->id] == 1 ? 'checked' : null }}>
                                                <label class="form-check-label">Ada</label>
                                            </div>
                                            <div class="form-check d-inline-block mx-3">
                                                <input class="form-check-input" type="radio"
                                                    name="subDt[{{ $item->id }}][{{ $sub->id }}]"
                                                    value="0"
                                                    {{ $remain && $subPt[$item->id]['value'][$sub->id] == 0 ? 'checked' : null }}
                                                    {{ $remain == false ? 'checked' : null }}>
                                                <label class="form-check-label">Tidak Ada</label>
                                            </div>
                                            <div class="form-check d-inline-block">
                                                <input class="form-check-input" type="radio"
                                                    name="subDt[{{ $item->id }}][{{ $sub->id }}]"
                                                    value="2"
                                                    {{ $remain && $subPt[$item->id]['value'][$sub->id] == 2 ? 'checked' : null }}>
                                                <label class="form-check-label">Tidak Perlu</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <textarea class="form-control" name="saranSubDt[{{ $sub->id }}]" rows="2">{{ old('saranSubDt[' . $sub->id . ']') }}</textarea>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="row mb-3">
                                <div class="col-md-4 d-flex">
                                    {{ $no++ }}. <p class="ms-2">{{ $item->name }}</p>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group d-flex justify-content-center">
                                        <div class="form-check d-inline-block">
                                            <input class="form-check-input" type="radio"
                                                name="itemDt[{{ $item->id }}]" value="1"
                                                {{ $remain && $itemPt[$item->id] == 1 ? 'checked' : null }}>
                                            <label class="form-check-label">Ada</label>
                                        </div>
                                        <div class="form-check d-inline-block mx-3">
                                            <input class="form-check-input" type="radio"
                                                name="itemDt[{{ $item->id }}]" value="0"
                                                {{ $remain && $itemPt[$item->id] == 0 ? 'checked' : null }}
                                                {{ $remain == false ? 'checked' : null }}>
                                            <label class="form-check-label">Tidak Ada</label>
                                        </div>
                                        <div class="form-check d-inline-block">
                                            <input class="form-check-input" type="radio"
                                                name="itemDt[{{ $item->id }}]" value="2"
                                                {{ $remain && $itemPt[$item->id] == 2 ? 'checked' : null }}>
                                            <label class="form-check-label">Tidak Perlu</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <textarea class="form-control" name="saranItemDt[{{ $item->id }}]" rows="2">{{ old('saranItem[' . $item->id . ']') }}</textarea>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif
        @endforeach
        <div class="form-group">
            <textarea class="form-control summernote" name="content" rows="2">
                {!! $head ? $head->saran : null !!}
            </textarea>
        </div>
    @endif
@else
    @php                  
        if($temp)
        {            
            $draft = $draft->steps->where('kode', 'VL3')->first();   
            if($draft)
            {
                $old = $draft;
            }
            else
            {
                $old = $temp->steps->where('kode', 'VL3')->first();   
            }
        }
        else
        {
            $old = $head->steps->where('kode', 'VL3')->first();                    
        }
        $remain = $old ? true : false;
        if ($remain) {
            $da = json_decode($old->item);
            $itemDa = (array) $da->dokumen_administrasi->item;
            $saranItemDa = (array) $da->dokumen_administrasi->saranItem;
            $sub = (array) $da->dokumen_administrasi->sub;
            foreach ($sub as $key => $value) {
                $subDa[$value->title] = ['saran' => (array) $value->saran, 'value' => (array) $value->value];
            }
        }
    @endphp
    @foreach ($doc->title as $row)
        @if ($row->name == doc(5, $head->type))
            <div class="col-md-12">
                <h6>{{ $row->name }}</h6>
                @foreach ($row->items as $item)
                    @if (count($item->sub) > 0)
                        <p> {{ $no++ }}. {{ $item->name }}</p>
                        @foreach ($item->sub as $sub)
                            <div class="row mb-3 g-0">
                                <div class="col-md-4 d-flex">
                                    <div class="ms-3">
                                        {{ abjad($loop->index) }}.
                                    </div>
                                    <p class="ms-1">{{ $sub->name }}</p>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group d-flex justify-content-center">
                                        <div class="form-check d-inline-block">
                                            <input class="form-check-input" type="radio"
                                                name="subDa[{{ $item->id }}][{{ $sub->id }}]"
                                                value="1"
                                                {{ $remain && $subDa[$item->id]['value'][$sub->id] == 1 ? 'checked' : null }}>
                                            <label class="form-check-label">Ada</label>
                                        </div>
                                        <div class="form-check d-inline-block mx-3">
                                            @if ($remain)
                                                <input class="form-check-input" type="radio"
                                                    name="subDa[{{ $item->id }}][{{ $sub->id }}]"
                                                    value="0"
                                                    {{ $remain && $subDa[$item->id]['value'][$sub->id] == 0 ? 'checked' : null }}>
                                            @else
                                                <input class="form-check-input" type="radio"
                                                    name="subDa[{{ $item->id }}][{{ $sub->id }}]"
                                                    value="0" checked>
                                            @endif
                                            <label class="form-check-label">Tidak Ada</label>
                                        </div>
                                        <div class="form-check d-inline-block">
                                            <input class="form-check-input" type="radio"
                                                name="subDa[{{ $item->id }}][{{ $sub->id }}]"
                                                value="2"
                                                {{ $remain && $subDa[$item->id]['value'][$sub->id] == 2 ? 'checked' : null }}>
                                            <label class="form-check-label">Tidak Perlu</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <textarea class="form-control" name="saranSubDa[{{ $sub->id }}]" rows="2">{{ $remain ? $subDa[$item->id]['saran'][$sub->id] : null }}</textarea>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="row mb-3">
                            <div class="col-md-4 d-flex">
                                {{ $no++ }}. <p class="ms-2">{{ $item->name }}</p>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group d-flex justify-content-center">
                                    <div class="form-check d-inline-block">
                                        <input class="form-check-input" type="radio"
                                            name="itemDa[{{ $item->id }}]" value="1"
                                            {{ $remain && $itemDa[$item->id] == 1 ? 'checked' : null }}>
                                        <label class="form-check-label">Ada</label>
                                    </div>
                                    <div class="form-check d-inline-block mx-3">
                                        @if ($remain)
                                            <input class="form-check-input" type="radio"
                                                name="itemDa[{{ $item->id }}]" value="0"
                                                {{ $remain && $itemDa[$item->id] == 0 ? 'checked' : null }}>
                                        @else
                                            <input class="form-check-input" type="radio"
                                                name="itemDa[{{ $item->id }}]" value="0" checked>
                                        @endif
                                        <label class="form-check-label">Tidak Ada</label>
                                    </div>
                                    <div class="form-check d-inline-block">
                                        <input class="form-check-input" type="radio"
                                            name="itemDa[{{ $item->id }}]" value="2"
                                            {{ $remain && $itemDa[$item->id] == 2 ? 'checked' : null }}>
                                        <label class="form-check-label">Tidak Perlu</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <textarea class="form-control" name="saranItemDa[{{ $item->id }}]" rows="2">{{ $remain ? $saranItemDa[$item->id] : null }}</textarea>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif
    @endforeach
@endif
