@php
    if($head->status == 5 && $head->parent == null)
    {
        $remain = false;      
        $old = false;
    }
    else
    {
        $head = $head->steps->count() > 0 ? $head : $head->old; 
        $old = $head->steps->where('kode', 'VL1')->first();
        $remain = true;
        $da = json_decode($old->item);
        $other = json_decode($old->other);        
        $itemDa = (array) $da->dokumen_administrasi->item;
        $saranItemDa = (array) $da->dokumen_administrasi->saranItem;
        $sub = (array) $da->dokumen_administrasi->sub;
           
        if($head->type == 'umum')
        {
            $itemDpl = (array) $da->dokumen_pendukung_lainnya->item;
            $saranItemDpl = (array) $da->dokumen_pendukung_lainnya->saranItem;
            $subdpl = (array) $da->dokumen_pendukung_lainnya->sub;
    
            foreach ($subdpl as $key => $value)
            {
                $subDpl[$value->title] = ['saran' => (array) $value->saran, 'value'=> (array) $value->value];
            }        
        }
        else {            
            $itemPt = (array) $da->persyaratan_teknis->item;
            $saranItemPt = (array) $da->persyaratan_teknis->saranItem;
        }


        $type = ($head->type == 'umum') ? 'dokumen_teknis' : 'persyaratan_teknis';
        $subt = (array) $da->$type->sub;

        foreach ($sub as $key => $value)
        {
            $subDa[$value->title] = ['saran' => (array) $value->saran, 'value'=> (array) $value->value];
        }        
    
        foreach ($subt as $key => $value)
        {
            $subDt[$value->title] = ['saran' => (array) $value->saran, 'value'=> (array) $value->value];
        } 
          

    }       
@endphp

@foreach ($doc->title as $row)
    @if ($row->name == doc(5, $head->type))
        <div class="col-md-12">
            <h6>{{ $row->name }}</h6>
            @php $no = 1; @endphp
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
                                            name="subDa[{{ $item->id }}][{{ $sub->id }}]" value="1"
                                            value="1" {{ $remain && $subDa[$item->id]['value'][$sub->id] == 1 ? 'checked' : null }}>
                                        <label class="form-check-label">Ada</label>
                                    </div>
                                    <div class="form-check d-inline-block mx-3">
                                        @if ($remain)
                                            <input class="form-check-input" type="radio"
                                                name="subDa[{{ $item->id }}][{{ $sub->id }}]" value="0"
                                                {{ $remain && $subDa[$item->id]['value'][$sub->id] == 0 ? 'checked' : null }}>
                                        @else
                                            <input class="form-check-input" type="radio"
                                                name="subDa[{{ $item->id }}][{{ $sub->id }}]" value="0" checked>
                                        @endif
                                        <label class="form-check-label">Tidak Ada</label>
                                    </div>
                                    <div class="form-check d-inline-block">
                                        <input class="form-check-input" type="radio"
                                            name="subDa[{{ $item->id }}][{{ $sub->id }}]" value="2"
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
                                    <input class="form-check-input" type="radio" name="itemDa[{{ $item->id }}]"
                                        value="1" {{ $remain && $itemDa[$item->id] == 1 ? 'checked' : null }}>
                                    <label class="form-check-label">Ada</label>
                                </div>
                                <div class="form-check d-inline-block mx-3">    
                                    @if($remain)
                                    <input class="form-check-input" type="radio" name="itemDa[{{ $item->id }}]"
                                    value="0" {{ $remain && $itemDa[$item->id] == 0 ? 'checked' : null }}> 
                                    @else
                                    <input class="form-check-input" type="radio" name="itemDa[{{ $item->id }}]"
                                            value="0" checked>                                            
                                    @endif                  
                                        <label class="form-check-label">Tidak Ada</label>
                                </div>
                                <div class="form-check d-inline-block">
                                    <input class="form-check-input" type="radio" name="itemDa[{{ $item->id }}]"
                                        value="2" {{ $remain && $itemDa[$item->id] == 2 ? 'checked' : null }}>
                                    <label class="form-check-label">Tidak Perlu</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <textarea class="form-control" name="saranItemDa[{{ $item->id }}]" rows="2">{{ $remain ? $saranItemDa[$item->id] : null}}</textarea>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    @endif
    @if ($row->name == doc(4, $head->type))
        <div class="col-md-12">
            <h6>{{ $row->name }}</h6>
            @php $no = 1; @endphp
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
                                            name="subDt[{{ $item->id }}][{{ $sub->id }}]" value="1"
                                            {{ $remain && $subDt[$item->id]['value'][$sub->id] == 1 ? 'checked' : null }}>
                                        <label class="form-check-label">Ada</label>
                                    </div>
                                    <div class="form-check d-inline-block mx-3">
                                        @if ($remain)
                                            <input class="form-check-input" type="radio"
                                                name="subDt[{{ $item->id }}][{{ $sub->id }}]" value="0"
                                                {{ $remain && $subDt[$item->id]['value'][$sub->id] == 0 ? 'checked' : null }}>
                                        @else
                                            <input class="form-check-input" type="radio"
                                                name="subDt[{{ $item->id }}][{{ $sub->id }}]" value="0" checked>
                                        @endif
                                        <label class="form-check-label">Tidak Ada</label>
                                    </div>
                                    <div class="form-check d-inline-block">
                                        <input class="form-check-input" type="radio"
                                            name="subDt[{{ $item->id }}][{{ $sub->id }}]" value="2"
                                            {{ $remain && $subDt[$item->id]['value'][$sub->id] == 2 ? 'checked' : null }}>
                                        <label class="form-check-label">Tidak Perlu</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <textarea class="form-control" name="saranSubDt[{{ $sub->id }}]" rows="2">{{ $remain ? $subDt[$item->id]['saran'][$sub->id] : null }}</textarea>
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
                                    <input class="form-check-input" type="radio" name="itemDt[{{ $item->id }}]"
                                        value="1" {{ old('itemDt[' . $item->id . ']') == '1' ? 'checked' : null }}
                                        {{ $remain && $itemPt[$item->id] == 1 ? 'checked' : null }}
                                        >
                                    <label class="form-check-label">Ada</label>
                                </div>
                                <div class="form-check d-inline-block mx-3">
                                    @if (old('itemDt[' . $item->id . ']'))
                                        <input class="form-check-input" type="radio"
                                            name="itemDt[{{ $item->id }}]" value="0"
                                            {{ old('itemDt[' . $item->id . ']') == '0' ? 'checked' : null }}>
                                    @else
                                        <input class="form-check-input" type="radio"
                                            name="itemDt[{{ $item->id }}]" value="0"                                            
                                            {{ $remain && $itemPt[$item->id] == 0 ? 'checked' : null }} checked>
                                    @endif
                                    <label class="form-check-label">Tidak Ada</label>
                                </div>
                                <div class="form-check d-inline-block">
                                    <input class="form-check-input" type="radio" name="itemDt[{{ $item->id }}]"
                                        value="2" {{ old('itemDt[' . $item->id . ']') == '2' ? 'checked' : null }}
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
    @if ($row->name == doc(3, $head->type))
        <div class="col-md-12">
            <h6>{{ $row->name }} </h6>
            @php $no = 1; @endphp
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
                                            name="subDpl[{{ $item->id }}][{{ $sub->id }}]" value="1"
                                            {{ $remain && $subDpl[$item->id]['value'][$sub->id] == 1 ? 'checked' : null }}>
                                        <label class="form-check-label">Ada</label>
                                    </div>
                                    <div class="form-check d-inline-block mx-3">
                                        <input class="form-check-input" type="radio"
                                            name="subDpl[{{ $item->id }}][{{ $sub->id }}]" value="0"
                                            {{ $remain && $subDpl[$item->id]['value'][$sub->id] == 0 ? 'checked' : null }}>                                 
                                        <label class="form-check-label">Tidak Ada</label>
                                    </div>
                                    <div class="form-check d-inline-block">
                                        @if ($remain)
                                            <input class="form-check-input" type="radio"
                                                name="subDpl[{{ $item->id }}][{{ $sub->id }}]" value="2"
                                                {{ $remain && $subDpl[$item->id]['value'][$sub->id] == 2 ? 'checked' : null }}>
                                        @else            
                                            <input class="form-check-input" type="radio"
                                            name="subDpl[{{ $item->id }}][{{ $sub->id }}]" value="2" checked>                             
                                        @endif
                                        <label class="form-check-label">Tidak Perlu</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <textarea class="form-control" name="saranSubDpl[{{ $sub->id }}]" rows="2">{{ $remain ? $subDpl[$item->id]['saran'][$sub->id] : null }}</textarea>
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
                                    <input class="form-check-input" type="radio" name="itemDpl[{{ $item->id }}]"
                                        value="1" {{ $remain && $itemDpl[$item->id] == 1 ? 'checked' : null }}>
                                    <label class="form-check-label">Ada</label>
                                </div>
                                <div class="form-check d-inline-block mx-3">
                                    <input class="form-check-input" type="radio"
                                        name="itemDpl[{{ $item->id }}]" value="0"
                                        {{ $remain && $itemDpl[$item->id] == 0 ? 'checked' : null }}>                    
                                    <label class="form-check-label">Tidak Ada</label>
                                </div>
                                <div class="form-check d-inline-block">
                                @if ($remain)
                                <input class="form-check-input" type="radio" name="itemDpl[{{ $item->id }}]"
                                    value="2" {{ $remain && $itemDpl[$item->id] == 2 ? 'checked' : null }}>                                    
                                @else
                                <input class="form-check-input" type="radio" name="itemDpl[{{ $item->id }}]"
                                    value="2" checked>
                                @endif
                                    <label class="form-check-label">Tidak Perlu</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <textarea class="form-control" name="saranItemDpl[{{ $item->id }}]" rows="2">{{ $remain ? $saranItemDpl[$item->id] : null}}</textarea>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    @endif
@endforeach
@if($head->type == 'umum')
    <div class="form-group">
        <h6>Lain-lain</h6>
        <div class="row mb-3" id="master">
            @if($old && $old->other)
                @for($i=0;$i < count($other); $i++)
                <div class="col-md-3">
                    <input type="text" class="form-control" name="nameOther[{{$i}}]" value="{{$other[$i]->name}}" placeholder="item name">
                </div>
                <div class="col-md-5">
                    <div class="d-flex justify-content-center">
                        <div class="form-check d-inline-block">
                            <input class="form-check-input" type="radio" name="item[{{$i}}]" value="1" {{ $other[$i]->value == '1' ? 'checked' : null  }}>
                            <label class="form-check-label">Ada</label>
                        </div>
                        <div class="form-check d-inline-block mx-3">
                            <input class="form-check-input" type="radio" name="item[{{$i}}]" value="0" {{ $other[$i]->value == '0' ? 'checked' : null  }}>
                            <label class="form-check-label">Tidak Ada</label>
                        </div>
                        <div class="form-check d-inline-block">
                            <input class="form-check-input" type="radio" name="item[{{$i}}]" value="2" {{ $other[$i]->value == '2' ? 'checked' : null  }}>
                            <label class="form-check-label">Tidak Perlu</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <textarea class="form-control" name="saranOther[{{$i}}]" rows="2">{{$other[$i]->saran}}</textarea>
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
                            <input class="form-check-input" type="radio" name="item[0]" value="0" checked>
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
@endif
<label>Saran :</label>
    <textarea class="form-control summernote" name="content" rows="2">
    {!! $remain ? $head->saran : null !!}
    </textarea>      
</div>
