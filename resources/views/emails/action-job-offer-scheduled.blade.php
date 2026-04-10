@extends('emails.layouts.main')

@section('content')
    @php
        $applicantName = trim($application->applicant->first_name . ' ' . $application->applicant->last_name);
    @endphp

    <p style="font-size: 16px;">Good Day,</p>

    <p style="font-size: 16px; line-height: 1.6;">
        A job offer has been scheduled for the following applicant:
    </p>

    <p style="font-size: 16px; line-height: 1.6;">
        <strong>Applicant:</strong> {{ $applicantName }}<br>
        <strong>Scheduled Date:</strong>
        {{ \Carbon\Carbon::parse($application->job_offer_schedule)->format('F d, Y h:i A') }}
    </p>

    <p style="font-size: 16px; line-height: 1.6;">
        Please review the application in the HR System for complete details.
    </p>

    <p style="font-size: 16px;">
        <a href="{{ $link }}" style="color: #0d6efd;">View Application</a>
    </p>

    <p style="font-size: 16px; line-height: 1.6;">
        Thank you,<br>
        <strong>{{ $senderName ?? 'AWS HR Team' }}</strong><br>
        <span style="font-size: 14px; color: #555;">
            {{ $senderRole ?? '' }}
        </span>
    </p>
@endsection
