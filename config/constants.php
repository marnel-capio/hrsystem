<?php

return [
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
];