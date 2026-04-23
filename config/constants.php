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
    'HR_STAFF_POSITION' => ['value' => 1,  'name' => 'HR Staff'],
    'TECHNICAL_RECRUITER_POSITION' => ['value' => 2,  'name' => 'Technical Recruiter'],
    'HR_ASSISTANT_POSITION' => ['value' => 3,  'name' => 'HR Assistant'],
    'HR_SENIOR_ASSISTANT_POSITION' => ['value' => 4,  'name' => 'HR Senior Assistant'],
    'HR_ASSOCIATE_POSITION' => ['value' => 5,  'name' => 'HR Associate'],
    'HR_SENIOR_ASSOCIATE_POSITION' => ['value' => 6,  'name' => 'HR Senior Associate'],
    'HR_SUPERVISOR_POSITION' => ['value' => 7,  'name' => 'HR Supervisor'],
    'HR_ASSISTANT_MANAGER_POSITION' => ['value' => 8,  'name' => 'HR Assistant Manager'],
    'HR_MANAGER_POSITION' => ['value' => 9,  'name' => 'HR Manager'],
    'BU_MANAGER_POSITION' => ['value' => 10, 'name' => 'BU Manager'],
    'OTHERS_POSITION' => ['value' => 11, 'name' => 'Others'],

    // -------------------------
    // Permission Constants
    // -------------------------
    'HR_ADMIN_PERMISSION' => ['value' => 1, 'name' => 'HR Admin'],
    'HR_MANAGER_PERMISSION' => ['value' => 2, 'name' => 'HR Manager'],
    'HR_RECRUITER_PERMISSION' => ['value' => 3, 'name' => 'HR Recruiter'],
    'HR_PERMISSION' => ['value' => 4, 'name' => 'HR'],
    'BU_MANAGER_PERMISSION' => ['value' => 5, 'name' => 'BU Manager'],
    'INTERVIEWER_PERMISSION' => ['value' => 6, 'name' => 'Interviewer'],
    'WALKIN_PERMISSION' => ['value' => 7, 'name' => 'Walk-in'],

    // -------------------------
    // Menu & Permissions
    // -------------------------
    'menuPermissions' => [
        '/user' => [1, 2],
        '/application-tracker' => [1, 2, 3, 5, 6],
        '/application-tracker-dashboard' => [1, 2, 3, 5, 6],
        '/action/batches' => [1, 2, 3],
        '/action/schedules' => [1, 2, 3],
        '/action/applicants' => [1, 2, 3, 5, 6],
        '/action/applications' => [1, 2, 3, 5, 6],
        '/intermediate/projects' => [1, 2, 3, 5],
        '/intermediate/resource-requisitions' => [1, 2, 3, 5],
        '/intermediate/applicants' => [1, 2, 3, 5, 6],
        '/intermediate/applications' => [1, 2, 3, 5, 6],
        '/dashboard' => [1, 2, 3, 5],
        '/walk-in-application' => [1, 7],
        '/account/settings' => [1, 2, 3, 4, 5, 6],
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

    'sourceTypes' => [
        1 => 'Campus Recruitment',
        2 => 'Academe Partner',
        3 => 'Recruitment Portals',
        4 => 'Employee Referral',
        5 => 'Walk-in',
    ],

    // Source Mapping (for Recruitment Portals)
    'source_map' => [
        'Indeed' => 2,
        'Facebook' => 6,
        'Kalibrr' => 3,
        'Linkedin' => 5,
        'Jobstreet' => 7,
    ],

    'recruitment_portal_keywords' => ['Indeed', 'Facebook', 'Kalibrr', 'Linkedin', 'Jobstreet'],

    // APPLICATIONS CONSTANTS

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

    // -------------------------
    // Exam Status Mapping
    // -------------------------
    'exam_status' => [
        'pending' => 1,
        'p1' => 2,
        'p2' => 3,
        'done' => 4,
        'passed' => 5,
        'failed' => 6,
        'no show' => 6,
        'withdrew' => 6,
    ],

    'sTypes' => [
        'CAMPUS_RECRUITMENT_VALUE' => 1,
        'ACADEME_PARTNER_VALUE' => 2,
        'RECRUITMENT_PORTALS_VALUE' => 3,
        'EMPLOYEE_REFERRAL_VALUE' => 4,
        'WALK-IN_VALUE' => 5,
        'CAMPUS_RECRUITMENT_NAME' => 'Campus Recruitment',
        'ACADEME_PARTNER_NAME' => 'Academe Partner',
        'RECRUITMENT_PORTALS_NAME' => 'Recruitment Portals',
        'EMPLOYEE_REFERRAL_NAME' => 'Employee Referral',
        'WALK-IN_NAME' => 'Walk-in',
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

    'examVenues' => [
        1 => 'Online',
        2 => 'Face to face',
    ],

    'examResults' => [
        1 => 'Pending',
        2 => 'Passed',
        3 => 'Failed',
    ],

    'examApplicationStatuses' => [
        1 => 'Pending',
        3 => '2nd Priority (P2)',
        4 => 'Done',
        5 => 'Passed',
        6 => 'Failed',
    ],

    'interviewResults' => [
        1 => 'Pending',
        2 => 'Passed',
        3 => 'Failed',
    ],

    'applicationStatuses' => [
        1 => 'Pending',
        2 => 'Done',
        3 => 'Passed',
        4 => 'P2',
        5 => 'Failed',
    ],

    'jobOfferStatuses' => [
        1 => 'Pending',
        2 => 'Done',
        3 => 'Accept',
        4 => 'Decline',
        5 => 'Withdraw',
        6 => 'Retracted',
    ],

    'traineesFrom' => [
        1 => 'Manila',
        2 => 'Cebu',
    ],


    'source' => [
        'MYNIMO_VALUE' => 1,
        'INDEED_VALUE' => 2,
        'KALIBRR_VALUE' => 3,
        'FOUNDIT_VALUE' => 4,
        'LINKEDIN_VALUE' => 5,
        'FACEBOOK_VALUE' => 6,
        'JOBSTREET_VALUE' => 7,
        'MYNIMO_NAME' => 'Mynimo',
        'INDEED_NAME' => 'Indeed',
        'KALIBRR_NAME' => 'Kalibrr',
        'FOUNDIT_NAME' => 'FoundIt',
        'LINKEDIN_NAME' => 'LinkedIn',
        'FACEBOOK_NAME' => 'Facebook',
        'JOBSTREET_NAME' => 'Jobstreet',
    ],

    'action_application_stage_fields' => [
        'exam' => [
            'exam_plan_date',
            'exam_actual_date',
            'exam_venue',
            'exam_atpp_result',
            'exam_git_result',
            'exam_prg_result',
            'exam_result',
            'exam_application_status',
            'exam_remarks',
        ],
        'initial_interview' => [
            'initial_interview_plan_date',
            'initial_interview_actual_date',
            'initial_interview_venue',
            'initial_interview_assignments',
            'initial_interview_final',
            'initial_interview_result',
            'initial_interview_application_status',
            'initial_interview_remarks',
        ],
        'final_interview' => [
            'final_interview_date',
            'final_interview_score_1',
            'final_interview_score_2',
            'final_interview_score_3',
            'final_interview_score_4',
            'final_interview_final',
            'final_interview_result',
            'final_interview_application_status',
            'final_interview_remarks',
        ],
        'job_offer' => [
            'job_offer_schedule',
            'job_offer_status',
            'job_offer_remarks',
        ],
        'general' => [
            'remarks',
        ],
        'documents' => [
            'upload_resume',
            'upload_tor',
            'upload_pic',
        ],
    ],

    'action_application_field_labels' => [
        'exam_plan_date' => 'Exam Plan Date',
        'exam_actual_date' => 'Exam Actual Date',
        'exam_venue' => 'Exam Venue',
        'exam_atpp_result' => 'Exam ATPP Result',
        'exam_git_result' => 'Exam GIT Result',
        'exam_prg_result' => 'Exam PRG Result',
        'exam_result' => 'Exam Result',
        'exam_application_status' => 'Exam Application Status',
        'exam_remarks' => 'Exam Remarks',

        'initial_interview_plan_date' => 'Initial Interview Plan Date',
        'initial_interview_actual_date' => 'Initial Interview Actual Date',
        'initial_interview_venue' => 'Initial Interview Venue',
        'initial_interview_final' => 'Initial Interview Final Score',
        'initial_interview_result' => 'Initial Interview Result',
        'initial_interview_application_status' => 'Initial Interview Status',
        'initial_interview_remarks' => 'Initial Interview Remarks',

        'final_interview_date' => 'Final Interview Date',
        'final_interview_score_1' => 'Final Interview Score 1',
        'final_interview_score_2' => 'Final Interview Score 2',
        'final_interview_score_3' => 'Final Interview Score 3',
        'final_interview_score_4' => 'Final Interview Score 4',
        'final_interview_final' => 'Final Interview Final Score',
        'final_interview_result' => 'Final Interview Result',
        'final_interview_application_status' => 'Final Interview Status',
        'final_interview_remarks' => 'Final Interview Remarks',

        'job_offer_schedule' => 'Job Offer Schedule',
        'job_offer_status' => 'Job Offer Status',
        'job_offer_remarks' => 'Job Offer Remarks',

        'remarks' => 'Remarks',
    ],

    // -------------------------
    // Gender Options
    // -------------------------
    'genders' => [
        1 => 'Male',
        2 => 'Female',
    ],

    'gender' => [
        'MALE_VALUE' => 1,
        'FEMALE_VALUE' => 2,
        'MALE_NAME' => 'Male',
        'FEMALE_NAME' => 'Female',
        'male' => 1, 'm' => 1,
        'female' => 2, 'f' => 2,
        'Male' => 1, 'Female' => 2,
    ],

    'tech_degree_patterns' => [
        'bs information technology',
        'bachelor of science in information technology',
        'bachelor of science major in information technology',
        'information technology',
        'bsit',
        'it',
        'bs it',

        'bs computer science',
        'bachelor of science in computer science',
        'bachelor of science major in computer science',
        'computer science',
        'bscs',
        'cs',
        'bs cs',

        'bs computer engineering',
        'bachelor of science in computer engineering',
        'bachelor of science major in computer engineering',
        'computer engineering',
        'bscpe',
        'cpe',
        'bs cpe',
    ],
    'interview_types' => [
        'exam' => 1,
        'initial' => 2,
        'final' => 3,
    ],

    'interview_assignment_status' => [
        'pending_approval' => 1,
        'approved' => 2,
        'declined' => 3,
        'completed' => 4,
    ],

    'application_results' => [
        'pending' => 1,
        'passed' => 2,
        'failed' => 3,
    ],

    'interview_type_labels' => [
        1 => 'Exam',
        2 => 'Initial Interview',
        3 => 'Final Interview',
    ],



        'final_interview' => [
            1 => 1, // Pending -> Pending
            2 => 1, // Done -> Pending
            3 => 2, // Passed -> Passed
            4 => 2, // P2 -> Passed
            5 => 3, // Failed -> Failed
        ],



    'application_score_rules' => [
        'exam' => [
            'young_it' => [
                'passed' => ['attp' => 60, 'git' => 6, 'prg' => 30],
                'p2' => ['attp' => 55, 'git' => 5, 'prg' => 20],
            ],
            'young_other' => [
                'passed' => ['attp' => 70, 'git' => 6, 'prg' => 30],
                'p2' => ['attp' => 55, 'git' => 5, 'prg' => 20],
            ],
            'adult' => [
                'passed' => ['attp' => 70, 'git' => 6, 'prg' => 30],
            ],
        ],

        'initial_interview' => [
            'passed_min' => 2.0,
            'p2_min' => 2.5,
            'failed_min' => 4.0,
        ],

    ],

    'application_result_map' => [

        'exam' => [
            1 => 1, // Pending  -> Pending
            2 => 2, // 1st Priority (Passed) -> Passed
            3 => 2, // 2nd Priority (P2) -> Passed
            4 => 1, // Done -> Pending
            5 => 2, // Passed -> Passed
            6 => 3, // Failed -> Failed
        ],

        'initial_interview' => [
            1 => 1, // Pending -> Pending
            2 => 1, // Done -> Pending
            3 => 2, // Passed -> Passed
            4 => 2, // P2 -> Passed
            5 => 3, // Failed -> Failed
        ],

        'final_interview' => [
            1 => 1, // Pending -> Pending
            2 => 1, // Done -> Pending
            3 => 2, // Passed -> Passed
            4 => 2, // P2 -> Passed
            5 => 3, // Failed -> Failed
        ],
    ],

    'application_score_rules' => [
        'exam' => [
            'young_it' => [
                'passed' => ['attp' => 60, 'git' => 6, 'prg' => 30],
                'p2' => ['attp' => 55, 'git' => 5, 'prg' => 20],
            ],
            'young_other' => [
                'passed' => ['attp' => 70, 'git' => 6, 'prg' => 30],
                'p2' => ['attp' => 55, 'git' => 5, 'prg' => 20],
            ],
            'adult' => [
                'passed' => ['attp' => 70, 'git' => 6, 'prg' => 30],
            ],
        ],

        'initial_interview' => [
            'passed_min' => 2.0,
            'p2_min' => 2.5,
            'failed_min' => 4.0,
        ],

    ],
'resource_requisitions' => [
    'engagement_type' => [
        'ET_1_NAME' => 'Permanent',
        'ET_2_NAME' => 'Temporary',
        'ET_3_NAME' => 'OJT',
    ],
    'sourcing_type' => [
        'ST_1_NAME' => 'Internal',
        'ST_2_NAME' => 'External',
        'ST_3_NAME' => 'Either',
    ],
    'request_type' => [
        'RT_1_NAME' => 'New Requirement',
        'RT_2_NAME' => 'Replacement',
    ],
    'replacement_due_to' => [
        'RDT_1_NAME' => 'Promotion',
        'RDT_2_NAME' => 'Attrition',
        'RDT_3_NAME' => 'Backfill',
        'RDT_4_NAME' => 'Transfer',
    ],
    'location_assignment' => [
        'LA_1_NAME' => 'Alabang',
        'LA_2_NAME' => 'Makati',
        'LA_3_NAME' => 'Cebu',
        'LA_4_NAME' => 'Japan',
        'LA_5_NAME' => 'China',
    ],
],

    'source_maps' => [
        'type1' => [
            'Foundit' => 1,
            'LinkedIn' => 2,
            'Facebook' => 3,
            'Mynimo' => 4,
            'Kalibrr' => 5,
        ],
        'type3' => [
            'AAISI' => 1,
            'Primover' => 2,
            'Tech Tierra' => 3,
            'Spring Valley' => 4,
            'YENS' => 5,
        ],
    ],

    'source_type_groups' => [
        'type1' => ['Foundit', 'LinkedIn', 'Facebook', 'Mynimo', 'Kalibrr'],
        'type2' => ['Referral', 'Recruitment Network', 'Job Fairs', 'Website', 'Rehire'],
        'type3' => ['AAISI', 'Primover', 'Tech Tierra', 'Spring Valley', 'YENS'],
    ],

    'japanese_backgrounds' => [
        1 => 'None',
        2 => 'Self Study / University Level',
        3 => 'JLPT Certification',
    ],


    'japanese_levels' => [
        5 => 'N5',
        4 => 'N4',
        3 => 'N3',
        2 => 'N2',
        1 => 'N1',
    ],
    'engagement_type' => [
        'ET_PERMANENT_VALUE' => 1,
        'ET_TEMPORARY_VALUE' => 2,
        'ET_OJT_VALUE' => 3,

        'ET_1_NAME' => 'Permanent',
        'ET_2_NAME' => 'Temporary',
        'ET_3_NAME' => 'OJT',
    ],

    'sourcing_type' => [
    'ST_INTERNAL_VALUE' => 1,
    'ST_EXTERNAL_VALUE' => 2,
    'ST_EITHER_VALUE' => 3,
    'ST_1_NAME' => 'Internal',
    'ST_2_NAME' => 'External',
    'ST_3_NAME' => 'Either',
    ],

    'request_type' => [
        'RT_NEW_REQUIREMENT_VALUE' => 1,
        'RT_REPLACEMENT_VALUE' => 2,
        'RT_1_NAME' => 'New Requirement',
        'RT_2_NAME' => 'Replacement',
    ],

    'replacement_due_to' => [
        'RDT_PROMOTION_VALUE' => 1,
        'RDT_ATTRITION_VALUE' => 2,
        'RDT_BACKFILL_VALUE' => 3,
        'RDT_TRANSFER_VALUE' => 4,
        'RDT_1_NAME' => 'Promotion',
        'RDT_2_NAME' => 'Attrition',
        'RDT_3_NAME' => 'Backfill',
        'RDT_4_NAME' => 'Transfer',
    ],

    'location_assignment' => [
        'LA_ALABANG_VALUE' => 1,
        'LA_MAKATI_VALUE' => 2,
        'LA_CEBU_VALUE' => 3,
        'LA_JAPAN_VALUE' => 4,
        'LA_CHINA_VALUE' => 5,
        'LA_1_NAME' => 'Alabang',
        'LA_2_NAME' => 'Makati',
        'LA_3_NAME' => 'Cebu',
        'LA_4_NAME' => 'Japan',
        'LA_5_NAME' => 'China',
    ],

    'exam_statuses' => [
        1 => 'Pending',
        2 => 'Done',
        3 => 'Passed',
        4 => 'P2',
        5 => 'Failed',
    ],

    'interview_statuses' => [
        1 => 'Pending',
        2 => 'Done',
        3 => 'Passed',
        4 => 'P2',
        5 => 'Failed',
    ],

    'job_offer_statuses' => [
        1 => 'Pending',
        2 => 'Done',
        3 => 'Accept',
        4 => 'Decline',
        5 => 'Withdraw',
        6 => 'Retracted',
    ],

    'intermediateSourceTypes' => [
    1 => 'SP (Service Provider)',
    2 => 'Recruitment Portals',
    3 => 'Employee Referral',
    4 => 'Walk-in',
],

'intermediateSources' => [
    1 => 'Mynimo',
    2 => 'Indeed',
    3 => 'Kalibrr',
    4 => 'FoundIt',
    5 => 'LinkedIn',
    6 => 'Facebook',
    7 => 'Jobstreet',
    8 => 'AAISI',
    9 => 'Primover',
    10 => 'Pan Asia',
    11 => 'Nityo',
    12 => 'CPS',
],



];
