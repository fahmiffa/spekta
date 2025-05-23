<?php
use App\Models\Head;
use App\Models\SummaryHead;
use App\Models\Item;
use App\Models\Sub;
use Carbon\Carbon;
use Intervention\Image\Facades\Image as Image;
use App\Models\Links;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;
use App\Models\Formulir;



function blobImage($da)
{
    $fileContents = file_get_contents($da);
    return base64_encode($fileContents);
}

function splitChar($id)
{
    return rtrim(preg_replace('/(\w{4})/', '$1-', md5($id)), '-');
}

function shortLink($head,$par)
{
    $link = Links::where('ket',$par)->where('head',$head)->first();
    
    $shortUrl = Str::random(6);
    while (Links::where('short', $shortUrl)->exists()) {
        $shortUrl = Str::random(6);
    }

    $link = $link ? $link : new Links;
    $link->head = $head;
    $link->ket = $par;
    $link->short = $shortUrl;
    $link->save();
}

function genPDF($id,$par)
{
    $head = Head::where('id',$id)->first();     
    $shortUrl = Str::random(6);
    while (Links::where('short', $shortUrl)->exists()) {
        $shortUrl = Str::random(6);
    }

    $link = Links::where('ket','verifikasi')->where('head',$id)->first();

    $link = $link ? $link : new Links;
    $link->head  = $id;
    $link->ket   = $par;
    $link->short = $shortUrl;
    $link->save();

    $qrCode = base64_encode(QrCode::format('png')->size(200)->generate(route('link',['id'=>$link->short])));

    if($par == 'verifikasi')
    {
        $num = LogFix($head);
        $docs  = Formulir::where('name',$head->type)->first();         
        $step = $head->step == 1 ? 0 : 1;
        $data = compact('qrCode','docs','head','step','num');
        $view = $head->step == 1 ? 'verifikator.doc.index' : 'verifikator.doc.home';
    }

    $name = $par.'_'.$id.'.pdf';
    $dir = 'assets/data/';
    $path = $dir.$name;
    
    $pdf = PDF::loadView($view, $data)->setPaper('a4', 'potrait');
    Storage::disk('public')->put($path, $pdf->output());   
    $link->files = $path;
    $link->save();
}

function LogFix($head)
{
    $no = 1;
    $temp = $head->temp->count();
    $filter = $head->id;
    $indexs = $head->temp->pluck('id')->toArray();
    $val = array_filter($indexs, function ($value) use ($filter) {
        return $value == $filter;
    });

    if (count($val) > 0) {
        $keys = array_keys($val)[0];
        $no = $keys + 1;
    }
    
    return $no;
}

function headers($id, $index, $val)
{
    $da = Formulir::findOrFail($id);
    $va = json_decode($da->items);
    if ($va->$index) {
        $res = $va->$index;
        return $res[$val];
    } else {
        return 'None';
    }
}

function named($val, $par)
{
    if ($par == 'item') {
        return Item::where('id', $val)->first()->name;
    }

    if ($par == 'sub') {
        return Sub::where('id', $val)->first()->name;
    }
}

function items($id, $index, $val)
{
    $da = Formulir::findOrFail($id);
    $va = json_decode($da->items);
    if ($va->$index) {
        return $va->$index->$val;
    } else {
        return 'None';
    }
}

function doc($val, $type)
{
    if ($type == 'umum') {
        if ($val == 5) {
            return 'Dokumen Administrasi';
        } else if ($val == 4) {
            return 'Dokumen Teknis';
        } else if ($val == 3) {
            return 'Dokumen Pendukung Lainnya (Untuk SLF)';
        }
    } else {
        if ($val == 5) {
            return 'Dokumen Administrasi';
        }
        if ($val == 4) {
            return 'Persyaratan Teknis';
        }

    }
}

function status($val)
{
    if ($val == '0') {
        return 'Tidak Ada';
    } else if ($val == '1') {
        return 'Ada';
    } else if ($val == '2') {
        return 'TIdak Perlu';
    } else {
        return 'Unknown';
    }
}

function gambar($val)
{
    $imagePath = $val; // Replace with your image path
    $imageData = Image::make($imagePath)->encode('data-url')->encoded;
    return $imageData;
}

function nomor()
{
    $year = date('Y');
    $head = SummaryHead::where('digawe',$year)->latest()->count();
    $num = $head + 1;
    $nom = str_pad($num, 4, '0', STR_PAD_LEFT);
    return '600.1.15/PBLT/' . $nom . '/SPm-SIMBG/' . numberToRoman(date('m')) . '/' . date('Y');
}

function baseDoc()
{
    return ['menara', 'umum'];
}

function spjDoc($par=null)
{
    $val = ['rapat_persiapan_survey', 'survey_pbg','rapat_pleno'];
    if($par)
    {
        return $val[$par];
    }

    return $val;
}


function Abjad($index)
{
    $abjad = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'];
    return $abjad[$index];
}

function numberToRoman($number)
{
    $romans = array(
        'M' => 1000,
        'CM' => 900,
        'D' => 500,
        'CD' => 400,
        'C' => 100,
        'XC' => 90,
        'L' => 50,
        'XL' => 40,
        'X' => 10,
        'IX' => 9,
        'V' => 5,
        'IV' => 4,
        'I' => 1,
    );

    $result = '';

    foreach ($romans as $roman => $value) {
        $matches = intval($number / $value);
        $result .= str_repeat($roman, $matches);
        $number = $number % $value;
    }

    return $result;
}

function dateID($par)
{
    $date = Carbon::parse($par);
    $date->setLocale('id');
    $indonesianDate = $date->isoFormat('LL');
    return $indonesianDate;
}

function Hari($tanggal)
{
    $date = Carbon::parse($tanggal)->locale('id');
    $date->settings(['formatFunction' => 'translatedFormat']);
    return $date->format('l, j F Y');
}
