<?php

return [

    // -------------------------
    // Position Values & Names
    // -------------------------
    'positions' => [
        1 => 'HR Staff',
        2 => 'Technical Recruiter',
        3 => 'HR Assistant',
        4 => 'HR Senior Assistant',
        5 => 'HR Associate',
        6 => 'HR Senior Associate',
        7 => 'HR Supervisor',
        8 => 'HR Assistant Manager',
        9 => 'HR Manager',
        10 => 'BU Manager',
        11 => 'Others',
    ],

    'permissionsList' => [
        1 => 'HR Admin',
        2 => 'HR Manager',
        3 => 'HR Recruiter',
        4 => 'HR',
        5 => 'BU Manager',
        6 => 'Interviewer',
        7 => 'Walk-in',
    ],

    // -------------------------
    // Position Constants
    // -------------------------
    'HR_STAFF_POSITION'                => ['value' => 1,  'name' => 'HR Staff'],
    'TECHNICAL_RECRUITER_POSITION'     => ['value' => 2,  'name' => 'Technical Recruiter'],
    'HR_ASSISTANT_POSITION'            => ['value' => 3,  'name' => 'HR Assistant'],
    'HR_SENIOR_ASSISTANT_POSITION'     => ['value' => 4,  'name' => 'HR Senior Assistant'],
    'HR_ASSOCIATE_POSITION'            => ['value' => 5,  'name' => 'HR Associate'],
    'HR_SENIOR_ASSOCIATE_POSITION'     => ['value' => 6,  'name' => 'HR Senior Associate'],
    'HR_SUPERVISOR_POSITION'           => ['value' => 7,  'name' => 'HR Supervisor'],
    'HR_ASSISTANT_MANAGER_POSITION'    => ['value' => 8,  'name' => 'HR Assistant Manager'],
    'HR_MANAGER_POSITION'              => ['value' => 9,  'name' => 'HR Manager'],
    'BU_MANAGER_POSITION'              => ['value' => 10, 'name' => 'BU Manager'],
    'OTHERS_POSITION'                  => ['value' => 11, 'name' => 'Others'],

    // -------------------------
    // Permission Constants
    // -------------------------
    'HR_ADMIN_PERMISSION'     => ['value' => 1, 'name' => 'HR Admin'],
    'HR_MANAGER_PERMISSION'   => ['value' => 2, 'name' => 'HR Manager'],
    'HR_RECRUITER_PERMISSION' => ['value' => 3, 'name' => 'HR Recruiter'],
    'HR_PERMISSION'           => ['value' => 4, 'name' => 'HR'],
    'BU_MANAGER_PERMISSION' => ['value' => 5, 'name' => 'BU Manager'],
    'INTERVIEWER_PERMISSION'  => ['value' => 6, 'name' => 'Interviewer'],
    'WALKIN_PERMISSION'       => ['value' => 7, 'name' => 'Walk-in'],

    // -------------------------
    // Menu & Permissions
    // -------------------------
    'menuPermissions' => [
        '/user' => [1,2],
        '/application-tracker' => [1, 2, 3, 5, 6],
        '/application-tracker-dashboard' => [1, 2, 3, 5, 6],
        '/action/batches' => [1, 2, 3],
        '/action/schedules' => [1, 2, 3],
        '/action/applicants' => [1, 2, 3, 5, 6],
        '/action/applications' => [1, 2, 3, 5, 6],
        '/intermediate/projects' => [1,2,3,5],
        '/intermediate/resource-requisitions' => [1, 2, 3, 5],
        '/intermediate/applicants' => [1, 2, 3, 5, 6],
        '/intermediate/applications' => [1, 2, 3, 5, 6],
        '/dashboard' => [1,2,3,5],
        '/walk-in-application' => [1, 7],
        '/account/settings' => [1,2,3,4,5,6],
    ],

    'hiddenLinks' => [
        '/application-tracker' => [4, 7],
        '/application-tracker-dashboard' => [4, 6, 7],
        '/action' => [4, 7],
        '/intermediate' => [4, 7],
        '/account/settings' => [7],
    ],

    'trainingLocation' => [
        'LOCATION_MANILA_VALUE' => 1,
        'LOCATION_CEBU_VALUE' => 2,
        'LOCATION_1_NAME' => 'Manila',
        'LOCATION_2_NAME' => 'Cebu',
    ],

    'full_edit_permissions' => [1, 2],
    'limited_edit_permissions' => [3, 4, 5, 6],


    // Source Type Mapping
    'source_type' => [
        'Indeed' => 3,
        'Facebook' => 3,
        'Kalibrr' => 3,
        'Linkedin' => 3,
        'Jobstreet' => 3,
        'Referral (Employee Referral or Applicant Referral)' => 4,
        'Campus Recruitment Activity' => 1,
    ],

    // Source Mapping (for Recruitment Portals)
    'source' => [
        'Indeed' => 2,
        'Facebook' => 6,
        'Kalibrr' => 3,
        'Linkedin' => 5,
        'Jobstreet' => 7,
    ],

    // Recruitment Portal Keywords (case-insensitive check)
    'recruitment_portal_keywords' => ['Indeed', 'Facebook', 'Kalibrr', 'Linkedin', 'Jobstreet'],



    // -------------------------
    // Gender Mapping
    // -------------------------
    'gender' => [
        'male' => 1, 'm' => 1,
        'female' => 2, 'f' => 2,
    ],

    // -------------------------
    // Exam Status Mapping
    // -------------------------
    'exam_status' => [
        'pending' => 1,
        'passed'  => 2,
        'failed'  => 3,
        'p2'      => 4,
        'p3'      => 5,
        'withdrew' => 6,
        'no show' => 7,
        'PASSED'  => 2,
        'FAILED'  => 3,
        'Withdrew'=> 6,
        'No Show' => 7,
    ],


     'sourceTypes' => [
        1 => 'Campus Recruitment',
        2 => 'Academe Partner',
        3 => 'Recruitment Portals',
        4 => 'Employee Referral',
        5 => 'Walk-in'
    ],

    'sTypes' => [
        'CAMPUS_RECRUITMENT_VALUE' => 1,
        'ACADEME_PARTNER_VALUE' => 2,
        'RECRUITMENT_PORTALS_VALUE' => 3,
        'EMPLOYEE_REFERRAL_VALUE' => 4,
        "WALK-IN_VALUE" => 5,
        'CAMPUS_RECRUITMENT_NAME' => 'Campus Recruitment',
        'ACADEME_PARTNER_NAME' => 'Academe Partner',
        'RECRUITMENT_PORTALS_NAME' => 'Recruitment Portals',
        'EMPLOYEE_REFERRAL_NAME' => 'Employee Referral',
        "WALK-IN_NAME" => 'Walk-in',
    ],
    

    'sources' => [
        1 => 'University Career Fair',
        2 => 'Partner School',
        3 => 'JobStreet',
        4 => 'LinkedIn',
        5 => 'Referral',
        6 => 'Facebook',
        7 => 'Jobstreet',
    ],

    'source' => [
        'MYNIMO_VALUE' => 1,
        'INDEED_VALUE' => 2,
        'KALIBRR_VALUE' => 3,
        'FOUNDIT_VALUE' => 4,
        "LINKEDIN_VALUE" => 5,
        'FACEBOOK_VALUE' => 6,
        "JOBSTREET_VALUE" => 7,
        'MYNIMO_NAME' => 'Mynimo',
        'INDEED_NAME' => 'Indeed',
        'KALIBRR_NAME' => 'Kalibrr',
        'FOUNDIT_NAME' => 'FoundIt',
        "LINKEDIN_NAME" => 'LinkedIn',
        'FACEBOOK_NAME' => 'Facebook',
        "JOBSTREET_NAME" => 'Jobstreet',
    ],

    // -------------------------
    // Gender Options
    // -------------------------
    'genders' => [
        1 => 'Male',
        2 => 'Female'
    ],

    'gender' => [
        'MALE_VALUE' => 1,
        'FEMALE_VALUE' => 2,
        'MALE_NAME' => 'Male',
        'FEMALE_NAME' => 'Female',
    ],


];
