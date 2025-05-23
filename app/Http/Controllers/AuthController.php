<?php

namespace App\Http\Controllers;

use App\Models\User;
use Auth;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Mail\SipMail;
use Mail;
use DB;
use PDF;


class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }


    public function index()
    {
        $data = 'Login';
        return view('log', compact('data'));
    }

    public function login()
    {
        // DB::table('users')->update(['password'=>bcrypt('jalan')]);
        $data = 'Login';
        return view('login', compact('data'));
    }

    public function forgot()
    {
        $data = 'Halaman Lupa Password';
        return view('forgot', compact('data'));
    }

    public function reset($id)
    {
        $id = str_replace('-',null,$id);
        $user = User::where(DB::raw('md5(req)'), $id)->first();
        $res = splitChar($user->id);
        $date = Carbon::createFromTimestamp($user->req); 
        if($user && $date->isFuture())
        {
            $data = 'Halaman Reset Password';
            return view('reset', compact('data','res'));
        }
        else
        {
            toastr()->error('link tidak ditemukan atau kadaluarsa', ['timeOut' => 5000]);
            return Redirect()->route('login');
        }
    }

    public function preset(Request $request, $id)
    {
        $rule = [
            'password' => 'required|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@!$#%&? "]).+$/|min:12',            
            'password_confirmation' => 'required',
        ];
        $message = [
            'required'  =>  'Field :attribute ini harus diisi',
            'min'       =>  ':attribute harus minimal 12 digit',         
            'regex'     =>  ':attribute harus kombinasi Huruf besar kecil, angka dan simbol',  
            'confirmed' =>  'Field :attribute Konfirm tidak valid',
        ];
        $request->validate($rule, $message);
        $id = str_replace('-',null,$id);
        $user = User::where(DB::raw('md5(id)'), $id)->first();
        $user->password = bcrypt($request->password);
        $user->save();

        Auth::login($user);
        toastr()->success('Password berhasil di reset', ['timeOut' => 5000]);
        return redirect()->route('main');
    }

    public function forget(Request $request)
    {
        $rule = [
            'email' => 'required|exists:users,email',
            'captcha' => ['required', 'captcha'],
        ];

        $messages = [
            'email.required' => 'Email harus di isi',
            'email.exists' => 'Email Tidak ada',
            'captcha.required' => 'captcha required',
            'captcha.captcha' => 'captcha invalid',
        ];

        $request->validate($rule, $messages);

        $user = User::where('email', $request->email)->first();
        $exp = Carbon::now('Asia/Jakarta')->addHour(env('EXP'))->timestamp;        
        $user->req = $exp;
        $user->save();


        if (env('MAIL')) {
            $link = route('recovery', ['id' => splitChar($exp)]);

            $details = [
                'title' => 'Reset Password',
                'body' => 'Klik Reset untuk reset password',
                'par' => '<a target="_blank" href="'.$link.'"  style="text-decoration: none;background-color: gray;color: white;padding: 0.5rem;border-radius: 10px;">Reset</a>',
            ];

            Mail::to($user->email)->send(new SipMail($details));
        }

        toastr()->success('Cek email untuk reset passwrod', ['timeOut' => 5000]);
        return back();
    }

    private function mail($var,$head)
    {
        $doc  = head::where('id',$head)->first();

        $header = json_decode($doc->header);

        foreach ($var as  $value) {
            $user = User::where('id',$value)->first();

            $mailData = [
                'title' => 'Yth. '.$user->name,
                'body' => 'Anda mendapatkan tugas untuk melakukan verifikasi terhadap permohonan PBG/SLF dengan Nomor Registrasi :'.$header[0],
                'par' => null,
            ];

            Mail::to($user->email)->send(new SipMail($mailData));
        }
         
    }

    public function pforget(Request $request)
    {

        $messages = [
            'email.required' => 'Email wajib diisikan',
        ];

        $validasi = Validator::make(
            $request->all(),
            [
                'email' => 'required',
            ],
            $messages
        );
        if ($validasi->fails()) {
            return back()->withErrors($validasi)->withInput();
        } else {

            $user = User::where('email', $request->email);
            $da = $user->exists();

            if ($da) {
                $random = $user->first()->id . Str::random(40);
                $now = Carbon::now();
                $exp = Carbon::now()->addHour(env('EXP'));

                $del = Forgot::where('user_id', $user->first()->id)->exists();
                if ($del) {
                    $del->first()->delete();
                }

                Forgot::create([
                    'user_id' => $user->first()->id,
                    'exp' => $exp,
                    'random' => $random,
                ]);

                $link = url('verif/' . $random);

                $send = SendEmail($request->email, config('notif.reset'), $link);
                Alert::success('info', 'Send a link to reset your password, check email');
                return back();
            } else {
                Alert::error('error', 'Email not found');
                return back();
            }

        }

    }

    public function verif($id)
    {

        try {
            $ids = substr($id, 0, 1);

            $user = Forgot::where('user_id', $ids)->where('random', $id);
            $now = Carbon::now();

            if (!$user->exists()) {
                throw new Exception("Link Forgot Password Invalid");

            }

            if ($now > $user->first()->exp) {
                throw new Exception("Link Forgot Password expired");
            }

            $data = 'New Password';
            $da = $user->first();
            return view('ver', compact('data', 'da'));
        } catch (Exception $e) {

            Alert::error('error', $e->getMessage());
            return redirect('forget');

        }

    }

    public function pverif(Request $request, $id)
    {

        $messages = [
            'password.required' => 'password wajib diisikan',
            'password.confirmed' => 'password confirm tidak sama',
        ];

        $validasi = Validator::make(
            $request->all(),
            [
                'password' => 'required|confirmed',
            ],
            $messages
        );
        if ($validasi->fails()) {
            return back()->withErrors($validasi)->withInput();
        } else {

            $user = User::where('id', $id)->first();
            $da = $user->exists();

            if ($da) {
                $user->password = bcrypt($request->password);
                $user->save();
                Alert::success('info', 'Success Update Password');
                return redirect('login');
            } else {
                Alert::error('error', 'Invalid Update Password');
                return redirect('login');
            }

        }

    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        Auth::logout();
        return Redirect()->route('login');
    }

    public function reloadCaptcha()
    {
        return response()->json(['captcha' => captcha_img()]);
    }

    public function log(Request $request)
    {

        $messages = [
            'password.required' => 'Pasword wajib diisikan',
            'email.required' => 'Email wajib diisikan',
            'captcha.required' => 'captcha required',
            'captcha.captcha' => 'captcha invalid',
        ];

        $validasi = Validator::make(
            $request->all(),
            [
                'email' => 'required',
                'password' => 'required',
                'captcha' => ['required', 'captcha'],
            ],
            $messages
        );
        if ($validasi->fails()) {
            return back()->withErrors($validasi)->withInput();
        } else {

            $credensil = $request->only('email', 'password');

            if (Auth::attempt($credensil)) {
                $user = Auth::user();

                if ($user->status == 1) {
                    return redirect()->route('main');
                } else {
                    Auth::logout();
                    toastr()->error('Akun anda di '.$user->note.', Silahkan hubungi Admin', ['timeOut' => 5000]);
                    return back();
                }

            }
            toastr()->error('Akun tidak ditemukan', ['timeOut' => 5000]);
            return back();
        }

    }

  

}
