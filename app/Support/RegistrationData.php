<?php

namespace App\Support;

class RegistrationData
{
    private const STUDENT_TEXT_FIELDS = [
        'firstname',
        'lastname',
        'course',
        'year',
        'blood_type',
        'emergency_contact_name',
        'emergency_contact_relationship',
        'emergency_address',
    ];

    private const EMPLOYEE_TEXT_FIELDS = [
        'firstname',
        'lastname',
        'department',
        'position',
        'status',
        'sex',
        'blood_type',
        'civil_status',
        'emergency_contact_name',
        'emergency_contact_relationship',
        'address',
    ];

    public static function uppercaseStudentFields(array $data): array
    {
        return self::uppercaseFields($data, self::STUDENT_TEXT_FIELDS);
    }

    public static function uppercaseEmployeeFields(array $data): array
    {
        return self::uppercaseFields($data, self::EMPLOYEE_TEXT_FIELDS);
    }

    private static function uppercaseFields(array $data, array $fields): array
    {
        foreach ($fields as $field) {
            if (isset($data[$field]) && is_string($data[$field])) {
                $data[$field] = mb_strtoupper(trim($data[$field]), 'UTF-8');
            }
        }

        return $data;
    }
}
