<?php
namespace App\Support;

/**
 * Aísla los cursos diurnos y vespertinos. La clave académica (3.º/4.º) no
 * reemplaza la jornada: una asignación para diurnos no se comparte con V3ro/V4to.
 */
final class CourseAccess
{
    public static function isEvening(?string $course): bool
    {
        return in_array(mb_strtoupper(trim((string)$course)), ['V3RO','V4TO'], true);
    }

    /** Cursos confirmados con el establecimiento; no se admiten secciones inventadas. */
    public const COURSES = [
        '3.º medio' => ['3.º Medio D', '3.º Medio F', 'V3ro'],
        '4.º medio' => ['4.º Medio C', '4.º Medio G', 'V4to'],
    ];

    public static function optionsForLevel(string $level): array
    {
        return self::COURSES[$level] ?? [];
    }

    public static function allCourses(): array
    {
        return array_merge(...array_values(self::COURSES));
    }

    public static function validCourse(string $course, string $level): bool
    {
        return in_array(trim($course), self::optionsForLevel($level), true);
    }

    public static function matches(?string $studentCourse, string $assignedCourse): bool
    {
        $student = trim((string)$studentCourse);
        if (self::isEvening($student)) return mb_strtoupper($student) === mb_strtoupper(trim($assignedCourse));
        if (self::isEvening($assignedCourse)) return false;
        if ($assignedCourse === 'Todos los cursos') return $student !== '';
        if (!preg_match('/^([34])/u', $student, $year) || !preg_match('/^([34])/u', $assignedCourse, $assignYear) || $year[1] !== $assignYear[1]) return false;
        preg_match('/([A-G])\s*$/iu', $student, $left);
        preg_match('/([A-G])\s*$/iu', $assignedCourse, $right);
        return isset($left[1],$right[1]) && mb_strtoupper($left[1]) === mb_strtoupper($right[1]);
    }

    /** Compatible con nombres históricos: 3° A, 3.º Medio A, 3ro A. */
    public static function assignmentCourseCandidates(?string $studentCourse): array
    {
        $student = trim((string)$studentCourse);
        if (self::isEvening($student)) return [$student];
        if (!preg_match('/^([34])/u',$student,$year) || !preg_match('/([A-G])\s*$/iu',$student,$letter)) return [];
        $n=$year[1];$l=mb_strtoupper($letter[1]);
        return array_unique(["{$n}.º Medio {$l}","{$n}.º {$l}","{$n}° {$l}","{$n}ro {$l}","{$n}to {$l}"]);
    }
}
