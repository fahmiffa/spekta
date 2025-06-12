<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;


Route::get('/clear', function () {       
    Artisan::call('optimize:clear');
    File::put(storage_path('logs/laravel.log'), '');
    return 'Log cleared';
});


Route::get('/simulasiretribusi', [App\Http\Controllers\HomeController::class, 'simulasiRetribusi'])->name('simulasi');
Route::get('/truncate', [App\Http\Controllers\HomeController::class, 'truncate'])->name('truncate');
Route::get('/lp', [App\Http\Controllers\AuthController::class, 'login'])->name('login');
Route::get('/', [App\Http\Controllers\HomeController::class, 'home'])->name('home');
Route::post('/store', [App\Http\Controllers\HomeController::class, 'store'])->name('store');
Route::post('/login', [App\Http\Controllers\AuthController::class, 'log'])->name('sign');
Route::get('/forgot', [App\Http\Controllers\AuthController::class, 'forgot'])->name('forgot');
Route::post('/forgot', [App\Http\Controllers\AuthController::class, 'forget'])->name('forget');
Route::post('/reset/{id}', [App\Http\Controllers\AuthController::class, 'preset'])->name('preset');
Route::get('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');
Route::get('/recovery/{id}', [App\Http\Controllers\AuthController::class, 'reset'])->name('recovery');
Route::get('/reload-captcha', [App\Http\Controllers\AuthController::class, 'reloadCaptcha']);
Route::get('link/{id}', [App\Http\Controllers\HomeController::class, 'link'])->name('link');
Route::get('/dokumene/{id}', [App\Http\Controllers\HomeController::class, 'dok'])->name('dok');
Route::get('surat/{id}', [App\Http\Controllers\HomeController::class, 'surat'])->name('surat');  
Route::get('part-doc/{id}/{par}', [App\Http\Controllers\HomeController::class, 'dok'])->name('req.dok'); 
Route::get('bak-ttd/{id}', [App\Http\Controllers\HomeController::class, 'ttd'])->name('ttd'); 
Route::get('bak-doc/{id}', [App\Http\Controllers\HomeController::class, 'docBak'])->name('docBak'); 
Route::post('bak-ttd/{id}', [App\Http\Controllers\HomeController::class, 'ttdp'])->name('ttdp');  
Route::get('/send', [App\Http\Controllers\HomeController::class, 'send'])->name('home');

Route::group(['middleware' => 'auth'], function() {    
    
    Route::group(['prefix'=>'home'],function() {
        Route::post('/export', [App\Http\Controllers\HomeController::class, 'export'])->name('export');
        Route::post('/notif', [App\Http\Controllers\HomeController::class, 'notif'])->name('notif');
        Route::get('/try', [App\Http\Controllers\HomeController::class, 'try'])->name('try');
        Route::get('/profile', [App\Http\Controllers\HomeController::class, 'profile'])->name('profile');
        Route::post('/profile', [App\Http\Controllers\HomeController::class, 'profiled'])->name('profiled');
        Route::post('/image', [App\Http\Controllers\HomeController::class, 'image'])->name('image');
        Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('main'); 
        Route::get('permohonan', [App\Http\Controllers\HomeController::class, 'req'])->name('req.index');   
        Route::post('permohonan-simbg/{id}', [App\Http\Controllers\HomeController::class, 'simbg'])->name('simbg');     
        Route::get('dokumen/{id}', [App\Http\Controllers\HomeController::class, 'doc'])->name('req.doc'); 
        Route::get('monitoring', [App\Http\Controllers\HomeController::class, 'monitoring'])->name('monitoring');  
        Route::get('pending', [App\Http\Controllers\HomeController::class, 'pending'])->name('pending');         
    });

    Route::group(['prefix'=>'master'],function() {   
        
        Route::group(['prefix'=>'setting'],function() {        
            Route::get('all', [App\Http\Controllers\Account\UserController::class, 'shst'])->name('shst');   
            Route::post('shst', [App\Http\Controllers\Account\UserController::class, 'shsts'])->name('shsts');   
        });

        
        Route::group(['prefix'=>'account'],function() {   
            Route::resource('role', App\Http\Controllers\Account\RoleController::class);  
            Route::resource('permission', App\Http\Controllers\Account\PermissionController::class);  
            Route::resource('user', App\Http\Controllers\Account\UserController::class);  
            Route::post('drop/{id}', [App\Http\Controllers\Account\UserController::class, 'drop'])->name('user.drop');  
        });

        // master 
        Route::group(['prefix'=>'dokumen'],function() {   
            Route::resource('formulir', App\Http\Controllers\FormulirController::class);  
            Route::resource('letter', App\Http\Controllers\LetterController::class);  
            Route::resource('header', App\Http\Controllers\Item\HeaderController::class);  
            Route::resource('footer', App\Http\Controllers\Item\FooterController::class);    
            Route::resource('title', App\Http\Controllers\Item\TitleController::class);  
            Route::resource('item', App\Http\Controllers\Item\ItemController::class);     
            Route::resource('sub', App\Http\Controllers\Item\SubController::class);         
        });    
        Route::resource('kecamatan', App\Http\Controllers\DistrictController::class);  
        Route::resource('desa', App\Http\Controllers\VillageController::class); 
        Route::post('import', [App\Http\Controllers\SuperController::class, 'import'])->name('import');
        Route::post('reset', [App\Http\Controllers\SuperController::class, 'reset'])->name('reset');  
    
        Route::group(['prefix'=>'formulir'],function() {   
            Route::resource('document', App\Http\Controllers\Formulir\DocumentController::class);  
            Route::get('step/{id}', [App\Http\Controllers\Formulir\DocumentController::class, 'step'])->name('step.index');    
            Route::post('step-store/{id}', [App\Http\Controllers\Formulir\DocumentController::class, 'steps'])->name('step.store');    
            Route::post('step-destroy/{id}', [App\Http\Controllers\Formulir\DocumentController::class, 'stepd'])->name('step.destroy');    
        });
    });

    Route::group(['prefix'=>'task'],function() {  
        
        Route::post('bak-generals', [App\Http\Controllers\NewsController::class, 'generals'])->name('bak.general'); 
        Route::get('bak-input/{id}', [App\Http\Controllers\SuperController::class, 'inputBak'])->name('super.bak'); 
        Route::post('bak-destroy/{id}', [App\Http\Controllers\SuperController::class, 'destroyBak'])->name('super.bak.destroy'); 

        Route::get('barp-input/{id}', [App\Http\Controllers\SuperController::class, 'inputBarp'])->name('super.barp'); 
        Route::post('barp-destroy/{id}', [App\Http\Controllers\SuperController::class, 'destroyBarp'])->name('super.barp.destroy'); 


        // dokumen pending
        Route::get('berita-acara-konsultasi', [App\Http\Controllers\PendingController::class, 'index'])->name('bak.pending'); 
        Route::post('hold/{id}', [App\Http\Controllers\PendingController::class, 'hold'])->name('super.hold'); 

        Route::resource('verification', App\Http\Controllers\VerificationController::class);  
        Route::get('verifikasi', [App\Http\Controllers\VerificationController::class, 'index'])->name('verification.index'); 
        Route::get('verifikasi-step/{id}', [App\Http\Controllers\VerificationController::class, 'step'])->name('step.verifikasi');  
        Route::get('edit-step/{id}', [App\Http\Controllers\VerificationController::class, 'modif'])->name('edit.verifikasi');  
        Route::post('next-step/{id}', [App\Http\Controllers\VerificationController::class, 'next'])->name('next.verifikasi'); 
        Route::post('pub-step/{id}', [App\Http\Controllers\VerificationController::class, 'pub'])->name('pub.verifikasi');    
        Route::post('back-step/{id}', [App\Http\Controllers\VerificationController::class, 'back'])->name('back.verifikasi'); 
        Route::post('next-tahap/{id}', [App\Http\Controllers\VerificationController::class, 'nexts'])->name('nexts.verifikasi'); 
        Route::post('pubs-tahap/{id}', [App\Http\Controllers\VerificationController::class, 'pubs'])->name('pubs.verifikasi');  

        Route::resource('news', App\Http\Controllers\NewsController::class);  
        Route::get('bak', [App\Http\Controllers\NewsController::class, 'index'])->name('news.index'); 
        Route::get('doc-bak/{id}', [App\Http\Controllers\NewsController::class, 'doc'])->name('doc.news'); 
        Route::get('konsultasi/{id}', [App\Http\Controllers\NewsController::class, 'sign'])->name('sign.news');  
        Route::post('news-sign/{id}', [App\Http\Controllers\NewsController::class, 'signed'])->name('signed.news');  
        Route::post('pub-bak/{id}', [App\Http\Controllers\NewsController::class, 'pub'])->name('pub.bak');  
        Route::get('input-bak/{id}', [App\Http\Controllers\NewsController::class, 'step'])->name('step.news'); 
        Route::post('update-pemohon/{id}', [App\Http\Controllers\NewsController::class, 'pemohon'])->name('pemohon.update.news'); 
        Route::post('village', [App\Http\Controllers\NewsController::class, 'village'])->name('village.news');   
        Route::post('next-news/{id}', [App\Http\Controllers\NewsController::class, 'next'])->name('next.news');    
        Route::post('bak-draft', [App\Http\Controllers\NewsController::class, 'draft'])->name('next.draft');    
        Route::post('back-news/{id}', [App\Http\Controllers\NewsController::class, 'back'])->name('back.news'); 


        Route::resource('meet', App\Http\Controllers\MeetController::class);  
        Route::get('barp', [App\Http\Controllers\MeetController::class, 'index'])->name('meet.index'); 
        Route::get('doc-barp/{id}', [App\Http\Controllers\MeetController::class, 'doc'])->name('doc.meet'); 
        Route::get('meet-sign/{id}', [App\Http\Controllers\MeetController::class, 'sign'])->name('sign.meet');  
        Route::post('meet-sign/{id}', [App\Http\Controllers\MeetController::class, 'signed'])->name('signed.meet');  
        Route::post('pub-barp/{id}', [App\Http\Controllers\MeetController::class, 'pub'])->name('pub.barp'); 
        Route::get('input-barp/{id}', [App\Http\Controllers\MeetController::class, 'step'])->name('step.meet'); 
        Route::post('next-meet/{id}', [App\Http\Controllers\MeetController::class, 'next'])->name('next.meet');    
        Route::post('back-meet/{id}', [App\Http\Controllers\MeetController::class, 'back'])->name('back.meet'); 

        Route::resource('attach', App\Http\Controllers\AttachController::class);  
        Route::get('lampiran', [App\Http\Controllers\AttachController::class, 'index'])->name('attach.index');
        Route::get('doc-attach/{id}', [App\Http\Controllers\AttachController::class, 'doc'])->name('doc.attach'); 
        Route::get('attach-step/{id}', [App\Http\Controllers\AttachController::class, 'step'])->name('step.attach'); 
        Route::get('retribusi-step/{id}', [App\Http\Controllers\AttachController::class, 'stepr'])->name('step.tax'); 
        Route::get('retribusi', [App\Http\Controllers\AttachController::class, 'tax'])->name('tax.index');
        Route::post('retribusi/{id}', [App\Http\Controllers\AttachController::class, 'storeTax'])->name('tax.store');  
        Route::get('doc-retribusi/{id}', [App\Http\Controllers\AttachController::class, 'docs'])->name('doc.tax'); 
        Route::get('bak-barp', [App\Http\Controllers\HeaderController::class, 'ba'])->name('ba.verifikasi');  
    });
    
    Route::group(['prefix'=>'dokumen'],function() {   
      
        Route::get('verifikasi/{id}', [App\Http\Controllers\HomeController::class, 'verifikasi'])->name('monitoring.doc'); 
        Route::get('preview/{id}/{par}', [App\Http\Controllers\HomeController::class, 'preview'])->name('preview');    
        
        Route::get('validasi/{id}', [App\Http\Controllers\HeaderController::class, 'validasi'])->name('ba.validasi');
        Route::get('ba-sign/{id}', [App\Http\Controllers\HeaderController::class, 'baSign'])->name('ba.sign');
        Route::post('ba-sign/{id}', [App\Http\Controllers\HeaderController::class, 'baSigned'])->name('ba.signed');
        Route::post('ba-ver/{id}', [App\Http\Controllers\HeaderController::class, 'baVer'])->name('ba.ver');
        Route::post('ba-reject/{id}', [App\Http\Controllers\HeaderController::class, 'baReject'])->name('ba.reject');
          
        Route::get('bak', [App\Http\Controllers\HeaderController::class, 'bak'])->name('bak.verifikasi');  
        Route::post('bak-apporove/{id}', [App\Http\Controllers\HeaderController::class, 'approveBak'])->name('approve.bak');
        Route::post('bak-reject/{id}', [App\Http\Controllers\HeaderController::class, 'rejectBak'])->name('reject.bak');  
        Route::get('doc-bak/{id}', [App\Http\Controllers\HeaderController::class, 'docBak'])->name('bak.doc');        
        
        Route::get('barp', [App\Http\Controllers\HeaderController::class, 'barp'])->name('barp.verifikasi');  
        Route::post('barp-apporove/{id}', [App\Http\Controllers\HeaderController::class, 'approveBarp'])->name('approve.barp');  
        Route::post('barp-reject/{id}', [App\Http\Controllers\HeaderController::class, 'rejctBarp'])->name('reject.barp');  
        Route::get('doc-barp/{id}', [App\Http\Controllers\HeaderController::class, 'docBarp'])->name('barp.doc');  
        Route::resource('consultation', App\Http\Controllers\ConsultationController::class);  
        
        Route::post('village', [App\Http\Controllers\HeadController::class, 'village'])->name('village');    
        Route::post('task', [App\Http\Controllers\HeadController::class, 'task'])->name('task');    

    
        Route::post('doc-apporove/{id}', [App\Http\Controllers\HeadController::class, 'approve'])->name('doc.approve');  
        Route::post('doc-reject/{id}', [App\Http\Controllers\HeadController::class, 'reject'])->name('doc.reject');            
        Route::post('doc-status/{id}', [App\Http\Controllers\HeadController::class, 'open'])->name('doc.status');       

        
    });
    
    Route::group(['prefix'=>'spj'],function() {   
        Route::get('/dokumen', [App\Http\Controllers\HeadController::class, 'spjDoc'])->name('spj.index');  
        Route::get('/file/{id}', [App\Http\Controllers\HeadController::class, 'spjFile'])->name('spj.doc'); 
        Route::get('/preview/{id}', [App\Http\Controllers\HeadController::class, 'spjPreview'])->name('spj.preview'); 
        Route::get('/edit/{id}', [App\Http\Controllers\HeadController::class, 'spjEdit'])->name('spj.edit');  
        Route::get('/create', [App\Http\Controllers\HeadController::class, 'spjCreate'])->name('spj.create');  
        Route::get('/template', [App\Http\Controllers\HeadController::class, 'template'])->name('spj.template'); 
        Route::get('/template/tambah', [App\Http\Controllers\HeadController::class, 'templateAdd'])->name('template.add');   
        Route::post('/template/store', [App\Http\Controllers\HeadController::class, 'templateStore'])->name('template.store');  
        Route::get('/template/{id}', [App\Http\Controllers\HeadController::class, 'templateEdit'])->name('template.edit');   
        Route::post('/store', [App\Http\Controllers\HeadController::class, 'spjStore'])->name('spj.store');  
        Route::post('/data', [App\Http\Controllers\HeadController::class, 'spjData'])->name('spj.data');  

        Route::post('/delete/{id}', [App\Http\Controllers\HeadController::class, 'spjDel'])->name('spj.del'); 
    });

    Route::resource('verifikasi', App\Http\Controllers\HeadController::class);  
});




