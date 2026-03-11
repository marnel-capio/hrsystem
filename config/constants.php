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
    'HR_STAFF'                => ['value' => 1,  'name' => 'HR Staff'],
    'TECHNICAL_RECRUITER'     => ['value' => 2,  'name' => 'Technical Recruiter'],
    'HR_ASSISTANT'            => ['value' => 3,  'name' => 'HR Assistant'],
    'HR_SENIOR_ASSISTANT'     => ['value' => 4,  'name' => 'HR Senior Assistant'],
    'HR_ASSOCIATE'            => ['value' => 5,  'name' => 'HR Associate'],
    'HR_SENIOR_ASSOCIATE'     => ['value' => 6,  'name' => 'HR Senior Associate'],
    'HR_SUPERVISOR'           => ['value' => 7,  'name' => 'HR Supervisor'],
    'HR_ASSISTANT_MANAGER'    => ['value' => 8,  'name' => 'HR Assistant Manager'],
    'HR_MANAGER'              => ['value' => 9,  'name' => 'HR Manager'],
    'BU_MANAGER'              => ['value' => 10, 'name' => 'BU Manager'],
    'OTHERS'                  => ['value' => 11, 'name' => 'Others'],

    // -------------------------
    // Permission Constants
    // -------------------------
    'HR_ADMIN'     => ['value' => 1, 'name' => 'HR Admin'],
    'HR_MANAGER'   => ['value' => 2, 'name' => 'HR Manager'],
    'HR_RECRUITER' => ['value' => 3, 'name' => 'HR Recruiter'],
    'HR'           => ['value' => 4, 'name' => 'HR'],
    'BU_MANAGER_P' => ['value' => 5, 'name' => 'BU Manager'],
    'INTERVIEWER'  => ['value' => 6, 'name' => 'Interviewer'],
    'WALKIN'       => ['value' => 7, 'name' => 'Walk-in'],

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
        '/intermediate/projects' => [1, 5],
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

    'full_edit_permissions' => [1, 2],
    'limited_edit_permissions' => [3, 4, 5, 6],

];