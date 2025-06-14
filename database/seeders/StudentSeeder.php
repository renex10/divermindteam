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
        // Verificar prerequisitos
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
            $this->command->error('Línea: ' . $e->getLine());
            throw $e;
        }
    }

    /**
     * Crea un estudiante individual con todas sus relaciones
     */
    private function createStudent(Course $course): void
    {
        // 1. Crear usuario
        $user = $this->createUser($course);

        // 2. Crear documento
        $document = $this->createUserDocument($user);

        // 3. Crear estudiante
        $student = $this->createStudentRecord($user, $document, $course);

        // 4. Relacionar estudiante con curso
        $this->linkStudentToCourse($student, $course);
    }

    /**
     * Crea un usuario para el estudiante
     */
    private function createUser(Course $course): User
    {
        $email = $this->generateUniqueEmail();
        $firstName = $this->faker->firstName();
        $lastName = $this->faker->lastName();
        $fullName = "{$firstName} {$lastName}";

        return User::create([
            'name' => $fullName,
            'email' => $email,
            'password' => bcrypt('password123'),
            'establishment_id' => $course->establishment_id,
            'email_verified_at' => null,
        ]);
    }

    /**
     * Crea el documento de identidad del usuario
     */
    private function createUserDocument(User $user): UserDocument
    {
        $rut = $this->generateUniqueRut();

        return UserDocument::create([
            'user_id' => $user->id,
            'rut' => $rut,
            'verified' => $this->faker->boolean(30), // 30% probabilidad de estar verificado
        ]);
    }

    /**
     * Crea el registro del estudiante
     */
    private function createStudentRecord(User $user, UserDocument $document, Course $course): Student
    {
        $nameParts = explode(' ', $user->name);
        $firstName = $nameParts[0];
        $lastName = isset($nameParts[1]) ? $nameParts[1] : $this->faker->lastName();

        $needType = $this->faker->randomElement(['permanent', 'temporary']);
        $diagnosis = $this->generateDiagnosis($needType);

        return Student::create([
            'user_id' => $user->id,
            'document_id' => $document->id,
            'course_id' => $course->id,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'birth_date' => $this->faker->dateTimeBetween('-18 years', '-6 years')->format('Y-m-d'),
            'need_type' => $needType,
            'diagnosis' => $diagnosis,
            'priority' => $this->determinePriority($diagnosis),
            'active' => $this->faker->boolean(95), // 95% activos
        ]);
    }

    /**
     * Relaciona el estudiante con el curso en la tabla pivot
     */
    private function linkStudentToCourse(Student $student, Course $course): void
    {
        // Verificar que no exista ya la relación
        $exists = DB::table('course_students')
            ->where('student_id', $student->id)
            ->where('course_id', $course->id)
            ->exists();

        if (!$exists) {
            DB::table('course_students')->insert([
                'student_id' => $student->id,
                'course_id' => $course->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Genera un email único
     */
    private function generateUniqueEmail(): string
    {
        $maxAttempts = 10;
        $attempts = 0;

        do {
            $email = $this->faker->unique()->safeEmail();
            $attempts++;
            
            if ($attempts > $maxAttempts) {
                // Generar email más único si hay muchos intentos
                $email = 'student_' . uniqid() . '@' . $this->faker->domainName();
                break;
            }
        } while (in_array($email, $this->usedEmails) || User::where('email', $email)->exists());

        $this->usedEmails[] = $email;
        return $email;
    }

    /**
     * Genera un RUT único
     */
    private function generateUniqueRut(): string
    {
        $maxAttempts = 10;
        $attempts = 0;

        do {
            $rut = $this->generateChileanRut();
            $attempts++;
            
            if ($attempts > $maxAttempts) {
                // Generar RUT más único si hay muchos intentos
                $number = rand(10000000, 24999999);
                $rut = $number . '-' . $this->calculateVerifierDigit($number);
                break;
            }
        } while (in_array($rut, $this->usedRuts) || UserDocument::where('rut', $rut)->exists());

        $this->usedRuts[] = $rut;
        return $rut;
    }

    /**
     * Genera un RUT chileno válido
     */
    private function generateChileanRut(): string
    {
        $number = rand(5000000, 24999999);
        $dv = $this->calculateVerifierDigit($number);
        return $number . '-' . $dv;
    }

    /**
     * Calcula el dígito verificador de un RUT chileno
     */
    private function calculateVerifierDigit(int $number): string
    {
        $s = 1;
        for ($m = 0; $number != 0; $number = (int)($number / 10)) {
            $s = ($s + $number % 10 * (9 - $m++ % 6)) % 11;
        }
        return $s ? chr($s + 47) : 'K';
    }

    /**
     * Genera diagnóstico según tipo de necesidad
     */
    private function generateDiagnosis(string $needType): string
    {
        $permanentDiagnoses = [
            'Discapacidad intelectual leve',
            'Discapacidad intelectual moderada',
            'Trastorno del espectro autista nivel 1',
            'Trastorno del espectro autista nivel 2',
            'Parálisis cerebral',
            'Síndrome de Down',
            'Discapacidad visual',
            'Discapacidad auditiva'
        ];

        $temporaryDiagnoses = [
            'Trastorno específico del aprendizaje en lectura',
            'Trastorno específico del aprendizaje en matemáticas',
            'Déficit atencional con hiperactividad',
            'Déficit atencional sin hiperactividad',
            'Disfasia expresiva',
            'Disfasia mixta',
            'Trastorno del lenguaje',
            'Dificultades de aprendizaje'
        ];

        $diagnoses = $needType === 'permanent' ? $permanentDiagnoses : $temporaryDiagnoses;
        return $diagnoses[array_rand($diagnoses)];
    }

    /**
     * Determina prioridad según diagnóstico
     */
    private function determinePriority(string $diagnosis): int
    {
        // Prioridad 1 (Máxima): Discapacidades severas
        if (str_contains($diagnosis, 'moderada') || 
            str_contains($diagnosis, 'nivel 2') || 
            str_contains($diagnosis, 'Parálisis cerebral') ||
            str_contains($diagnosis, 'Síndrome de Down')) {
            return 1;
        }

        // Prioridad 2 (Media): Trastornos que requieren apoyo constante
        if (str_contains($diagnosis, 'Déficit atencional') || 
            str_contains($diagnosis, 'Disfasia') ||
            str_contains($diagnosis, 'nivel 1') ||
            str_contains($diagnosis, 'visual') ||
            str_contains($diagnosis, 'auditiva')) {
            return 2;
        }

        // Prioridad 3 (Básica): Dificultades de aprendizaje
        return 3;
    }
}