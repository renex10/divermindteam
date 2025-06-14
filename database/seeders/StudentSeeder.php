<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\User;
use App\Models\UserDocument;
use App\Models\Student;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class StudentSeeder extends Seeder
{
    private $faker;
    private $usedRuts = [];
    private $usedEmails = [];

    public function __construct()
    {
        $this->faker = Faker::create('es_ES');
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Course::count() == 0) {
            $this->command->error('Primero ejecute CourseSeeder!');
            return;
        }

        $this->command->info('Iniciando creación de estudiantes...');

        DB::beginTransaction();

        try {
            $courses = Course::with('establishment')->get();
            $totalStudents = 0;

            foreach ($courses as $course) {
                $studentsPerCourse = rand(5, 10);
                $this->command->info("Creando {$studentsPerCourse} estudiantes para curso: {$course->name}");

                for ($i = 0; $i < $studentsPerCourse; $i++) {
                    $this->createStudent($course);
                    $totalStudents++;
                }
            }

            DB::commit();
            $this->command->info("✅ {$totalStudents} estudiantes creados exitosamente en " . count($courses) . " cursos.");

        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('❌ Error: ' . $e->getMessage());
            throw $e;
        }
    }

    private function createStudent(Course $course): void
    {
        $user = $this->createUser($course);
        $document = $this->createUserDocument($user);
        $this->createStudentRecord($user, $document, $course);
    }

    private function createUser(Course $course): User
    {
        $email = $this->generateUniqueEmail();
        $fullName = $this->faker->firstName() . ' ' . $this->faker->lastName();

        return User::create([
            'name' => $fullName,
            'email' => $email,
            'password' => bcrypt('password123'),
            'establishment_id' => $course->establishment_id,
            'email_verified_at' => null,
        ]);
    }

    private function createUserDocument(User $user): UserDocument
    {
        $rut = $this->generateUniqueRut();

        return UserDocument::create([
            'user_id' => $user->id,
            'rut' => $rut,
            'verified' => $this->faker->boolean(30),
        ]);
    }

    private function createStudentRecord(User $user, UserDocument $document, Course $course): Student
    {
        $needType = $this->faker->randomElement(['permanent', 'temporary']);
        $diagnosis = $this->generateDiagnosis($needType);

        return Student::create([
            'user_id' => $user->id,
            'document_id' => $document->id,
            'course_id' => $course->id,
            'birth_date' => $this->faker->dateTimeBetween('-18 years', '-6 years')->format('Y-m-d'),
            'need_type' => $needType,
            'diagnosis' => $diagnosis,
            'priority' => $this->determinePriority($diagnosis),
            'active' => $this->faker->boolean(95),
        ]);
    }

    private function generateUniqueEmail(): string
    {
        $maxAttempts = 10;
        $attempts = 0;

        do {
            $email = $this->faker->unique()->safeEmail();
            $attempts++;
            
            if ($attempts > $maxAttempts) {
                $email = 'student_' . uniqid() . '@' . $this->faker->domainName();
                break;
            }
        } while (in_array($email, $this->usedEmails) || User::where('email', $email)->exists());

        $this->usedEmails[] = $email;
        return $email;
    }

    private function generateUniqueRut(): string
    {
        $maxAttempts = 10;
        $attempts = 0;

        do {
            $rut = $this->generateChileanRut();
            $attempts++;
            
            if ($attempts > $maxAttempts) {
                $number = rand(10000000, 24999999);
                $rut = $number . '-' . $this->calculateVerifierDigit($number);
                break;
            }
        } while (in_array($rut, $this->usedRuts) || UserDocument::where('rut', $rut)->exists());

        $this->usedRuts[] = $rut;
        return $rut;
    }

    private function generateChileanRut(): string
    {
        $number = rand(5000000, 24999999);
        $dv = $this->calculateVerifierDigit($number);
        return $number . '-' . $dv;
    }

    private function calculateVerifierDigit(int $number): string
    {
        $s = 1;
        for ($m = 0; $number != 0; $number = (int)($number / 10)) {
            $s = ($s + $number % 10 * (9 - $m++ % 6)) % 11;
        }
        return $s ? chr($s + 47) : 'K';
    }

    private function generateDiagnosis(string $needType): string
    {
        $permanent = [
            'Discapacidad intelectual moderada',
            'Trastorno del espectro autista nivel 2',
            'Parálisis cerebral'
        ];

        $temporary = [
            'Trastorno específico del aprendizaje',
            'Déficit atencional',
            'Disfasia'
        ];

        return $needType === 'permanent' 
            ? $permanent[array_rand($permanent)]
            : $temporary[array_rand($temporary)];
    }

    private function determinePriority(string $diagnosis): int
    {
        if (str_contains($diagnosis, 'moderada') || str_contains($diagnosis, 'nivel 2')) {
            return 1;
        } elseif (str_contains($diagnosis, 'Déficit') || str_contains($diagnosis, 'Disfasia')) {
            return 2;
        }
        return 3;
    }
}