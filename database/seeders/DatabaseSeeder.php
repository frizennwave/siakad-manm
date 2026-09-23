<?php

namespace Database\Seeders;

use App\Models\AbsenceLetter;
use App\Models\AcademicYear;
use App\Models\Attendance;
use App\Models\ClassRoom;
use App\Models\Grade;
use App\Models\LessonSession;
use App\Models\Major;
use App\Models\Schedule;
use App\Models\SchoolCalendar;
use App\Models\Student;
use App\Models\StudentEnrollment;
use App\Models\StudentQR;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\TeacherAttendance;
use App\Models\TeacherQR;
use App\Models\TeachingAssignment;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $roles = ['admin', 'teacher', 'student'];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }

        /*
        |--------------------------------------------------------------------------
        | 1. USERS
        |--------------------------------------------------------------------------
        */

        $admin = User::factory()
            ->admin()
            ->create([
                'name' => 'Administrator',
                'email' => 'admin@manmuhammad.sch.id',
            ]);

        /*
        |--------------------------------------------------------------------------
        | 2. ACADEMIC YEAR
        |--------------------------------------------------------------------------
        */

        $academicYear = AcademicYear::factory()
            ->active()
            ->create([
                'year' => '2026/2027',
            ]);

        /*
        |--------------------------------------------------------------------------
        | 3. MAJORS
        |--------------------------------------------------------------------------
        */

        $ipa = Major::create([
            'code' => 'IPA',
            'name' => 'Ilmu Pengetahuan Alam',
            'status' => true,
        ]);

        $ips = Major::create([
            'code' => 'IPS',
            'name' => 'Ilmu Pengetahuan Sosial',
            'status' => true,
        ]);

        /*
        |--------------------------------------------------------------------------
        | 4. SUBJECTS
        |--------------------------------------------------------------------------
        */

        $matematika = Subject::create([
            'code' => 'MTK',
            'name' => 'Matematika',
            'status' => true,
        ]);

        $bahasaIndonesia = Subject::create([
            'code' => 'BIN',
            'name' => 'Bahasa Indonesia',
            'status' => true,
        ]);

        $fisika = Subject::create([
            'code' => 'FIS',
            'name' => 'Fisika',
            'status' => true,
        ]);

        $sejarah = Subject::create([
            'code' => 'SEJ',
            'name' => 'Sejarah',
            'status' => true,
        ]);

        /*
        |--------------------------------------------------------------------------
        | 5. TEACHERS / TENDIK
        |--------------------------------------------------------------------------
        */

        $guruA = Teacher::factory()->create([
            'user_id' => User::factory()
                ->teacher()
                ->create([
                    'name' => 'Guru A',
                    'email' => 'guru.a@manmuhammad.sch.id',
                ])
                ->id,

            'nip' => '198001012006041001',
            'position' => 'Guru Matematika',
        ]);

        $guruB = Teacher::factory()->create([
            'user_id' => User::factory()
                ->teacher()
                ->create([
                    'name' => 'Guru B',
                    'email' => 'guru.b@manmuhammad.sch.id',
                ])
                ->id,

            'nip' => '198101012007041002',
            'position' => 'Guru Matematika',
        ]);

        $guruC = Teacher::factory()->create([
            'user_id' => User::factory()
                ->teacher()
                ->create([
                    'name' => 'Guru C',
                    'email' => 'guru.c@manmuhammad.sch.id',
                ])
                ->id,

            'nip' => '198201012008041003',
            'position' => 'Guru Fisika',
        ]);

        $tendik = Teacher::factory()
            ->tendik()
            ->create([
                'user_id' => User::factory()
                    ->teacher()
                    ->create([
                        'name' => 'Tenaga Kependidikan',
                        'email' => 'tendik@manmuhammad.sch.id',
                    ])
                    ->id,

                'nip' => '198301012009041004',
            ]);

        /*
        |--------------------------------------------------------------------------
        | 6. QR GURU / TENDIK
        |--------------------------------------------------------------------------
        */

        TeacherQR::factory()->create([
            'teacher_id' => $guruA->id,
        ]);

        TeacherQR::factory()->create([
            'teacher_id' => $guruB->id,
        ]);

        TeacherQR::factory()->create([
            'teacher_id' => $guruC->id,
        ]);

        TeacherQR::factory()->create([
            'teacher_id' => $tendik->id,
        ]);

        /*
        |--------------------------------------------------------------------------
        | 7. CLASS ROOMS
        |--------------------------------------------------------------------------
        */

        $kelasXA = ClassRoom::create([
            'academic_year_id' => $academicYear->id,
            'major_id' => null,
            'grade_level' => 10,
            'name' => 'X-A',
        ]);

        $kelasXIBP = ClassRoom::create([
            'academic_year_id' => $academicYear->id,
            'major_id' => $ipa->id,
            'grade_level' => 11,
            'name' => 'XI IPA-1',
        ]);

        $kelasXIIPS = ClassRoom::create([
            'academic_year_id' => $academicYear->id,
            'major_id' => $ips->id,
            'grade_level' => 11,
            'name' => 'XI IPS-1',
        ]);

        /*
        |--------------------------------------------------------------------------
        | 8. STUDENTS
        |--------------------------------------------------------------------------
        */

        $budi = Student::factory()->create([
            'user_id' => User::factory()
                ->student()
                ->create([
                    'name' => 'Budi',
                    'email' => 'budi@manmuhammad.sch.id',
                ])
                ->id,

            'nis' => '260001',
        ]);

        $siti = Student::factory()->create([
            'user_id' => User::factory()
                ->student()
                ->create([
                    'name' => 'Siti',
                    'email' => 'siti@manmuhammad.sch.id',
                ])
                ->id,

            'nis' => '260002',
        ]);

        $andi = Student::factory()->create([
            'user_id' => User::factory()
                ->student()
                ->create([
                    'name' => 'Andi',
                    'email' => 'andi@manmuhammad.sch.id',
                ])
                ->id,

            'nis' => '260003',
        ]);

        $rina = Student::factory()->create([
            'user_id' => User::factory()
                ->student()
                ->create([
                    'name' => 'Rina',
                    'email' => 'rina@manmuhammad.sch.id',
                ])
                ->id,

            'nis' => '260004',
        ]);

        /*
        |--------------------------------------------------------------------------
        | 9. STUDENT QR
        |--------------------------------------------------------------------------
        */

        StudentQR::factory()->create([
            'student_id' => $budi->id,
        ]);

        StudentQR::factory()->create([
            'student_id' => $siti->id,
        ]);

        StudentQR::factory()->create([
            'student_id' => $andi->id,
        ]);

        StudentQR::factory()->create([
            'student_id' => $rina->id,
        ]);

        /*
        |--------------------------------------------------------------------------
        | 10. ENROLLMENTS
        |--------------------------------------------------------------------------
        */

        StudentEnrollment::factory()->active()->create([
            'student_id' => $budi->id,
            'class_room_id' => $kelasXA->id,
            'start_date' => '2026-07-13',
        ]);

        StudentEnrollment::factory()->active()->create([
            'student_id' => $siti->id,
            'class_room_id' => $kelasXA->id,
            'start_date' => '2026-07-13',
        ]);

        StudentEnrollment::factory()->active()->create([
            'student_id' => $andi->id,
            'class_room_id' => $kelasXIBP->id,
            'start_date' => '2026-07-13',
        ]);

        /*
         * Rina merupakan contoh siswa yang masuk di tengah semester.
         */
        StudentEnrollment::factory()->active()->create([
            'student_id' => $rina->id,
            'class_room_id' => $kelasXIBP->id,
            'start_date' => '2026-09-15',
        ]);

        /*
        |--------------------------------------------------------------------------
        | 11. TEACHING ASSIGNMENTS
        |--------------------------------------------------------------------------
        |
        | Di sini kita sengaja menguji:
        |
        | Guru A → Matematika → X-A
        | Guru B → Matematika → XI IPA-1
        | Guru A → Matematika → XI IPS-1
        |
        */

        $taMatematikaXA = TeachingAssignment::create([
            'teacher_id' => $guruA->id,
            'subject_id' => $matematika->id,
            'class_room_id' => $kelasXA->id,
            'semester' => 1,
        ]);

        $taMatematikaXIBP = TeachingAssignment::create([
            'teacher_id' => $guruB->id,
            'subject_id' => $matematika->id,
            'class_room_id' => $kelasXIBP->id,
            'semester' => 1,
        ]);

        $taMatematikaXIIPS = TeachingAssignment::create([
            'teacher_id' => $guruA->id,
            'subject_id' => $matematika->id,
            'class_room_id' => $kelasXIIPS->id,
            'semester' => 1,
        ]);

        $taBahasaXA = TeachingAssignment::create([
            'teacher_id' => $guruB->id,
            'subject_id' => $bahasaIndonesia->id,
            'class_room_id' => $kelasXA->id,
            'semester' => 1,
        ]);

        $taFisikaXIBP = TeachingAssignment::create([
            'teacher_id' => $guruC->id,
            'subject_id' => $fisika->id,
            'class_room_id' => $kelasXIBP->id,
            'semester' => 1,
        ]);

        /*
        |--------------------------------------------------------------------------
        | 12. SCHEDULES
        |--------------------------------------------------------------------------
        |
        | Matematika X-A sengaja dibuat dua kali pada hari Senin,
        | untuk menguji kasus yang kamu jelaskan sebelumnya.
        */

        $scheduleMathXA1 = Schedule::create([
            'teaching_assignment_id' => $taMatematikaXA->id,
            'day_of_week' => 1, // Senin
            'start_time' => '07:00:00',
            'end_time' => '07:45:00',
        ]);

        $scheduleMathXA2 = Schedule::create([
            'teaching_assignment_id' => $taMatematikaXA->id,
            'day_of_week' => 1, // Senin
            'start_time' => '08:30:00',
            'end_time' => '09:15:00',
        ]);

        Schedule::create([
            'teaching_assignment_id' => $taBahasaXA->id,
            'day_of_week' => 1,
            'start_time' => '07:45:00',
            'end_time' => '08:30:00',
        ]);

        $scheduleMathXIBP = Schedule::create([
            'teaching_assignment_id' => $taMatematikaXIBP->id,
            'day_of_week' => 2, // Selasa
            'start_time' => '07:00:00',
            'end_time' => '08:30:00',
        ]);

        Schedule::create([
            'teaching_assignment_id' => $taFisikaXIBP->id,
            'day_of_week' => 3, // Rabu
            'start_time' => '09:00:00',
            'end_time' => '10:30:00',
        ]);

        /*
        |--------------------------------------------------------------------------
        | 13. SCHOOL CALENDAR
        |--------------------------------------------------------------------------
        */

        SchoolCalendar::create([
            'academic_year_id' => $academicYear->id,
            'date' => '2026-08-17',
            'type' => 'national_holiday',
            'name' => 'Hari Kemerdekaan Republik Indonesia',
            'description' => 'Libur nasional.',
        ]);

        SchoolCalendar::create([
            'academic_year_id' => $academicYear->id,
            'date' => '2026-09-25',
            'type' => 'school_holiday',
            'name' => 'Libur Khusus Sekolah',
            'description' => 'Libur berdasarkan kebijakan sekolah.',
        ]);

        /*
        |--------------------------------------------------------------------------
        | 14. LESSON SESSIONS
        |--------------------------------------------------------------------------
        */

        $sessionMathXA1 = LessonSession::create([
            'schedule_id' => $scheduleMathXA1->id,
            'session_date' => '2026-09-21',
            'start_time' => '07:00:00',
            'end_time' => '07:45:00',
            'status' => 'completed',
        ]);

        $sessionMathXA2 = LessonSession::create([
            'schedule_id' => $scheduleMathXA2->id,
            'session_date' => '2026-09-21',
            'start_time' => '08:30:00',
            'end_time' => '09:15:00',
            'status' => 'completed',
        ]);

        $sessionMathXIBP = LessonSession::create([
            'schedule_id' => $scheduleMathXIBP->id,
            'session_date' => '2026-09-22',
            'start_time' => '07:00:00',
            'end_time' => '08:30:00',
            'status' => 'completed',
        ]);

        /*
        |--------------------------------------------------------------------------
        | 15. ABSENCE LETTERS
        |--------------------------------------------------------------------------
        */

        $suratSiti = AbsenceLetter::factory()
            ->sick()
            ->issued($admin)
            ->create([
                'student_id' => $siti->id,
                'reason' => 'Demam dan perlu istirahat.',
            ]);

        /*
        |--------------------------------------------------------------------------
        | 16. STUDENT ATTENDANCES
        |--------------------------------------------------------------------------
        */

        // Budi hadir.
        Attendance::factory()
            ->present()
            ->create([
                'lesson_session_id' => $sessionMathXA1->id,
                'student_id' => $budi->id,
            ]);

        // Siti sakit berdasarkan surat resmi.
        Attendance::factory()
            ->sick()
            ->create([
                'lesson_session_id' => $sessionMathXA1->id,
                'student_id' => $siti->id,
                'absence_letter_id' => $suratSiti->id,
            ]);

        /*
         * Budi hadir lagi pada sesi Matematika kedua.
         */
        Attendance::factory()
            ->present()
            ->create([
                'lesson_session_id' => $sessionMathXA2->id,
                'student_id' => $budi->id,
            ]);

        /*
         * Andi berada di XI IPA-1 dan hadir pada Matematika.
         */
        Attendance::factory()
            ->present()
            ->create([
                'lesson_session_id' => $sessionMathXIBP->id,
                'student_id' => $andi->id,
            ]);

        /*
         |--------------------------------------------------------------------------
         | 17. TEACHER ATTENDANCES
         |--------------------------------------------------------------------------
         */

        TeacherAttendance::create([
            'teacher_id' => $guruA->id,
            'date' => '2026-09-21',
            'scan_time' => '06:45:00',
            'status' => 'present',
        ]);

        TeacherAttendance::create([
            'teacher_id' => $guruB->id,
            'date' => '2026-09-21',
            'scan_time' => '07:05:00',
            'status' => 'late',
        ]);

        TeacherAttendance::create([
            'teacher_id' => $guruC->id,
            'date' => '2026-09-21',
            'scan_time' => '06:50:00',
            'status' => 'present',
        ]);

        /*
        |--------------------------------------------------------------------------
        | 18. GRADES
        |--------------------------------------------------------------------------
        */

        Grade::factory()
            ->assignment('Tugas 1')
            ->create([
                'student_id' => $budi->id,
                'teaching_assignment_id' => $taMatematikaXA->id,
                'score' => 85,
            ]);

        Grade::factory()
            ->assignment('Tugas 2')
            ->create([
                'student_id' => $budi->id,
                'teaching_assignment_id' => $taMatematikaXA->id,
                'score' => 90,
            ]);

        Grade::factory()
            ->midterm()
            ->create([
                'student_id' => $budi->id,
                'teaching_assignment_id' => $taMatematikaXA->id,
                'score' => 88,
            ]);

        Grade::factory()
            ->finalExam()
            ->create([
                'student_id' => $budi->id,
                'teaching_assignment_id' => $taMatematikaXA->id,
                'score' => 92,
            ]);
    }
}
