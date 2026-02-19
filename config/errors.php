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

    
    // Universal message for all record updated successfully
    'record_updated_successfully' => [
        'errorCode' => 'RECORD_UPDATED_SUCCESSFULLY',
        'errorMessage' => 'Record updated successfully.',
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
    'target_trainees_min' => [
        'errorCode' => 'TARGET_TRAINEES_MIN',
        'errorMessage' => 'Target trainees must be at least 1.',
    ],
    'wbs_start_required' => [
        'errorCode' => 'WBS_START_REQUIRED',
        'errorMessage' => 'Start week is required.',
    ],
    'wbs_end_required' => [
        'errorCode' => 'WBS_END_REQUIRED',
        'errorMessage' => 'End week is required.',
    ],
    'wbs_end_before_start' => [
        'errorCode' => 'WBS_END_BEFORE_START',
        'errorMessage' => 'Start week cannot be after end week.',
    ],
];