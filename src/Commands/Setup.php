<?php

namespace Raultm\Pruebas\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Raultm\Pruebas\Facades\Pruebas;
use Symfony\Component\Process\Process;

/**
 * Setup Lumki package
 *
 * @author raultm
 **/
class Setup extends Command
{
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'lumki:setup';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Package Setup.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        // Publicar migraciones de Spatie/Laravel permissions
        $this->askStep(
            '¿Publicar migraciones de Spatie\Permission\PermissionServiceProvider?',
            function () {
                $this->call('vendor:publish', [
                    '--provider' => 'Spatie\Permission\PermissionServiceProvider'
                ]);
            }
        );
        // Publicar configuracion de Lab404/Impersonate permissions
        $this->askStep(
            '¿Publicar configuracion de Lab404\Impersonate\ImpersonateServiceProvider?',
            function () {
                $this->call('vendor:publish', [
                    '--provider' => 'Lab404\Impersonate\ImpersonateServiceProvider'
                ]);
            }
        );
        // Migrar
        $this->askStep(
            '¿Ejecutar migraciones ahora?',
            function () {
                $this->call('migrate');
            }
        );
        // Model User
        // Añadir Trait/Use Spatie\Permission\Traits\HasRoles after use Laravel\Jetstream\HasProfilePhoto;;
        $this->askStep(
            '¿Añadir Trait HasRole de Laravel Permission a Models/User?',
            function () {
                $this->info(
                    Pruebas::insertLineAfter(
                        app_path("Models/User.php"),
                        "use Laravel\Jetstream\HasProfilePhoto;",
                        "use Spatie\Permission\Traits\HasRoles;")
                );

                $this->info(
                    Pruebas::insertLineAfter(
                        app_path("Models/User.php"),
                        "use HasProfilePhoto;",
                        "use HasRoles;")
                );
            }
        );
        // Añadir Trait/Use Lab404\Impersonate\Models\Impersonate; after Spatie\Permission\Traits\HasRoles
        $this->askStep(
            '¿Añadir Trait Impersonate de Laravel Permission a Models/User?',
            function () {
                $this->info(
                    Pruebas::insertLineAfter(
                        app_path("Models/User.php"),
                        "use Spatie\Permission\Traits\HasRoles;",
                        "use Lab404\Impersonate\Models\Impersonate;")
                );

                $this->info(
                    Pruebas::insertLineAfter(
                        app_path("Models/User.php"),
                        "use HasRoles;",
                        "use Impersonate;")
                );
            }
        );

        // Añadir Rutas de Impersonate
        $this->askStep(
            '¿Añadir Rutas de Impersonate',
            function () {
                $this->info(
                    Pruebas::insertLineBefore(
                        base_path("routes/web.php"),
                        "Route::get('/', function () {",
                        "Route::impersonate();\n")
                );
            }
        );
        // Añadir directiva @lumki al menu del usuario
        $this->askStep(
            '¿Añadir Menú de acceso en el desplegable del usuario',
            function () {
                $this->info(
                    Pruebas::insertLineBefore(
                        resource_path('views/navigation-dropdown.blade.php'),
                        "@if (Laravel\Jetstream\Jetstream::hasApiFeatures())",
                        "\n@lumki\n")
                );
            }
        );



    }

    public function askStep($question, $yesCallback, $noCallback = null)
    {
        if( $this->confirm($question, "yes") ){
            $yesCallback();
        }else{
            if($noCallback === null){
                $this->info("Step Skipped.");
            }else{
                $noCallback();
            }
        }
    }
}
