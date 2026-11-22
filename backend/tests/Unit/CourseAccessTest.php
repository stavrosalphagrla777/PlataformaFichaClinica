<?php
namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Support\CourseAccess;

class CourseAccessTest extends TestCase
{
    public function test_only_client_courses_are_valid(): void
    {
        foreach (['3.º Medio D','3.º Medio F','V3ro'] as $course) $this->assertTrue(CourseAccess::validCourse($course,'3.º medio'));
        foreach (['4.º Medio C','4.º Medio G','V4to'] as $course) $this->assertTrue(CourseAccess::validCourse($course,'4.º medio'));
        foreach (['3.º Medio A','4.º Medio A','Todos los cursos','V4to'] as $course) $this->assertFalse(CourseAccess::validCourse($course,'3.º medio'));
    }

    public function test_evening_assignments_do_not_leak_into_day_courses(): void
    {
        $this->assertTrue(CourseAccess::matches('V3ro','V3ro'));
        $this->assertFalse(CourseAccess::matches('V3ro','3.º Medio D'));
        $this->assertFalse(CourseAccess::matches('3.º Medio D','V3ro'));
        $this->assertTrue(CourseAccess::matches('3.º Medio D','3.º Medio D'));
    }
}
