@extends('emails.layouts.main')

@section('content')
    @php
        $applicantName = $applicantName ?? 'Applicant';
        $venue = $venue ?? '';
        $stage = $stage ?? 'Assessment';
        $scheduledDate = $scheduledDate ?? '';
    @endphp

    <p style="font-size: 16px;">Good day {{ $applicantName }},</p>

    <p style="font-size: 16px; line-height: 1.6;">
        We got your application and we would like to invite you to take the {{ $venue }} {{ $stage }} scheduled on <strong>{{ $scheduledDate }}</strong>.
    </p>

    @if (strtolower($stage) === 'exam')
        <p style="font-size: 16px; line-height: 1.6; margin-top: 16px;">
            Here is the scope of the exams:
        </p>

        <ul style="font-size: 16px; line-height: 1.6;">
            <li>Aptitude Test I - Sequence/ Pattern Analysis (10mins)</li>
            <li>Aptitude Test II - Abstract Reasoning (15mins)</li>
            <li>Aptitude Test III - Problem Solving (30mins)</li>
            <li>Technical Exams will proceed after and will take 30mins.</li>
        </ul>

        <p style="font-size: 16px; line-height: 1.6; margin-top: 12px;">
            Here are some reminders that you should take note of:
        </p>

        <ul style="font-size: 16px; line-height: 1.6;">
            <li>Each part of the exam is time limited so please be on time.</li>
            <li>It would take at least 85 minutes to finish the exams.</li>

            @if (strtolower($venue) === 'online')
                <li>During the exams, you are required to turn on your camera (for online exam).</li>
                <li>You will receive the exam links during the conference call.</li>
            @endif

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