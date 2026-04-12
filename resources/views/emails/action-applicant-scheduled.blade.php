@extends('emails.layouts.main')

@section('content')
@php

    $applicantName = trim($application->applicant->first_name . ' ' . $application->applicant->last_name);

    $uniqueSchedules = $approvedInterviews
        ->map(function ($interview) {
            $stageLabel = match((int) $interview->interview_type) {
                1 => 'Exam',
                2 => 'Initial Interview',
                3 => 'Final Interview',
                default => 'Assessment',
            };

            $dateKey = \Carbon\Carbon::parse($interview->scheduled_date)->format('Y-m-d H:i:s');

            return [
                'stage' => $stageLabel,
                'datetime' => \Carbon\Carbon::parse($interview->scheduled_date)->format('F d, Y h:i A'),
                'key' => ((int) $interview->interview_type) . '|' . $dateKey,
            ];
        })
        ->unique('key')
        ->values();

    $stageNames = $uniqueSchedules
        ->pluck('stage')
        ->map(fn ($stage) => strtolower($stage))
        ->unique()
        ->values();

if ($stageNames->count() === 1) {
    $stage = $stageNames->first();
    $firstSchedule = $uniqueSchedules->first();
    $stage = $stageNames->first();

$venueValue = match($stage) {
    'exam' => $application->exam_venue,
    'initial interview' => $application->initial_interview_venue,
    'final interview' => $application->final_interview_venue,
    default => null,
};

$venueLabel = match((int) $venueValue) {
    1 => 'online',
    2 => 'face-to-face',
    default => '',
};

    $intro = match($stage) {
        'exam' => "We got your application and we would like to invite you to take the {$venueLabel} screening exam scheduled on <strong>{$firstSchedule['datetime']}</strong> .",
        'initial interview' => "We are pleased to inform you that you have passed our initial screening exam. We would like to invite you for your {$venueLabel} initial interview scheduled on <strong>{$firstSchedule['datetime']}</strong>",
        'final interview' => "We are pleased to inform you that you have passed our initial interview screening. We would like to invite you for your {$venueLabel} final interview scheduled <strong>{$firstSchedule['datetime']}</strong>",
        default => "We got your application and we would like to invite you for the scheduled assessment on {$firstSchedule['datetime']}.",
    };
} else {
    $intro = 'We got your application and we would like to invite you for the following scheduled assessments.';
}
@endphp

<p style="font-size: 16px;">Good day {{ $applicantName }},</p>

<p style="font-size: 16px; line-height: 1.6;">
{!! $intro !!}
</p>

@if ($stageNames->contains('exam'))
    <p style="font-size: 16px; line-height: 1.6; margin-top: 16px;">
        Here is the scope of the exams:
    </p>

    <ul style="font-size: 16px; line-height: 1.6;">
        <li>Aptitude Test I - Sequence/ Pattern Analysis (10mins)</li>
        <li>Aptitude Test II - Abstract Reasoning (15mins)</li>
        <li>Aptitude Test III - Problem Solving (30mins)</li>
        <li>General IT Exams (15mins)</li>
    </ul>

    <p style="font-size: 16px; line-height: 1.6; margin-top: 12px;">
        Here are some reminders that you should take note of:
    </p>

    <ul style="font-size: 16px; line-height: 1.6;">
        <li>Each part of the exam is time limited so please be on time.</li>
        <li>It would take at least 70 minutes to finish the exams.</li>
        <li>During the exams, you are required to turn on your camera.</li>
        <li>You will receive the exam links during the conference call.</li>
        <li>Please be at the meeting 30 mins early.</li>
    </ul>
@endif

<p style="font-size: 16px; line-height: 1.6;">
    Please confirm your attendance on the scheduled date.
</p>

<p style="font-size: 16px; line-height: 1.6;">
    Thank you and kind regards,<br>
    <strong>AWS HR Team</strong>
</p>
@endsection
