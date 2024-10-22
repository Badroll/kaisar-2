<?php

use App\Http\Controllers\Admin\HariBesarController;
use App\Http\Controllers\Admin\KursusController;
use App\Http\Controllers\Admin\MstClassController;
use App\Http\Controllers\Admin\PraktekController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InfoUserController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ResetController;
use App\Http\Controllers\SessionsController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TalentController;
use App\Http\Controllers\TeoriController;
use App\Http\Controllers\TestingController;
use App\Http\Controllers\TrainerController;
use App\Http\Controllers\UniversalController;
use App\Models\Teori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/get-sessions', [PraktekController::class, 'getAvailableSessions']);
Route::get('/get-sessions-edit/{id}', [PraktekController::class, 'getAvailableSessionsEdit']);

Route::prefix('admin')->group(function () {
	Route::get('login', [SessionsController::class, 'showLoginAdminForm'])->name('admin.login');
	Route::post('login', [SessionsController::class, 'store'])->name('admin.store-login');

	Route::middleware(['auth', 'role:admin'])->group(function () {
		Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');

		//undergoing
		Route::get('/profil', [AdminController::class, 'create'])->name('admin.profil');
		Route::put('/profil', [AdminController::class, 'store'])->name('admin.profil.store');
		Route::put('/profil/photo-update', [AdminController::class, 'photoUpdate'])->name('admin.profil.photo-update');
		Route::delete('/delete-photo', [AdminController::class, 'deletePhoto'])->name('admin.delete-photo');

		Route::get('users/data', [UserController::class, 'getUsers'])->name('users.data');
		Route::delete('user/{id}/delete-photo', [UserController::class, 'deletePhoto'])->name('users.delete-photo');
		Route::resource('users', UserController::class);
		
		Route::get('kelas/data', [MstClassController::class, 'getKelas'])->name('kelas.data');
		Route::resource('kelas', MstClassController::class);

		Route::get('kursus/data/aktif', [KursusController::class, 'getActiveCourses'])->name('kursus.data.aktif');
		Route::get('kursus/data/selesai', [KursusController::class, 'getCompletedCourses'])->name('kursus.data.selesai');
		Route::get('kursus/{course_id}/jadwal', [KursusController::class, 'view_jadwal'])->name('kursus.buat-jadwal');
		Route::post('kursus/{course_id}/jadwal', [KursusController::class, 'store_jadwal'])->name('kursus.simpan-jadwal');
		Route::get('kursus/{course_id}/teori', [KursusController::class, 'view_teori'])->name('kursus.buat-teori');
		Route::post('kursus/{course_id}/teori', [KursusController::class, 'store_teori'])->name('kursus.simpan-teori');
		Route::resource('kursus', KursusController::class);

		Route::get('/teori/get-dates', [TeoriController::class, 'getDates'])->name('teori.getDates');
		Route::get('teori/data/terjadwal', [TeoriController::class, 'getScheduledTeori'])->name('teori.data.aktif');
		Route::get('teori/data/history', [TeoriController::class, 'getCompletedTeori'])->name('teori.data.selesai');
		Route::post('teori/{id}/cancel', [TeoriController::class, 'cancel'])->name('teori.cancel');
		Route::resource('teori', TeoriController::class);

		Route::get('praktek/data/terjadwal', [PraktekController::class, 'getScheduledPractices'])->name('praktek.data.aktif');
		Route::get('praktek/data/history', [PraktekController::class, 'getCompletedPractices'])->name('praktek.data.selesai');
		Route::post('praktek/{id}/cancel', [PraktekController::class, 'cancel'])->name('praktek.cancel');
		Route::resource('praktek', PraktekController::class);
		
		Route::get('hari-besar/data', [HariBesarController::class, 'getData'])->name('hari-besar.data');
		Route::resource('hari-besar', HariBesarController::class);
	});
});

Route::prefix('trainer')->group(function () {
	Route::get('login', [SessionsController::class, 'showLoginTrainerForm'])->name('trainer.login');
	Route::post('login', [SessionsController::class, 'store'])->name('trainer.store-login');

	Route::middleware(['auth', 'role:trainer'])->group(function () {
		Route::get('/', [TrainerController::class, 'dashboard'])->name('trainer.home');

		Route::get('/profil', [UniversalController::class, 'showProfil'])->name('trainer.profil');
		Route::put('/update-profil', [UniversalController::class, 'updateProfil'])->name('trainer.update-profil');
		Route::put('/update-photo', [UniversalController::class, 'updatePhoto'])->name('trainer.update-photo');
		Route::delete('/delete-photo', [UniversalController::class, 'deletePhoto'])->name('trainer.delete-photo');

		Route::get('/ganti-password', [UniversalController::class, 'showGantiPassword'])->name('trainer.ganti-password');
		Route::put('/ganti-password', [UniversalController::class, 'savePassword'])->name('trainer.simpan-password');

		Route::get('/cari-jadwal', [TrainerController::class, 'searchSchedule'])->name('search.schedule');;		
	});
});

Route::prefix('student')->group(function () {
	Route::get('login', [SessionsController::class, 'showLoginStudentForm'])->name('student.login');
	Route::post('login', [SessionsController::class, 'store'])->name('student.store-login');

	Route::middleware(['auth', 'role:student'])->group(function () {
		Route::get('/', [StudentController::class, 'dashboard'])->name('student.home');
		Route::post('/buat-teori', [StudentController::class, 'storeTeori'])->name('student.store-teori');
		Route::get('/kursus', [StudentController::class, 'allKursus'])->name('student.kursus');
		Route::get('/kursus/{id}/', [StudentController::class, 'showKursus'])->name('student.detail-kursus');
		Route::get('/jadwalkan-teori/{id}/', [StudentController::class, 'showJadwalPraktek'])->name('student.jadwal-praktek');
		Route::post('/jadwalkan-teori/{id}/', [StudentController::class, 'storeJadwalPraktek'])->name('student.store-praktek');
		Route::get('/teori/get-dates', [StudentController::class, 'getDates'])->name('student.getTeori');
		
		Route::get('/profil', [UniversalController::class, 'showProfil'])->name('student.profil');
		Route::put('/update-profil', [UniversalController::class, 'updateProfil'])->name('student.update-profil');
		Route::put('/update-photo', [UniversalController::class, 'updatePhoto'])->name('student.update-photo');
		Route::delete('/delete-photo', [UniversalController::class, 'deletePhoto'])->name('student.delete-photo');

		Route::get('/ganti-password', [UniversalController::class, 'showGantiPassword'])->name('student.ganti-password');
		Route::put('/ganti-password', [UniversalController::class, 'savePassword'])->name('student.simpan-password');
	});
});

Route::prefix('talent')->group(function () {
	Route::get('login', [SessionsController::class, 'showLoginTalentForm'])->name('talent.login');
	Route::post('login', [SessionsController::class, 'store'])->name('talent.store-login');
	Route::get('register', [SessionsController::class, 'showRegisterTalentForm'])->name('talent.register');
	Route::post('register', [SessionsController::class, 'register'])->name('talent.store-register');

	Route::middleware(['auth', 'role:talent'])->group(function () {
		Route::get('/', [TalentController::class, 'dashboard'])->name('talent.home');
		Route::get('/riwayat-potong', [TalentController::class, 'riwayatPotong'])->name('talent.riwayat-potong');
		Route::get('/cari-jadwal', [TalentController::class, 'cariJadwal'])->name('talent.cari-jadwal');
		Route::get('/pilih-jadwal/{id}/', [TalentController::class, 'pilihJadwal'])->name('talent.pilih-jadwal');
		Route::post('/store-jadwal/{id}/', [TalentController::class, 'storeJadwal'])->name('talent.store-jadwal');
		
		Route::get('/profil', [UniversalController::class, 'showProfil'])->name('talent.profil');
		Route::put('/update-profil', [UniversalController::class, 'updateProfil'])->name('talent.update-profil');
		Route::put('/update-photo', [UniversalController::class, 'updatePhoto'])->name('talent.update-photo');
		Route::delete('/delete-photo', [UniversalController::class, 'deletePhoto'])->name('talent.delete-photo');

		Route::get('/ganti-password', [UniversalController::class, 'showGantiPassword'])->name('talent.ganti-password');
		Route::put('/ganti-password', [UniversalController::class, 'savePassword'])->name('talent.simpan-password');
	});
});

// Route::group(['middleware' => 'auth'], function () {
// 	Route::get('/', [HomeController::class, 'home']);
// 	Route::get('dashboard', function () {
// 		return view('dashboard');
// 	})->name('dashboard');

// 	Route::get('billing', function () {
// 		return view('billing');
// 	})->name('billing');

// 	Route::get('profile', function () {
// 		return view('profile');
// 	})->name('profile');

// 	Route::get('rtl', function () {
// 		return view('rtl');
// 	})->name('rtl');

// 	Route::get('user-management', function () {
// 		return view('laravel-examples/user-management');
// 	})->name('user-management');

// 	Route::get('tables', function () {
// 		return view('tables');
// 	})->name('tables');

// 	Route::get('virtual-reality', function () {
// 		return view('virtual-reality');
// 	})->name('virtual-reality');

// 	Route::get('static-sign-in', function () {
// 		return view('static-sign-in');
// 	})->name('sign-in');

// 	Route::get('static-sign-up', function () {
// 		return view('static-sign-up');
// 	})->name('sign-up');

	Route::post('/logout', [SessionsController::class, 'destroy'])->name('logout');
// 	Route::get('/user-profile', [InfoUserController::class, 'create']);
// 	Route::post('/user-profile', [InfoUserController::class, 'store']);
// 	Route::get('/login', function () {
// 		return view('dashboard');
// 	})->name('sign-up');
// });

Route::group(['middleware' => 'guest'], function () {
	Route::get('/register', [RegisterController::class, 'create']);
	Route::post('/register', [RegisterController::class, 'store']);
	Route::get('/login', [SessionsController::class, 'create']);
	Route::post('/session', [SessionsController::class, 'store']);
	Route::get('/login/forgot-password', [ResetController::class, 'create']);
	Route::post('/forgot-password', [ResetController::class, 'sendEmail']);
	Route::get('/reset-password/{token}', [ResetController::class, 'resetPass'])->name('password.reset');
	Route::post('/reset-password', [ChangePasswordController::class, 'changePassword'])->name('password.update');
});

// Route::get('/login', function () {
// 	return view('session/admin-login');
// })->name('login');

Route::get('/', function () {
	return view('session/user-choose');
})->name('choose');

// Route::get('/tes-wa', [TestingController::class, 'index']);
// Route::get('/tes-wa-resha', [TestingController::class, 'kirimPesanKeResha']);
