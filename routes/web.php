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

Route::get('/', function () {

    return view('welcome');

});


require __DIR__.'/auth.php';


Route::middleware(['auth'])->group( function() {

    Route::get('/dashboard', [App\Http\Controllers\User\HomeController::class, 'index'])->name('user.dashboard');

    Route::get('/profile', [App\Http\Controllers\User\AccountController::class, 'index'])->name('user.profile');

    Route::post('/profile/account/update', [App\Http\Controllers\User\AccountController::class, 'update'])->name('user.account.update');

    Route::post('/account/password/reset', [App\Http\Controllers\User\AccountController::class, 'passwordReset'])->name('user.account.password.reset');

    // wallet controller

    Route::get('/wallet', [App\Http\Controllers\User\WalletController::class, 'index'])->name('user.wallet');

    Route::post('/wallet/deposit', [App\Http\Controllers\User\WalletController::class, 'deposit'])->name('user.wallet.deposit');

    Route::get('/wallet/deposit/verification', [App\Http\Controllers\User\WalletController::class, 'depositVerification'])->name('user.wallet.deposit.verification');

    Route::post('/wallet/withdrawal/bank/verification', [App\Http\Controllers\User\WalletController::class, 'bankVerification']);

    Route::post('/wallet/process/withdrawal', [App\Http\Controllers\User\WalletController::class, 'processWithdrawal']);

    // Transfers Method

    Route::get('/payonce/method/bank', [App\Http\Controllers\PayOnce\BankController::class, 'index'])->name('payonce.method.bank');

    Route::post('/payonce/method/bank/verify', [App\Http\Controllers\PayOnce\BankController::class, 'verify'])->name('payonce.method.bank.verify');

    Route::post('/payonce/method/bank/start-transaction', [App\Http\Controllers\PayOnce\BankController::class, 'startTransaction'])->name('payonce.method.bank.start-transaction');

    Route::get('/payonce/payment/verification', [App\Http\Controllers\PayOnce\BankController::class, 'paymentVerification'])->name('payonce.payment.verification');

    Route::get('/payonce/fetch/transfer', [App\Http\Controllers\PayOnce\BankController::class, 'fetchTransfer']);

    Route::post('/payonce/wallet/transfer', [App\Http\Controllers\PayOnce\WalletTransferController::class, 'store']);

    Route::get('/payonce/wallet/transfer/start-transaction', [App\Http\Controllers\PayOnce\WalletTransferController::class, 'startTransaction']);

    Route::get('/payonce/wallet/transfer/verify/transaction', [App\Http\Controllers\PayOnce\WalletTransferController::class, 'verifyTransaction'])->name('payonce.wallet.transfer.verify');



    //Transactions

    Route::get('/bank/transfer/transactions', [App\Http\Controllers\User\TransactionController::class, 'index'])->name('user.transactions');

    Route::get('/transactions/{transaction}/delete', [App\Http\Controllers\User\TransactionController::class, 'delete'])->name('user.transactions.delete');

    Route::get('/transactions/delete', [App\Http\Controllers\User\TransactionController::class, 'bulkDelete'])->name('user.transactions.bulk.delete');

    Route::get('/wallet/transfer/transactions', [App\Http\Controllers\PayOnce\WalletTransferController::class, 'index'])->name('user.wallet.transfer.transactions');

    Route::get('/wallet/transactions/{transaction}/delete', [App\Http\Controllers\PayOnce\WalletTransferController::class, 'delete']);

    Route::get('/wallet/transactions/delete', [App\Http\Controllers\PayOnce\WalletTransferController::class, 'bulkDelete']);

    

    // App preference settings

    Route::post('user/save/app/usage/setting', [App\Http\Controllers\User\AppUsageController::class, 'store'])->name('user.save.app-usage.setting');

    


    

    //Employees 

    Route::get('/employees', [App\Http\Controllers\User\EmployeeController::class, 'index'])->name('user.employees');

    Route::get('/employees/create', [App\Http\Controllers\User\EmployeeController::class, 'create'])->name('user.employees.create');

    Route::post('/employees', [App\Http\Controllers\User\EmployeeController::class, 'store'])->name('user.employees.store');

    Route::post('/employees/create/via/csv', [App\Http\Controllers\User\EmployeeController::class, 'uploadCSV']);

    Route::get('/employees/{employee}/edit', [App\Http\Controllers\User\EmployeeController::class, 'show'])->name('user.employees.show');

    Route::post('/employees/update', [App\Http\Controllers\User\EmployeeController::class, 'update'])->name('user.employees.update');

    Route::get('/employees/{employee}/delete', [App\Http\Controllers\User\EmployeeController::class, 'delete'])->name('user.employees.delete');

    Route::get('/employees/delete', [App\Http\Controllers\User\EmployeeController::class, 'bulkDelete'])->name('user.employees.bulk.delete');

    Route::post('/employees/payonce', [App\Http\Controllers\User\EmployeeController::class, 'payOnce'])->name('user.employees.payonce');

    Route::get('/employees/payonce/verification', [App\Http\Controllers\User\EmployeeController::class, 'processBulkTransfer'])->name('user.employees.payonce.verification');


    

});


Route::post('/payonce/webhook/event', [App\Http\Controllers\PayOnce\BankController::class, 'listenToEventWebhook']);

Route::get('/process-payroll', [App\Http\Controllers\PayOnce\PayRollController::class, 'startProcessing']);
