<?php

return [
    
 // Universal message for all transaction failed
    'transaction_failed' => [
        'errorCode' => 'TRANSACTION_FAILED',
        'errorMessage' => 'An error occurred while creating the record. Please try again.',
    ],


 // Universal message for all record created succesfully
    'record_created_successfully' => [
        'errorCode' => 'RECORD_CREATED_SUCCESSFULLY',
        'errorMessage' => 'Record created successfully.',
    ],

    // Universal message for all required fields
    'field_required' => [
        'errorCode' => 'FIELD_REQUIRED',
        'errorMessage' => 'This field is required.',
    ],


    //RESOURCE SCHED REGISTER ERROR MSGS
    'batch_name_taken' => [
        'errorCode' => 'BATCH_NAME_TAKEN',
        'errorMessage' => 'This batch name is already in use.',
    ],
    'target_trainees_invalid' => [
        'errorCode' => 'TARGET_TRAINEES_INVALID',
        'errorMessage' => 'Target trainees must be a number.',
    ],
    'target_trainees_min' => [
        'errorCode' => 'TARGET_TRAINEES_MIN',
        'errorMessage' => 'Target trainees must be at least 1.',
    ],
    'deployment_date_format' => [
        'errorCode' => 'DEPLOYMENT_DATE_FORMAT',
        'errorMessage' => 'Deployment date must be in YYYY-MM format.',
    ],
    'wbs_required' => [
        'errorCode' => 'WBS_REQUIRED',
        'errorMessage' => 'WBS is required.',
    ],
    'wbs_start_required' => [
        'errorCode' => 'WBS_START_REQUIRED',
        'errorMessage' => 'Start week is required.',
    ],
    'wbs_end_required' => [
        'errorCode' => 'WBS_END_REQUIRED',
        'errorMessage' => 'End week is required.',
    ],
    'wbs_invalid_format' => [
        'errorCode' => 'WBS_INVALID_FORMAT',
        'errorMessage' => 'WBS weeks must be in YYYY-WWW format (e.g., 2024-W01).',
    ],
    'wbs_end_before_start' => [
        'errorCode' => 'WBS_END_BEFORE_START',
        'errorMessage' => 'Start week cannot be after end week.',
    ],
];