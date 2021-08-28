<?php

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

Route::get('/', [App\Http\Controllers\WelcomeController::class, 'index'])->name('welcome');

Auth::routes();

Route::prefix('hw/home')->group(function () {
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('index');
    Route::get('/questionnaire/series', [App\Http\Controllers\SeriesController::class, 'series'])->name('series');
    Route::post('/questionnaire/series', [App\Http\Controllers\SeriesController::class, 'create'])->name('create');

    Route::get('/series/sections/{seriesId}', [App\Http\Controllers\SectionController::class, 'sections'])->name('sections');
    Route::post('/series/sections', [App\Http\Controllers\SectionController::class, 'create'])->name('create_sec');

    Route::get('/series/questions/{sectionId}', [App\Http\Controllers\QuestionController::class, 'questions'])->name('questions');
    Route::post('/series/questions', [App\Http\Controllers\QuestionController::class, 'create'])->name('create_q');

    Route::get('/manage/question/{questionId}', [App\Http\Controllers\QuestionController::class, 'manage_q'])->name('manage_q');
    Route::post('/manage/edit/question/save', [App\Http\Controllers\QuestionController::class, 'save_q'])->name('save_q');

    Route::get('/customer/reponses', [App\Http\Controllers\HomeController::class, 'responses'])->name('responses');
    Route::get('/customer/reponses/{cid}/attempt/{attempt}', [App\Http\Controllers\HomeController::class, 'c_response'])->name('c_response');

    /** special orders */
    Route::get('/special/orders', [App\Http\Controllers\OrderController::class, 'spec_orders'])->name('spec_orders');
    Route::post('/special/order/new', [App\Http\Controllers\OrderController::class, 'new_order'])->name('new_order');
    /** profile */
    Route::get('/admin/account', [App\Http\Controllers\HomeController::class, 'adm_account'])->name('adm_account');
    Route::post('/change/pwd', [App\Http\Controllers\HomeController::class, 'change_pwd'])->name('change_pwd');
    /** pricing */
    Route::get('pricing', [App\Http\Controllers\HomeController::class, 'adm_pricing'])->name('adm_pricing');
    Route::post('pricing/edit', [App\Http\Controllers\HomeController::class, 'adm_price_update'])->name('adm_price_update');
    /** NGOs */
    Route::get('ngos', [App\Http\Controllers\HomeController::class, 'adm_ngo'])->name('adm_ngo');
    Route::get('ngos/view/{id}', [App\Http\Controllers\HomeController::class, 'adm_view_ngo'])->name('adm_view_ngo');
    Route::post('ngos/edit', [App\Http\Controllers\HomeController::class, 'adm_ngo_update'])->name('adm_ngo_update');

    // Route::get('/visualizations', [App\Http\Controllers\HomeController::class, 'graphs'])->name('graphs');

    Route::get('/exit', [App\Http\Controllers\HomeController::class, 'leave'])->name('leave');
});
/** corprates */
Route::prefix('corprates')->group(function () {
    Route::get('/sign-up', [App\Http\Controllers\CorporateController::class, 'signup'])->name('signup');
    Route::post('/sign-up/add', [App\Http\Controllers\CorporateController::class, 'new_corporate'])->name('new_corporate');
});
Route::prefix('home/corprates')->group(function () {
    Route::post('/order/add', [App\Http\Controllers\CorporateController::class, 'new_c_order'])->name('new_c_order');
    Route::get('/orders/distribute/{order}', [App\Http\Controllers\CorporateController::class, 'distribute'])->name('distribute');
    Route::post('/orders/distribute/it/add', [App\Http\Controllers\CorporateController::class, 'distribute_new'])->name('distribute_new');
});

Route::prefix('guest/home')->group(function () {
    Route::post('/init/new/customer', [App\Http\Controllers\ResponseController::class, 'init_customer'])->name('init_customer');
    Route::post('/init/verify/email', [App\Http\Controllers\ResponseController::class, 'verifyEmail'])->name('verifyEmail');
    Route::post('/finish/create/customer', [App\Http\Controllers\ResponseController::class, 'create_customer'])->name('create_customer');
    Route::get('/test/graph', [App\Http\Controllers\ResponseController::class, 'test_graph'])->name('test_graph');
});

/** progress */
Route::prefix('showing/hardwires/')->group(function () {
    /** preview */
    Route::get('/preview/{sectionId}', [App\Http\Controllers\SectionController::class, 'section_prev'])->name('section_prev');

    Route::get('/pay/{pid}/rtf/{hash}', [App\Http\Controllers\PaymentController::class, 'show_iframe'])->name('show_iframe');

    Route::get('/assessment/{no}/sections/{hash}/attempt/{attempt}', [App\Http\Controllers\ResponseController::class, 'show_section'])->name('show_section');
    Route::post('/assessment/save/section', [App\Http\Controllers\ResponseController::class, 'save_section'])->name('save_section');
});

/** Stream controller */
Route::prefix('stream/')->group(function () {
    /** preview */
    Route::get('/file/{file}', [App\Http\Controllers\HomeController::class, 'stream'])->name('stream');
});

/** callbacks */
Route::prefix('streams/callbacks/')->group(function () {
    Route::any('/to/next', [App\Http\Controllers\PaymentController::class, 'tonext'])->name('tonext');
    Route::any('/thanks', [App\Http\Controllers\PaymentController::class, 'thank_you'])->name('thank_you');

    Route::any('/cancelled', [App\Http\Controllers\PaymentController::class, 'p_cancel'])->name('p_cancel');

    Route::any('/c/thanks', [App\Http\Controllers\PaymentController::class, 'c_thank_you'])->name('c_thank_you');
    Route::any('/callback', [App\Http\Controllers\PaymentController::class, 'callback'])->name('callback');
});