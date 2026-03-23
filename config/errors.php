<?php

return [

    //UNIVERSAL ERROR MESSAGES

    // field max length exceeded
        'max_length_exceeded' => [
        'errorCode' => 'MAX_LENGTH_EXCEEDED',
        'errorMessage' => 'This field exceeds the maximum allowed length.',
    ],

    // field is required
    'field_required' => [
        'errorCode' => 'FIELD_REQUIRED',
        'errorMessage' => 'This is a required field.',
    ],
    
    // record created succesfully
    'record_created_successfully' => [
        'errorCode' => 'RECORD_CREATED_SUCCESSFULLY',
        'errorMessage' => 'Record created successfully.',
    ],

    // record creation failed
    'transaction_failed' => [
        'errorCode' => 'TRANSACTION_FAILED',
        'errorMessage' => 'An error occurred while creating the record. Please try again.',
    ],

    // record deleted succesfully
    'record_deleted_successfully' => [
        'errorCode' => 'RECORD_DELETED_SUCCESSFULLY',
        'errorMessage' => 'Record successfully deleted.',
    ],

    // record delete failed
    'record_deleted_failed' => [
        'errorCode' => 'RECORD_DELETED_FAILED',
        'errorMessage' => 'An error occurred while deleting the record. Please try again.												
',
    ],

    // record update success
    'record_updated_successfully' => [
        'errorCode' => 'RECORD_UPDATED_SUCCESSFULLY',
        'errorMessage' => 'Record updated successfully.												
',
    ],

    // record update failed
    'record_updated_failed' => [
        'errorCode' => 'RECORD_UPDATED_FAILED',
        'errorMessage' => 'An error occurred while updating the record. Please try again.												
',
    ],

    // unauthorized access
    'unauthorized' => [
        'errorCode' => 'UNAUTHORIZED',
        'errorMessage' => 'Access denied: You are not authorized to view this page.',
    ],

    // email address registered
    'email_taken' => [
    'errorCode' => 'EMAIL_TAKEN',
    'errorMessage' => 'This email address is already registered.',
    ],

    // passwords do not match
    'password_mismatch' => [
        'errorCode' => 'PASSWORD_MISMATCH',
        'errorMessage' => 'Passwords do not match.',
    ],
    
    // email sent success
    'email_sent_success' => [
        'errorCode' => 'EMAIL_SUCCESS',
        'errorMessage' => 'Email/s sent successfully',
    ],

    
     // email sent failed
    'email_sent_failed' => [
        'errorCode' => 'EMAIL_FAILED',
        'errorMessage' => 'An error occurred while sending the email/s. Please try again.',										
    ],

    // account is inactive
    'account_inactive' => [
    'errorCode' => 'ACCOUNT_INACTIVE',
    'errorMessage' => 'Your account is no longer active. Please check with your manager or admin.',
    ],




    // FOR RESOURCE SCHEDULE
    'wbs_end_before_start' => [
        'errorCode' => 'WBS_END_BEFORE_START',
        'errorMessage' => 'Start week cannot be after end week.',
    ],

    //FOR ACTION APPLICATIONS
        'successful_action_application_import' => [
        'errorCode' => 'SUCCESSFUL_ACTION_APPLICATION_IMPORT',
        'errorMessage' => 'The following applicants have been successfully uploaded:',
    ],

        'failed_action_application_import' => [
            'errorCode' => 'FAILED_ACTION_APPLICATION_IMPORT',
            'errorMessage' => 'An error occurred while uploading the following applicants:',
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
    'action_batch_unique' => [
        'errorCode' => 'ACTION_BATCH_UNIQUE',
        'errorMessage' => 'Action Batch already exists.',
    ],

    'target_date_after_or_equal' => [
        'errorCode' => 'TARGET_DATE_AFTER_OR_EQUAL',
        'errorMessage' => 'The selected date must be in the future.',
    ],

];
