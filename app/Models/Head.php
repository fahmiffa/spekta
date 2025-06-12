<?php

namespace App\Models;

use App\Models\User;
use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use QrCode;

class Head extends Model
{
    // protected $appends = ['verif', 'task', 'dokumen', 'qr'];

    use HasFactory, SoftDeletes;

    public function getverifAttribute()
    {

        if ($this->verifikator) {
            $val = explode(",", $this->verifikator);

            foreach ($val as $item) {
                $user = User::where('id', $item)->first();
                if ($user) {
                    $name[] = $user->name;
                }
            }

            return $name;
        } else {
            return null;
        }
    }

    public function gettahapAttribute()
    {

        if ($this->step == 2) {
            $val = explode(",", $this->verifikator);

            foreach ($val as $item) {
                if (Auth::user()->id == $item) {
                    $user = User::where('id', $item)->first();
                    if ($user) {
                        $name = $user->roles->kode;
                        break;
                    }
                }
            }

            return $name;
        } else {
            return null;
        }
    }

    public function gettaskAttribute()
    {

        if ($this->verifikator) {
            $val = explode(",", $this->verifikator);

            if (in_array(Auth::user()->id, $val)) {
                return true;
            } else {
                return false;
            }

        } else {
            return false;
        }
    }

    public function getDokumenAttribute()
    {
        $tax = $this->tax()->exists();
        $attach = $this->attach()->exists();
        $barp = $this->barp()->exists();
        $bak = $this->bak()->exists();
        $kons = $this->kons()->exists();

        if($this->hold == 1)
        {
            $val = 'Ditunda';
        } else if ($attach && $tax && $this->do == 1) {
            $val = 'Selesai';
        } else if ($this->do == 1) {
            $val = 'Pembuatan Lampiran';
        } else if ($this->do == 0 && $barp && $this->barp->status == 1 && $bak && $this->bak->status == 1) {
            $val = 'Verifikasi Kabid / Ketua TPA';
        } else if ($barp) {
            $val = 'Proses Konsultasi (BAK/BARP)';
        } else if ($bak) {
            $val = 'Proses Konsultasi (BAK/BARP)';
        } else if ($kons) {
            $val = 'Penjadwalan Konsultasi';
        } else {
            if ($this->step == 2) {
                if ($this->temp()->whereNotNull('deleted_at')->count() > 0 && $this->grant == 0 && $this->open == 0 && $this->status != 1) {
                    $val = 'Perbaikan Dokumen';
                }
                else if ($this->temp()->whereNotNull('deleted_at')->count() > 0 && $this->grant == 0 && $this->open == 1 && $this->status != 1) {
                    $val = 'Verifikasi Ulang';
                }
                else if ($this->status == 3) {
                    $val = 'Proses Verifikasi';
                } else if ($this->status == 1 && $this->grant == 0) {
                    $val = 'Verifikasi Operator';
                } else if ($this->status == 1 && $this->grant == 1) {
                    $val = 'Penugasan TPT/TPA';
                } else {
                    $val = 'Verifikasi Kelengkapan Dokumen';
                }
            } else {
                if ($this->temp()->whereNotNull('deleted_at')->count() > 0 && $this->grant == 0 && $this->open == 0 && $this->status != 1) {
                    $val = 'Perbaikan Dokumen';
                }
                else if ($this->temp()->whereNotNull('deleted_at')->count() > 0 && $this->grant == 0 && $this->open == 1 && $this->status != 1) {
                    $val = 'Verifikasi Ulang';
                }
                else if ($this->status == 2) {
                    $val = 'Verifikasi Kelengkapan Dokumen';
                } else if ($this->status == 1 && $this->grant == 0) {
                    $val = 'Verifikasi Operator';
                } else if ($this->status == 1 && $this->grant == 1) {
                    $val = 'Penugasan TPT/TPA';
                } else {
                    $val = 'Verifikasi Kelengkapan Dokumen';
                }

            }

        }
        return $val;
    }

    public function getnumberAttribute()
    {
        $bak = $this->bak()->exists();
        $barp = $this->barp()->exists();

        if ($barp) {
            return str_replace('SPm', 'BARP', str_replace('600.1.15', '600.1.15/PBLT', $this->nomor));
        } elseif ($bak) {
            return str_replace('SPm', 'BAK', str_replace('600.1.15', '600.1.15/PBLT', $this->nomor));
        } else {
            // return $this->nomor;
            return null;
        }
    }

    public function numbDoc($par)
    {
        if($this->surat)
        {
            $nom = str_pad($this->surat->id, 4, '0', STR_PAD_LEFT);
            $time = explode('#', $this->surat->waktu);
            $tang = explode('-', $time[2]);
            if ($par == 'bak' && $this->bak()->exists()) {
                return '600.1.15/PBLT/' . $nom . '/BAK-SIMBG/' . numberToRoman($tang[1]) . '/' . $tang[0];
            } else if ($par == 'barp' && $this->barp()->exists()) {

                $date = $this->barp->date;
                if($date)
                {
                    $tango = explode('-', $date);
                    return '600.1.15/PBLT/' . $nom . '/BARP-SIMBG/' . numberToRoman($tango[1]) . '/' . $tang[0];
                }
                else
                {
                    return '600.1.15/PBLT/' . $nom . '/BARP-SIMBG//' . $tang[0];
                }
            }
            else if ($par == 'lampiran' && $this->barp()->exists()) {
                return '600.1.15/PBLT/' . $nom . '/LDP-SIMBG/' . numberToRoman($tang[1]) . '/' . $tang[0];
            }
            else if ($par == 'tax' && $this->barp()->exists()) {
                return '600.1.15/PBLT/' . $nom . '/LDP-SIMBG/' . numberToRoman($tang[1]) . '/' . $tang[0];
            }            
            else if ($par == 'verifikasi') {
                $nom = str_pad($this->id, 4, '0', STR_PAD_LEFT);
                $tang = explode('-', $this->created_at);
                return '600.1.15/PBLT/' . $nom . '/SPm-SIMBG/' . numberToRoman($tang[1]) . '/' . $tang[0];
            } else {
                return null;
            }

        }
        else
        {
            if ($par == 'verifikasi') {
                $nom = str_pad($this->id, 4, '0', STR_PAD_LEFT);
                $tang = explode('-', $this->created_at);
                return '600.1.15/PBLT/' . $nom . '/SPm-SIMBG/' . numberToRoman($tang[1]) . '/' . $tang[0];
            } else {
                return null;
            }
        }

    }

    public function getqrAttribute()
    {

        $val = route('dok', ['id' => base64_encode(md5($this->reg))]);
        return base64_encode(QrCode::format('png')->size(200)->generate($val));

    }

    public function region()
    {
        return $this->belongsTo(Village::class, 'village', 'id');
    }

    public function steps()
    {
        return $this->HasMany(Step::class, 'head', 'id');
    }

    public function head()
    {
        return $this->HasMany(Head::class, 'id', 'parent')->withTrashed();
    }

    public function old()
    {
        return $this->HasOne(Head::class, 'id', 'parent')->withTrashed();
    }

    public function sign()
    {
        return $this->HasMany(Signed::class, 'head', 'id');
    }

    public function tmp()
    {
        return $this->HasMany(Head::class, 'head_id', 'id')->withTrashed();
    }

    public function temp()
    {
        return $this->HasMany(Head::class, 'head_id', 'head_id')->withTrashed();
    }

    public function parents()
    {
        return $this->HasOne(Head::class, 'id', 'head_id')->withTrashed();
    }

    public function kons()
    {
        return $this->HasOne(Consultation::class, 'head', 'id');
    }

    public function link()
    {
        return $this->HasOne(Links::class, 'head', 'id');
    }

    public function links()
    {
        return $this->HasMany(Links::class, 'head', 'id');
    }

    public function surat()
    {
        return $this->HasOne(Schedule::class, 'head', 'id');
    }

    public function bak()
    {
        return $this->HasOne(News::class, 'head', 'id');
    }

    public function bakTemp()
    {
        return $this->HasMany(News::class, 'head', 'id')->latest()->withTrashed();
    }

    public function barp()
    {
        return $this->HasOne(Meet::class, 'head', 'id');
    }

    public function barpTemp()
    {
        return $this->HasMany(Meet::class, 'head', 'id')->latest()->withTrashed();
    }

    public function notulen()
    {
        return $this->HasOne(Notulen::class, 'head', 'id');
    }

    public function attach()
    {
        return $this->HasOne(Attach::class, 'head', 'id');
    }

    public function tax()
    {
        return $this->HasOne(Tax::class, 'head', 'id');
    }
}
