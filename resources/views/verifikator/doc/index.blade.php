@extends('layout.pdf')

@section('main')
    @include('verifikator.doc.header')

    <main style="margin-top: 0.2rem">
        <p style="font-weight: bold; margin-top:1rem;">{{ $docs->tag }}</p>
        @php

            $VL1 = $head->steps->where('kode', 'VL1')->first();
            $da = json_decode($VL1->item);

            $other = $VL1->other ? json_decode($VL1->other) : null;
            if ($other) {
                foreach ($other as $side) {
                    $oth[] = $side->value;
                }
                $vother[] = in_array(0, $oth);
                $vother[] = in_array(1, $oth);
                $vother = in_array(true, $vother);
            }

            $itemDa = (array) $da->dokumen_administrasi->item;
            $saranItemDa = (array) $da->dokumen_administrasi->saranItem;
            $sub = (array) $da->dokumen_administrasi->sub;

            if ($head->type == 'umum') {
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
            } else {
                $itemPt = (array) $da->persyaratan_teknis->item;
                $saranItemPt = (array) $da->persyaratan_teknis->saranItem;
                $vitemDt[] = in_array(1, $itemPt);
                $vitemDt[] = in_array(0, $itemPt);
            }

            $type = $head->type == 'umum' ? 'dokumen_teknis' : 'persyaratan_teknis';
            $subt = (array) $da->$type->sub;

            foreach ($sub as $key => $value) {
                $subDa[$value->title] = ['saran' => (array) $value->saran, 'value' => (array) $value->value];
                foreach ($value->value as $val) {
                    $vSubDa[] = $val;
                }
            }

            foreach ($subt as $key => $value) {
                $subDt[$value->title] = ['saran' => (array) $value->saran, 'value' => (array) $value->value];
                foreach ($value->value as $val) {
                    $vsubDt[] = $val;
                }
            }

            $vitemDa[] = in_array(1, $itemDa);
            $vitemDa[] = in_array(0, $itemDa);
            $vitemDa[] = in_array(1, $vSubDa);
            $vitemDa[] = in_array(0, $vSubDa);
            $vitemDa = in_array(true, $vitemDa);

            $vitemDt[] = in_array(1, $vsubDt);
            $vitemDt[] = in_array(0, $vsubDt);
            $vitemDt = in_array(true, $vitemDt);

        @endphp

        <table autosize="1" style="width: 100%">
            <tbody>
                @php
                    $last = 0;
                @endphp
                @foreach ($docs->title as $row)
                    @php
                        $no = 1;
                        $last++;
                    @endphp
                    @if ($row->name == doc(5, $head->type) && $vitemDa)
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
                                        <td style="text-align: right;">{{ $no++ }}&nbsp;</td>
                                        <td colspan="4">&nbsp;{{ $item->name }}</td>
                                    </tr>
                                @endif
                                @foreach ($item->sub as $sub)
                                    @if ($subDa[$item->id]['value'][$sub->id] != 2)
                                        <tr>
                                            <td></td>
                                            <td width="1%" style="border-right:0px">
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

                    @if ($row->name == doc(4, $head->type) && $vitemDt)
                        <tr style="font-weight: bold;">
                            <td width="5%" align="center">{{ strtoupper(Abjad($loop->index)) }}.</td>
                            <td colspan="2" width="50%">&nbsp;{{ $row->name }} {{ doc(4, $head->type) }}</td>
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
                                        @php $indeks -= $indeks; @endphp
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
                                            @php $indeks -= $indeks; @endphp
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
                @endforeach
                @if ($head->type == 'umum' && $other && $vother)
                    <tr style="font-weight: bold;">
                        <td align="center">{{ strtoupper(Abjad($last)) }}. </td>
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
