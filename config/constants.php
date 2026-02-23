<?php

return [
    'menuPermissions' => [
        '/user' => [1],
        '/application-tracker-dashboard' => [1, 2, 3, 5, 6], 
        '/action/batches' => [1, 2, 3],
        '/action/schedules' => [1, 2, 3],
        '/action/applicants' => [1, 2, 3, 5, 6],
        '/action/applications' => [1, 2, 3, 5, 6],
        '/intermediate/projects' => [1, 5],
        '/intermediate/requests' => [1, 5],
        '/intermediate/applicants' => [1, 2, 3, 5, 6],
        '/intermediate/applications' => [1, 2, 3, 5, 6],
        '/dashboard' => [1,2,3,5], // only users allowed to see dashboard
        '/walk-in-application' => [1, 7],
        '/account/settings' => [1,2,3,4,5,6],
    ],
    'hiddenLinks' => [
        '/application-tracker-dashboard' => [4, 6, 7], // hide dashboard for these
        '/action' => [4, 7],
        '/intermediate' => [4, 7],
        '/account/settings' => [7],
        
    ],
];