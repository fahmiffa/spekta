@extends('layout.pdf')

@section('main')
    @include('verifikator.doc.header')

    <main style="margin-top: 0.2rem">
        <p style="font-weight: bold; margin-top:1rem;">{{ $docs->tag }}</p>
        @php
            $VL3 = $head->steps->where('kode', 'VL3')->first();
            $VL2 = $head->steps->where('kode', 'VL2')->first();
            if ($VL3) {
                $da = json_decode($VL3->item);
                $itemDa = (array) $da->dokumen_administrasi->item;
                $saranItemDa = (array) $da->dokumen_administrasi->saranItem;
                $sub = (array) $da->dokumen_administrasi->sub;

                foreach ($sub as $key => $value) {
                    $subDa[$value->title] = ['saran' => (array) $value->saran, 'value' => (array) $value->value];
                    foreach ($value->value as $val) {
                        $vSubDa[] = $val;
                    }
                }

                $vitemDa[] = in_array(1, $itemDa);
                $vitemDa[] = in_array(0, $itemDa);
                $vitemDa[] = in_array(1, $vSubDa);
                $vitemDa[] = in_array(0, $vSubDa);
                $vitemDa = in_array(true, $vitemDa);
            }

            if ($VL2) {
                $da = json_decode($VL2->item);
                $type = $head->type == 'umum' ? 'dokumen_teknis' : 'persyaratan_teknis';
                if ($head->type == 'menara') {
                    $itemPt = (array) $da->persyaratan_teknis->item;
                    $saranItemPt = (array) $da->$type->saranItem;
                }

                $subt = (array) $da->$type->sub;

                foreach ($subt as $key => $value) {
                    $subDt[$value->title] = ['saran' => (array) $value->saran, 'value' => (array) $value->value];
                    foreach ($value->value as $val) {
                        $vsubDt[] = $val;
                    }
                }

                if ($head->type == 'umum') {
                    $other = $VL2->other ? json_decode($VL2->other) : null;

                    $itemDpl = (array) $da->dokumen_pendukung_lainnya->item;
                    $saranItemDpl = (array) $da->dokumen_pendukung_lainnya->saranItem;
                    $subdpl = (array) $da->dokumen_pendukung_lainnya->sub;

                    foreach ($subdpl as $key => $value) {
                        $subDpl[$value->title] = ['saran' => (array) $value->saran, 'value' => (array) $value->value];
                        foreach ($value->value as $val) {
                            $vSubDpl[] = $val;
                        }
                    }

                    $vDpl[] = in_array(1, $itemDpl);
                    $vDpl[] = in_array(0, $itemDpl);
                    $vDpl[] = in_array(1, $vSubDpl);
                    $vDpl[] = in_array(0, $vSubDpl);
                    $vDpl = in_array(true, $vDpl);
                }
            }

        @endphp

        <table autosize="1" style="width: 100%">
            <tbody>
                @foreach ($docs->title as $row)
                    @php $no = 1; @endphp
                    @if ($VL3 && $row->name == doc(5, $head->type) && $vitemDa)
                        <tr style="font-weight: bold;">
                            <td width="5%" align="center">{{ strtoupper(Abjad($loop->index)) }}.</td>
                            <td colspan="2" width="50%">&nbsp;{{ $row->name }}</td>
                            <td width="10%" align="center">Status</td>
                            <td width="35%" align="center">Catatan / Saran</td>
                        </tr>
                        @foreach ($row->items as $item)
                            @if (count($item->sub) > 0)
                                @php
                                    $valsubDa = $subDa[$item->id]['value'];
                                    $vw0 = in_array(0, $valsubDa);
                                    $vw1 = in_array(1, $valsubDa);
                                    $indeks = 0;
                                @endphp
                                @if ($vw0 || $vw1)
                                    <tr>
                                        <td style="text-align: right; vertical-align:top">{{ $no++ }}&nbsp;</td>
                                        <td colspan="4">&nbsp;{{ $item->name }}</td>
                                    </tr>
                                @endif
                                @foreach ($item->sub as $sub)
                                    @if ($subDa[$item->id]['value'][$sub->id] != 2)
                                        <tr>
                                            <td></td>
                                            <td width="1%" style="vertical-align:top;border-right:0px">
                                                &nbsp;{{ abjad($indeks++) }}. </td>
                                            <td style="border-left:0px">&nbsp;{{ $sub->name }}</td>
                                            <td align="center">{{ status($subDa[$item->id]['value'][$sub->id]) }}</td>
                                            <td style="text-align: left;padding:0.2rem">{{ $subDa[$item->id]['saran'][$sub->id] }}</td>
                                        </tr>
                                    @else
                                    @php $indeks -= $indeks; @endphp
                                    @endif
                                @endforeach
                            @else
                                @if ($itemDa[$item->id] != 2)
                                    <tr>
                                        <td style="text-align: right; vertical-align:top">{{ $no++ }}&nbsp;</td>
                                        <td colspan="2">&nbsp;{{ $item->name }}
                                        <td align="center">{{ status($itemDa[$item->id]) }}</td>
                                        <td style="text-align: left;padding:0.2rem">{{ $saranItemDa[$item->id] }}</td>
                                    </tr>
                                @endif
                            @endif
                        @endforeach
                    @endif

                    @if($VL2)
                        @if ($row->name == doc(4, $head->type))
                            <tr style="font-weight: bold;">
                                <td width="5%" align="center">{{ strtoupper(Abjad($loop->index)) }}.</td>
                                <td colspan="2" width="50%">&nbsp;{{ $row->name }}</td>
                                <td width="10%" align="center">Status</td>
                                <td width="35%" align="center">Catatan / Saran</td>
                            </tr>
                            @foreach ($row->items as $item)
                                @if (count($item->sub) > 0)
                                    @php
                                        $valsubDt = $subDt[$item->id]['value'];
                                        $vw0 = in_array(0, $valsubDt);
                                        $vw1 = in_array(1, $valsubDt);
                                        $indeks = 0;
                                    @endphp
                                    @if ($vw0 || $vw1)
                                        <tr>
                                            <td style="text-align: right; vertical-align:top">{{ $no++ }}&nbsp;</td>
                                            <td colspan="4">&nbsp;{{ $item->name }}</td>
                                        </tr>
                                    @endif
                                    @foreach ($item->sub as $sub)          
                                        @if ($subDt[$item->id]['value'][$sub->id] != 2)
                                        <tr>
                                            <td></td>
                                            <td width="1%" style="vertical-align:top;border-right:0px">
                                                &nbsp;{{ abjad($indeks++) }}. </td>
                                                <td style="border-left:0px">&nbsp;{{ $sub->name }}</td>
                                                <td align="center">{{ status($subDt[$item->id]['value'][$sub->id]) }}</td>
                                                <td style="text-align: left;padding:0.2rem">{{ $subDt[$item->id]['saran'][$sub->id] }}</td>
                                            </tr>
                                        @else
                                            @php  $indeks -= $indeks;  @endphp
                                        @endif
                                    @endforeach
                                @else
                                    @if ($itemPt[$item->id] != 2)
                                        <tr>
                                            <td style="text-align: right; vertical-align:top">{{ $no++ }}&nbsp;</td>
                                            <td colspan="2">&nbsp;{{ $item->name }}
                                            <td align="center">{{ status($itemPt[$item->id]) }}</td>
                                            <td style="text-align: left;padding:0.2rem">{{ $saranItemPt[$item->id] }}</td>
                                        </tr>
                                    @endif
                                @endif
                            @endforeach
                        @endif

                        @if ($row->name == doc(3, $head->type) && $vDpl)
                            <tr style="font-weight: bold;" id="dpl">
                                <td width="5%" align="center">{{ strtoupper(Abjad($loop->index)) }}.</td>
                                <td colspan="2" width="50%">&nbsp;{{ $row->name }}</td>
                                <td width="10%" align="center">Status</td>
                                <td width="35%" align="center">Catatan / Saran</td>
                            </tr>
                            @foreach ($row->items as $item)
                                @if (count($item->sub) > 0)
                                    @php
                                        $valSubDpl = $subDpl[$item->id]['value'];
                                        $vw0 = in_array(0, $valSubDpl);
                                        $vw1 = in_array(1, $valSubDpl);
                                        $indeks = 0;
                                    @endphp
                                    @if ($vw0 || $vw1)
                                        <tr>
                                            <td style="text-align: right; vertical-align:top">{{ $no++ }}&nbsp;</td>
                                            <td colspan="4">&nbsp;{{ $item->name }}</td>
                                        </tr>
                                        @foreach ($item->sub as $sub)
                                            @if ($subDpl[$item->id]['value'][$sub->id] != 2)
                                                <tr>
                                                    <td></td>
                                                    <td width="1%" style="vertical-align:top;border-right:0px">
                                                        &nbsp;{{ abjad($indeks++) }}. </td>
                                                    <td style="border-left:0px">&nbsp;{{ $sub->name }}</td>
                                                    <td align="center">{{ status($subDpl[$item->id]['value'][$sub->id]) }}</td>
                                                    <td style="text-align: left;padding:0.2rem">{{ $subDpl[$item->id]['saran'][$sub->id] }}</td>
                                                </tr>
                                            @else
                                            @php $indeks -= $indeks;  @endphp
                                            @endif
                                        @endforeach
                                    @endif
                                @else
                                    @if ($itemDpl[$item->id] != 2)
                                        <tr>
                                            <td style="text-align: right; vertical-align:top">{{ $no++ }}&nbsp;</td>
                                            <td colspan="2">&nbsp;{{ $item->name }}
                                            <td align="center">{{ status($itemDpl[$item->id]) }}</td>
                                            <td style="text-align: left;padding:0.2rem">{{ $saranItemDpl[$item->id] }}</td>
                                        </tr>
                                    @endif
                                @endif
                            @endforeach
                        @endif
                    @endif
                @endforeach

                @if($VL2)
                    @if ($head->type == 'umum' && $other)
                        <tr style="font-weight: bold;">
                            <td style="text-align: center">D.</td>
                            <td colspan="4">&nbsp;Lain-lain</td>
                        </tr>

                        @for ($i = 0; $i < count($other); $i++)
                            @if ($other[$i]->value != 2)
                                <tr>
                                    <td></td>
                                    <td width="1%" style="vertical-align:top;border-right:0px">
                                        &nbsp;{{ abjad($i) }}. </td>
                                    <td style="border-left:0px">&nbsp;{{ $other[$i]->name }}</td>
                                    <td align="center">{{ status($other[$i]->value) }}</td>
                                    <td style="text-align: left;padding:0.2rem">{{ $other[$i]->saran }}</td>
                                </tr>
                            @endif
                        @endfor
                    @endif
                @endif
            </tbody>
        </table>
    </main>

    @include('verifikator.doc.footer')

    @if ($head->deleted_at && $num)
        <script type="text/php"> 
            if (isset($pdf)) { 
                //Shows number center-bottom of A4 page with $x,$y values
                $x = 35;  //X-axis i.e. vertical position 
                $y = 980; //Y-axis horizontal position
                $text = "Verifikasi ke-{{$num}} | Perbaikan Dokumen";  
                $font =  $fontMetrics->get_font("helvetica", "bold");
                $size = 7;
                $color = array(255,0,0);
                $word_space = 0.0;  //  default
                $char_space = 0.0;  //  default
                $angle = 0.0;   //  default
                $pdf->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);
            }
        </script>
    @endif
    @if($head->grant == 1)
        <script type="text/php"> 
                if (isset($pdf)) { 
                    //Shows number center-bottom of A4 page with $x,$y values
                    $x = 35;  //X-axis i.e. vertical position 
                    $y = 980; //Y-axis horizontal position
                    $text = "Verifikasi ke-{{$num}} | Selesai Verifikasi";  
                    $font =  $fontMetrics->get_font("helvetica", "bold");
                    $size = 7;
                    $color = array(0,0,255);
                    $word_space = 0.0;  //  default
                    $char_space = 0.0;  //  default
                    $angle = 0.0;   //  default
                    $pdf->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);
                }
            </script>
    @endif



    @php  $header = (array) json_decode($head->header); @endphp
    <script type="text/php"> 
        if (isset($pdf)) { 
            //Shows number center-bottom of A4 page with $x,$y values
            $x = 300;  //X-axis vertical position 
            $y = 980; //Y-axis horizontal position
            $text = "Lembar Verifikasi Dokumen No. Registrasi {{$header[0]}} | Halaman {PAGE_NUM} dari {PAGE_COUNT}";             
            $font =  $fontMetrics->get_font("helvetica", "bold");
            $size = 7;
            $color = array(0,0,0);
            $word_space = 0.0;  //  default
            $char_space = 0.0;  //  default
            $angle = 0.0;   //  default
            $pdf->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);
        }
    </script> 
@endsection
