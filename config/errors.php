<?php

// unsure if gagamitin pa ito or if the error validations will be hardcoded inline in web.php like it is now

return [

    // Universal message for field max length exceeded
        'max_length_exceeded' => [
        'errorCode' => 'MAX_LENGTH_EXCEEDED',
        'errorMessage' => 'This field exceeds the maximum allowed length.',
    ],

    // Universal message for all transaction failed
    'transaction_failed' => [
        'errorCode' => 'TRANSACTION_FAILED',
        'errorMessage' => 'An error occurred while creating the record. Please try again.',
    ],

    // Unauthorized access
    'unauthorized' => [
        'errorCode' => 'UNAUTHORIZED',
        'errorMessage' => 'Access denied: You are not authorized to view this page.',
    ],

    // Universal message for all record created succesfully
    'record_created_successfully' => [
        'errorCode' => 'RECORD_CREATED_SUCCESSFULLY',
        'errorMessage' => 'Record created successfully.',
    ],

    // Universal message for all required fields
    'field_required' => [
        'errorCode' => 'ErrMsg00013',
        'errorMessage' => 'This is a required field.',
    ],

    
     // Universal message for all record created succesfully
    'record_created_successfully' => [
        'errorCode' => 'SucMsg00001',
        'errorMessage' => 'Record created successfully!',
    ],

    // ACCOUNT IS INACTIVE
    'account_inactive' => [
    'errorCode' => 'ACCOUNT_INACTIVE',
    'errorMessage' => 'Your account is no longer active. Please check with your manager or admin.',
    ],

    // RESOURCE SCHED REGISTER ERROR MSGS
    'batch_name_taken' => [
        'errorCode' => 'ErrMsg00018',
        'errorMessage' => 'The batch name has already been taken.',
    ],

    'target_trainees_min' => [
        'errorCode' => 'ErrMsg00019',
        'errorMessage' => 'Target trainees must be at least 1.',
    ],

    'wbs_end_before_start' => [
        'errorCode' => 'ErrMsg00014',
        'errorMessage' => 'Start week cannot be after end week.',
    ],

    // FOR USER REGISTRATION

    'password_complexity_failed' => [
        'errorCode' => 'PASSWORD_COMPLEXITY_FAILED',
        'errorMessage' => 'Password must contain at least 1 uppercase letter, 1 lowercase letter, 1 number, and 1 special character (!@#$%&*_)',
    ],

    'aws_email_required' => [
        'errorCode' => 'AWS_EMAIL_REQUIRED',
        'errorMessage' => 'The :attribute must be your AWS email address.',
    ],

    'alpha_space_dash' => [
        'errorCode' => 'ALPHA_SPACE_DASH',
        'errorMessage' => 'Only letters, spaces, and hyphens are allowed.',
    ],

    'contact_number_numeric' => [
        'errorCode' => 'CONTACT_NUMBER_NUMERIC',
        'errorMessage' => 'The contact number must contain only numbers.',
    ],

    'contact_number_length' => [
        'errorCode' => 'CONTACT_NUMBER_LENGTH',
        'errorMessage' => 'The contact number must be exactly 11 digits.',
    ],



    //ACTION BATCH
    'action_batch_required' => [
        'errorCode' => 'ACTION_BATCH_REQUIRED',
        'errorMessage' => 'This field is required.',
    ],

    'action_batch_max' => [
        'errorCode' => 'ACTION_BATCH_MAX',
        'errorMessage' => 'This field exceeds the maximum allowed length.',
    ],

    'action_batch_unique' => [
        'errorCode' => 'ACTION_BATCH_UNIQUE',
        'errorMessage' => 'Action Batch already exists.',
    ],

    'target_trainees_required' => [
        'errorCode' => 'TARGET_TRAINEES_REQUIRED',
        'errorMessage' => 'This field is required.',
    ],

    'target_trainees_max' => [
        'errorCode' => 'TARGET_TRAINEES_MAX',
        'errorMessage' => 'This field exceeds the maximum allowed length.',
    ],

    'target_date_required' => [
        'errorCode' => 'TARGET_DATE_REQUIRED',
        'errorMessage' => 'This field is required.',
    ],

    'target_date_after_or_equal' => [
        'errorCode' => 'TARGET_DATE_AFTER_OR_EQUAL',
        'errorMessage' => 'The selected date must be in the future.',
    ],

    'remarks_max' => [
        'errorCode' => 'REMARKS_MAX',
        'errorMessage' => 'This field exceeds the maximum allowed length.',
    ],

    'action_batch_create_success' => [
    'messageCode' => 'ACTION_BATCH_CREATE_SUCCESS',
    'message' => 'Record created successfully.',
    ],
    
    'action_batch_create_error' => [
        'errorCode' => 'ACTION_BATCH_CREATE_ERROR',
        'errorMessage' => 'An error occurred while creating the record. Please try again.',
    ],
];
