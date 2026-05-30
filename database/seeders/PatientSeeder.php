<?php

namespace Database\Seeders;

use App\Models\Patient;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PatientSeeder extends Seeder
{
    use WithoutModelEvents;

    private array $patients = [
        ["first_name" => "Juan", "last_name" => "Pérez", "nationality" => "Ecuatoriana", "gender" => "male", "birth_date" => "1985-03-12", "id_card" => "0958745632", "email" => "juan.perez@example.com", "phone" => "0998745632"],
        ["first_name" => "María", "last_name" => "Gómez", "nationality" => "Ecuatoriana", "gender" => "female", "birth_date" => "1990-07-25", "id_card" => "0958745633", "email" => "maria.gomez@example.com", "phone" => "0987456321"],
        ["first_name" => "Carlos", "last_name" => "Ramírez", "nationality" => "Peruana", "gender" => "male", "birth_date" => "1978-11-02", "id_card" => "0958745634", "email" => "carlos.ramirez@example.com", "phone" => "0978456322"],
        ["first_name" => "Ana", "last_name" => "Torres", "nationality" => "Colombiana", "gender" => "female", "birth_date" => "1995-01-18", "id_card" => "0958745635", "email" => "ana.torres@example.com", "phone" => "0968456323"],
        ["first_name" => "Luis", "last_name" => "Martínez", "nationality" => "Ecuatoriana", "gender" => "male", "birth_date" => "1988-09-09", "id_card" => "0958745636", "email" => "luis.martinez@example.com", "phone" => "0958456324"],
        ["first_name" => "Sofía", "last_name" => "Vargas", "nationality" => "Chilena", "gender" => "female", "birth_date" => "1992-04-22", "id_card" => "0958745637", "email" => "sofia.vargas@example.com", "phone" => "0948456325"],
        ["first_name" => "Pedro", "last_name" => "Jiménez", "nationality" => "Mexicana", "gender" => "male", "birth_date" => "1980-12-30", "id_card" => "0958745638", "email" => "pedro.jimenez@example.com", "phone" => "0938456326"],
        ["first_name" => "Camila", "last_name" => "Rojas", "nationality" => "Ecuatoriana", "gender" => "female", "birth_date" => "1998-06-15", "id_card" => "0958745639", "email" => "camila.rojas@example.com", "phone" => "0928456327"],
        ["first_name" => "Andrés", "last_name" => "Salazar", "nationality" => "Colombiana", "gender" => "male", "birth_date" => "1983-02-05", "id_card" => "0958745640", "email" => "andres.salazar@example.com", "phone" => "0918456328"],
        ["first_name" => "Valentina", "last_name" => "Morales", "nationality" => "Peruana", "gender" => "female", "birth_date" => "1996-10-10", "id_card" => "0958745641", "email" => "valentina.morales@example.com", "phone" => "0908456329"],
        ["first_name" => "Jorge", "last_name" => "Castillo", "nationality" => "Ecuatoriana", "gender" => "male", "birth_date" => "1981-08-21", "id_card" => "0958745642", "email" => "jorge.castillo@example.com", "phone" => "0998456330"],
        ["first_name" => "Fernanda", "last_name" => "López", "nationality" => "Argentina", "gender" => "female", "birth_date" => "1993-03-03", "id_card" => "0958745643", "email" => "fernanda.lopez@example.com", "phone" => "0988456331"],
        ["first_name" => "Ricardo", "last_name" => "Navarro", "nationality" => "Ecuatoriana", "gender" => "male", "birth_date" => "1979-05-27", "id_card" => "0958745644", "email" => "ricardo.navarro@example.com", "phone" => "0978456332"],
        ["first_name" => "Daniela", "last_name" => "Suárez", "nationality" => "Venezolana", "gender" => "female", "birth_date" => "1997-09-14", "id_card" => "0958745645", "email" => "daniela.suarez@example.com", "phone" => "0968456333"],
        ["first_name" => "Miguel", "last_name" => "Herrera", "nationality" => "Ecuatoriana", "gender" => "male", "birth_date" => "1984-11-19", "id_card" => "0958745646", "email" => "miguel.herrera@example.com", "phone" => "0958456334"],
        ["first_name" => "Paola", "last_name" => "Cruz", "nationality" => "Colombiana", "gender" => "female", "birth_date" => "1991-07-07", "id_card" => "0958745647", "email" => "paola.cruz@example.com", "phone" => "0948456335"],
        ["first_name" => "Diego", "last_name" => "Flores", "nationality" => "Ecuatoriana", "gender" => "male", "birth_date" => "1986-01-25", "id_card" => "0958745648", "email" => "diego.flores@example.com", "phone" => "0938456336"],
        ["first_name" => "Laura", "last_name" => "Mendoza", "nationality" => "Peruana", "gender" => "female", "birth_date" => "1994-02-28", "id_card" => "0958745649", "email" => "laura.mendoza@example.com", "phone" => "0928456337"],
        ["first_name" => "José", "last_name" => "Ortiz", "nationality" => "Ecuatoriana", "gender" => "male", "birth_date" => "1982-06-11", "id_card" => "0958745650", "email" => "jose.ortiz@example.com", "phone" => "0918456338"],
        ["first_name" => "Carolina", "last_name" => "Paredes", "nationality" => "Chilena", "gender" => "female", "birth_date" => "1999-12-09", "id_card" => "0958745651", "email" => "carolina.paredes@example.com", "phone" => "0908456339"],
        ["first_name" => "Sebastián", "last_name" => "Reyes", "nationality" => "Ecuatoriana", "gender" => "male", "birth_date" => "1987-04-04", "id_card" => "0958745652", "email" => "sebastian.reyes@example.com", "phone" => "0998456340"],
        ["first_name" => "Gabriela", "last_name" => "Silva", "nationality" => "Venezolana", "gender" => "female", "birth_date" => "1995-08-16", "id_card" => "0958745653", "email" => "gabriela.silva@example.com", "phone" => "0988456341"],
        ["first_name" => "Héctor", "last_name" => "Vega", "nationality" => "Ecuatoriana", "gender" => "male", "birth_date" => "1989-09-23", "id_card" => "0958745654", "email" => "hector.vega@example.com", "phone" => "0978456342"],
        ["first_name" => "Natalia", "last_name" => "Campos", "nationality" => "Colombiana", "gender" => "female", "birth_date" => "1992-11-30", "id_card" => "0958745655", "email" => "natalia.campos@example.com", "phone" => "0968456343"],
        ["first_name" => "Francisco", "last_name" => "Aguilar", "nationality" => "Ecuatoriana", "gender" => "male", "birth_date" => "1980-01-10", "id_card" => "0958745656", "email" => "francisco.aguilar@example.com", "phone" => "0958456344"],
        ["first_name" => "Isabella", "last_name" => "Rivas", "nationality" => "Peruana", "gender" => "female", "birth_date" => "1997-05-05", "id_card" => "0958745657", "email" => "isabella.rivas@example.com", "phone" => "0948456345"],
        ["first_name" => "Tomás", "last_name" => "García", "nationality" => "Ecuatoriana", "gender" => "male", "birth_date" => "1983-03-18", "id_card" => "0958745658", "email" => "tomas.garcia@example.com", "phone" => "0938456346"],
        ["first_name" => "Lucía", "last_name" => "Fernández", "nationality" => "Argentina", "gender" => "female", "birth_date" => "1990-09-01", "id_card" => "0958745659", "email" => "lucia.fernandez@example.com", "phone" => "0928456347"],
        ["first_name" => "Manuel", "last_name" => "Santos", "nationality" => "Ecuatoriana", "gender" => "male", "birth_date" => "1985-07-29", "id_card" => "0958745660", "email" => "manuel.santos@example.com", "phone" => "0918456348"],
        ["first_name" => "Andrea", "last_name" => "Delgado", "nationality" => "Colombiana", "gender" => "female", "birth_date" => "1998-02-12", "id_card" => "0958745661", "email" => "andrea.delgado@example.com", "phone" => "0908456349"]
    ];


    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        foreach ($this->patients as $patient) {
            Patient::updateOrCreate(
                ['id_card' => $patient['id_card']],
                $patient
            );
        }
    }
}
