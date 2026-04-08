@extends('emails.layouts.main')

@section('content')
@php
    $applicantName = trim($application->applicant->first_name . ' ' . $application->applicant->last_name);

    $resultText = match(strtolower($failedStage)) {
        'exam' => 'We regret to inform you that you did not pass the exam stage of the application process.',
        'initial interview' => 'We regret to inform you that you did not pass the initial interview stage of the application process.',
        'final interview' => 'We regret to inform you that you did not pass the final interview stage of the application process.',
        default => 'We regret to inform you that you did not pass this stage of the application process.',
    };
@endphp

<p style="font-size: 16px;">Good Day {{ $applicantName }},</p>

<p style="font-size: 16px; line-height: 1.6;">
    Thank you for your interest in applying with <strong>Advanced World Solutions</strong>.
</p>

<p style="font-size: 16px; line-height: 1.6;">
    {{ $resultText }}
</p>

<p style="font-size: 16px; line-height: 1.6;">
    We sincerely appreciate the time and effort you invested in the application process.
</p>

<p style="font-size: 16px; line-height: 1.6;">
    Thank you,<br>
    <strong>AWS HR Team</strong>
</p>
@endsection
