<?php

return [

    // Universal message for all transaction failed
    'transaction_failed' => [
        'errorCode' => 'TRANSACTION_FAILED',
        'errorMessage' => 'An error occurred while creating the record. Please try again.',
    ],

    'update_failed' => [
        'errorCode' => 'UPDATE_FAILED',
        'errorMessage' => 'An error occurred while saving the record. Please try again.',
    ],

    'email_taken' => [
    'errorCode' => 'EMAIL_TAKEN',
    'errorMessage' => 'This email address is already registered.',
    ],

    'password_mismatch' => [
        'errorCode' => 'PASSWORD_MISMATCH',
        'errorMessage' => 'Passwords do not match.',
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

    // Universal message for all record deleted succesfully
    'record_deleted_successfully' => [
        'errorCode' => 'RECORD_DELETED_SUCCESSFULLY',
        'errorMessage' => 'Record successfully deleted.',
    ],

    // Universal message for all record deleted failed
    'record_deleted_failed' => [
        'errorCode' => 'RECORD_DELETED_FAILED',
        'errorMessage' => 'An error occurred while deleting the record. Please try again.												
',
    ],

    // Universal message for all record updated failed
    'record_updated_failed' => [
        'errorCode' => 'RECORD_UPDATED_FAILED',
        'errorMessage' => 'An error occurred while updating the record. Please try again.												
',
    ],

        // Universal message for all record updated success
    'record_updated_successfully' => [
        'errorCode' => 'RECORD_UPDATED_SUCCESSFULLY',
        'errorMessage' => 'Record updated successfully.												
',
    ],

    // Universal message for all required fields
    'field_required' => [
        'errorCode' => 'FIELD_REQUIRED',
        'errorMessage' => 'This is a required field.',
    ],

    
    // Universal message for all email sent success
    'email_sent_success' => [
        'errorCode' => 'EMAIL_SUCCESS',
        'errorMessage' => 'Email/s sent successfully',
    ],

    
     // Universal message for email sent failed
    'email_sent_failed' => [
        'errorCode' => 'EMAIL_FAILED',
        'errorMessage' => 'An error occurred while sending the email/s. Please try again.',										
    ],

    // ACCOUNT IS INACTIVE
    'account_inactive' => [
    'errorCode' => 'ACCOUNT_INACTIVE',
    'errorMessage' => 'Your account is no longer active. Please check with your manager or admin.',
    ],

    // RESOURCE SCHED REGISTER ERROR MSGS
    'wbs_end_before_start' => [
        'errorCode' => 'WBS_END_BEFORE_START',
        'errorMessage' => 'Start week cannot be after end week.',
    ],

    // FOR USER REGISTRATION
    'max_length_exceeded' => [
        'errorCode' => 'MAX_LENGTH_EXCEEDED',
        'errorMessage' => 'This field exceeds the maximum allowed length.',
    ],

    'password_complexity_failed' => [
        'errorCode' => 'PASSWORD_COMPLEXITY_FAILED',
        'errorMessage' => 'Password must contain at least 1 uppercase letter, 1 lowercase letter, 1 number, and 1 special character (!@#$%&*_)',
    ],

    'aws_email_required' => [
        'errorCode' => 'AWS_EMAIL_REQUIRED',
        'errorMessage' => 'The email address must be your AWS email address.',
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

    'user_updated_successfully' => [
    'errorCode' => 'USER_UPDATED_SUCCESSFULLY',
    'errorMessage' => 'Record updated successfully.',
],



    //ACTION BATCH
    'action_batch_unique' => [
        'errorCode' => 'ACTION_BATCH_UNIQUE',
        'errorMessage' => 'Action Batch already exists.',
    ],
    
    // TARGET DATE
    'target_date_after_or_equal' => [
        'errorCode' => 'TARGET_DATE_AFTER_OR_EQUAL',
        'errorMessage' => 'The selected date must be in the future.',
    ],
 
];  

