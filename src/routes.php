<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
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

Route::prefix('lumki')->middleware(['web','auth:sanctum'])->group(function () {

    //Route::impersonate();

    Route::get('/', function(Request $request){
        return redirect(route("lumki.users.index"));
    })->name("lumki.index");

    Route::get('setup', function() {
        if( ! Schema::hasTable('roles') ){
            return view("lumki::nosetup");
        }
        /*
        $r1 = Role::firstOrCreate(["name" => "Superadmin"]);
        $r2 = Role::firstOrCreate(["name" => "Admin"]);
        $r3 = Role::firstOrCreate(["name" => "User"]);

        $p1 = Permission::firstOrCreate(['name' => 'manage users']);

        $r1->givePermissionTo('manage users');

        $user = User::first();
        $user->assignRole($r1);
        $user->assignRole($r2);
        $user->assignRole($r3);
        */
        //return view("lumki::nosetup");
    });

    Route::get('users', function(Request $request){
        return view("lumki::index", ["users" => User::paginate(8)]);
    })->name("lumki.users.index");


    Route::get('users/{user}', function(User $user){
        return view("lumki::edit", [
            "user" => $user,
            "roles" => Role::all()
        ]);
    })->name("lumki.user.roles.edit");


    Route::put('users/{user}', function(User $user){
        $user->syncRoles(request('roles'));
        return redirect(route("lumki.users.index"));
    })->name("lumki.user.roles.update");

    Route::get('impersonate', function(User $user){
        Auth::user()->impersonate($user);
        return redirect(route('dashboard'));
    })->name("lumki.impersonate");


    Route::get('file', function(){
        $r1 = Pruebas::insertLineAfter(
            app_path("Models/User.php"),
            "use Laravel\Jetstream\HasProfilePhoto;",
            "use Spatie\Permission\Traits\HasRoles;");

        $r2 = Pruebas::insertLineAfter(
            app_path("Models/User.php"),
            "use HasProfilePhoto;",
            "use HasRoles;");

        return [$r1, $r2];
    });

});


