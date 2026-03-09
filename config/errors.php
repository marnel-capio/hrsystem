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
        'errorCode' => 'FIELD_REQUIRED',
        'errorMessage' => 'This field is required.',
    ],

    // ACCOUNT IS INACTIVE
    'account_inactive' => [
    'errorCode' => 'ACCOUNT_INACTIVE',
    'errorMessage' => 'Your account is no longer active. Please check with your manager or admin.',
    ],

    // RESOURCE SCHED REGISTER ERROR MSGS
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

];
