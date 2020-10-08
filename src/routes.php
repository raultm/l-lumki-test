<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Raultm\Pruebas\Facades\Pruebas;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

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

Route::prefix('lumki')->middleware(['web', 'verified'])->group(function () {

    Route::get('setup', function() {
        $r1 = Role::firstOrCreate(["name" => "Superadmin"]);
        $r2 = Role::firstOrCreate(["name" => "Admin"]);
        $r3 = Role::firstOrCreate(["name" => "User"]);

        $p1 = Permission::firstOrCreate(['name' => 'manage users']);

        $r1->givePermissionTo('manage users');

        $user = User::first();
        $user->assignRole($r1);
        $user->assignRole($r2);
        $user->assignRole($r3);
    });

    Route::get('/', function(Request $request){
        return redirect(route("lumki.users.index"));
    });

    Route::get('users', function(Request $request){
        return view("management.index", ["users" => User::all()]);
    })->name("lumki.users.index");

    Route::get('users/{user}', function(User $user){

        return view("management.edit", [
            "user" => $user,
            "roles" => Role::all()
        ]);
    })->name("lumki.user.roles.edit");

    Route::put('users/{user}', function(User $user){
        //dd(request()->all());
        $user->syncRoles(request('roles'));
        return redirect(route("lumki.users.index"));
    })->name("lumki.user.roles.update");

});


