<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DoctorSeeder extends Seeder
{
    use WithoutModelEvents;

    private array $doctors = [
        ["first_name" => "Martín", "last_name" => "Alvarado", "specialty" => "Pediatría", "id_card" => "1234567890", "email" => "martin.alvarado@example.com", "phone" => "0998745632"],
        ["first_name" => "Elena", "last_name" => "Cedeño", "specialty" => "Ginecología", "id_card" => "2345678901", "email" => "elena.cedeno@example.com", "phone" => "0987456321"],
        ["first_name" => "Rodrigo", "last_name" => "Quintero", "specialty" => "Medicina Ocupacional", "id_card" => "3456789012", "email" => "rodrigo.quintero@example.com", "phone" => "0978456322"],
        ["first_name" => "Patricia", "last_name" => "Mejía", "specialty" => "Audiología", "id_card" => "4567890123", "email" => "patricia.mejia@example.com", "phone" => "0968456323"],
        ["first_name" => "Hernán", "last_name" => "Villamar", "specialty" => "Cardiología", "id_card" => "5678901234", "email" => "hernan.villamar@example.com", "phone" => "0958456324"],
        ["first_name" => "Claudia", "last_name" => "Espinoza", "specialty" => "Dermatología", "id_card" => "6789012345", "email" => "claudia.espinoza@example.com", "phone" => "0948456325"],
        ["first_name" => "Felipe", "last_name" => "Zambrano", "specialty" => "Traumatología", "id_card" => "7890123456", "email" => "felipe.zambrano@example.com", "phone" => "0938456326"],
        ["first_name" => "Verónica", "last_name" => "Palacios", "specialty" => "Oftalmología", "id_card" => "8901234567", "email" => "veronica.palacios@example.com", "phone" => "0928456327"],
        ["first_name" => "Esteban", "last_name" => "Rosales", "specialty" => "Neurología", "id_card" => "9012345678", "email" => "esteban.rosales@example.com", "phone" => "0918456328"],
        ["first_name" => "Marisol", "last_name" => "Guerrero", "specialty" => "Oncología", "id_card" => "1122334455", "email" => "marisol.guerrero@example.com", "phone" => "0908456329"],
        ["first_name" => "Álvaro", "last_name" => "Peñafiel", "specialty" => "Medicina Interna", "id_card" => "2233445566", "email" => "alvaro.penafiel@example.com", "phone" => "0998456330"],
        ["first_name" => "Rosa", "last_name" => "Montoya", "specialty" => "Psiquiatría", "id_card" => "3344556677", "email" => "rosa.montoya@example.com", "phone" => "0988456331"],
        ["first_name" => "Mauricio", "last_name" => "Gálvez", "specialty" => "Urología", "id_card" => "4455667788", "email" => "mauricio.galvez@example.com", "phone" => "0978456332"],
        ["first_name" => "Carla", "last_name" => "Benítez", "specialty" => "Endocrinología", "id_card" => "5566778899", "email" => "carla.benitez@example.com", "phone" => "0968456333"],
        ["first_name" => "Ignacio", "last_name" => "Figueroa", "specialty" => "Neumología", "id_card" => "6677889900", "email" => "ignacio.figueroa@example.com", "phone" => "0958456334"],
        ["first_name" => "Lorena", "last_name" => "Carrillo", "specialty" => "Reumatología", "id_card" => "7788990011", "email" => "lorena.carrillo@example.com", "phone" => "0948456335"],
        ["first_name" => "Gustavo", "last_name" => "Mora", "specialty" => "Hematología", "id_card" => "8899001122", "email" => "gustavo.mora@example.com", "phone" => "0938456336"],
        ["first_name" => "Silvia", "last_name" => "Pazmiño", "specialty" => "Nefrología", "id_card" => "9900112233", "email" => "silvia.pazmino@example.com", "phone" => "0928456337"],
        ["first_name" => "Óscar", "last_name" => "Luna", "specialty" => "Geriatría", "id_card" => "1011121314", "email" => "oscar.luna@example.com", "phone" => "0918456338"],
        ["first_name" => "Daniela", "last_name" => "Cornejo", "specialty" => "Odontología", "id_card" => "1213141516", "email" => "daniela.cornejo@example.com", "phone" => "0908456339"],
        ["first_name" => "Cristian", "last_name" => "Bravo", "specialty" => "Cirugía General", "id_card" => "1314151617", "email" => "cristian.bravo@example.com", "phone" => "0998456340"],
        ["first_name" => "Tatiana", "last_name" => "Serrano", "specialty" => "Nutrición Clínica", "id_card" => "1415161718", "email" => "tatiana.serrano@example.com", "phone" => "0988456341"],
        ["first_name" => "Ramiro", "last_name" => "Pacheco", "specialty" => "Medicina Familiar", "id_card" => "1516171819", "email" => "ramiro.pacheco@example.com", "phone" => "0978456342"],
        ["first_name" => "Mónica", "last_name" => "Villacís", "specialty" => "Infectología", "id_card" => "1617181920", "email" => "monica.villacis@example.com", "phone" => "0968456343"],
        ["first_name" => "Fernando", "last_name" => "Cárdenas", "specialty" => "Anestesiología", "id_card" => "1718192021", "email" => "fernando.cardenas@example.com", "phone" => "0958456344"],
        ["first_name" => "Isabel", "last_name" => "Burgos", "specialty" => "Genética Médica", "id_card" => "1819202122", "email" => "isabel.burgos@example.com", "phone" => "0948456345"],
        ["first_name" => "Julián", "last_name" => "Maldonado", "specialty" => "Radiología", "id_card" => "1920212223", "email" => "julian.maldonado@example.com", "phone" => "0938456346"],
        ["first_name" => "Camila", "last_name" => "Arévalo", "specialty" => "Inmunología", "id_card" => "2021222324", "email" => "camila.arevalo@example.com", "phone" => "0928456347"],
        ["first_name" => "Mauricio", "last_name" => "Salcedo", "specialty" => "Cirugía Plástica", "id_card" => "2122232425", "email" => "mauricio.salcedo@example.com", "phone" => "0918456348"],
        ["first_name" => "Adriana", "last_name" => "Borja", "specialty" => "Medicina de Emergencias", "id_card" => "2223242526", "email" => "adriana.borja@example.com", "phone" => "0908456349"]
    ];


    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        foreach ($this->doctors as $doctor) {
            Doctor::updateOrCreate(
                ['id_card' => $doctor['id_card']],
                $doctor
            );
        }
    }
}
