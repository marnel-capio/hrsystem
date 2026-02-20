<?php

return [

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


    //RESOURCE SCHED REGISTER ERROR MSGS
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


];