<?php

namespace App\Console\Commands;

use Carbon\Carbon;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Proyect;
use App\Models\Worklog;

class CreateDummy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-dummy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('init');
        $names = [
            "juan",
            "sergio",
            "victor",
            "diego",
            "francisco",
            "cristian",
            "emerson",
            "francesca",
            "roberto",
            "matias",
            "tomas",
            "marcelo",
            "patricio",
            "romina",
            "paulo",
            "jesus",
            "jorge",
            "sebastian",
            "edward",
            "bernardita",
            "christopher",
            "benjamin",
            "raul",
            "fabian",
            "javier",
            "cristobal",
            "marcus",
            "piero"
        ];

        $lastnames = [
            "reyes",
            "silva",
            "peñaloza",
            "leiva",
            "yachan",
            "mujica",
            "puelles",
            "digiorgi",
            "caceres",
            "salinas",
            "bustos",
            "chalesdebeaulieu",
            "bastias",
            "gutierrez",
            "bobadilla",
            "canales",
            "jesus",
            "opazo",
            "avalos",
            "cabrera",
            "fuentes",
            "lobos",
            "herrera",
            "fuentealba",
            "arancibia",
            "gutierrez",
            "olivares",
            "raby",
            "ellisca",
            "ampuero"
        ];

        $actions = [
            "compilar",
            "recopilar",
            "investigar",
            "generar",
            "documentar",
            "soporte",
            "programar",
            "diseñar"
        ];

        $subjects = [
            "proyecto",
            "dependencias",
            "pluggins",
            "librerias externas",
            "diseños",
            "maquetas"
        ];

        $proyects = [
            Proyect::create(["name" => "camión minero", "logo" => "https://cdn-icons-png.flaticon.com/512/71/71452.png"]),
            Proyect::create(["name" => "grúa horquilla", "logo" => "https://cdn-icons-png.flaticon.com/256/1580/1580355.png"]),
            Proyect::create(["name" => "CEFOMIN", "logo" => "https://cefomin.cl/wp-content/uploads/2023/06/logotipo_cefomin-800.png"]),
            Proyect::create(["name" => "tour virtual", "logo" => "https://cdn-icons-png.flaticon.com/512/5136/5136877.png"])
        ];

        for ($p=0; $p < 3; $p++) { 
            $user = User::create([
                'email' => $names[rand(0, count($names)-1)].".".$lastnames[rand(0, count($lastnames)-1)]."@yoy.cl",
                'password' => "",
            ]);
            $this->info('create '.$user->email);

            $userproyect = $proyects[rand(0, count($proyects)-1)];

            for ($d=0; $d < 29; $d++) { 
                if(rand(0, 70) < 50){
                    $start = Carbon::now()->subDays(30-$d);
                    $start->hour = 9 + (rand(0, 5) - 3);
                    $start->minute = rand(0, 59);
                    $end = Carbon::now()->subDays(30-$d);
                    $end->hour = 18 + (rand(0, 7) - 4);
                    $end->minute = rand(0, 59);

                    $description = "";
                    for ($i=0; $i < rand(1, 4); $i++) { 
                        $description = $description . $actions[rand(0, count($actions)-1)] . " " . $subjects[rand(0, count($subjects)-1)] . "\n";
                    }

                    Worklog::create([
                        'start' => $start,
                        'end' => $end,
                        'fk_user' => $user->id,
                        'fk_proyect' => $userproyect->id,
                        'description' => $description,
                    ]);
                }
            }
        }
    }
}
