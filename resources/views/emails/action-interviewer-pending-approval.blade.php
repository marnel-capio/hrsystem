@extends('emails.layouts.main')

@section('content')
    @php
        $applicantName = trim($application->applicant->first_name . ' ' . $application->applicant->last_name);

        $stage = match ((int) $interview->interview_type) {
            1 => 'Exam',
            2 => 'Initial Interview',
            3 => 'Final Interview',
            default => 'Assessment',
        };

        $assignmentText = match ((int) $interview->interview_type) {
            1 => 'You have been assigned as an exam conductor for an applicant. Your confirmation is required.',
            2 => 'You have been assigned as an interviewer for an applicant. Your confirmation is required.',
            3 => 'You have been assigned as an interviewer for an applicant. Your confirmation is required.',
            default => 'You have been assigned to an applicant. Your confirmation is required.',
        };
    @endphp

    <p style="font-size: 16px;">Good Day,</p>

    <p style="font-size: 16px; line-height: 1.6;">
        {{ $assignmentText }}
    </p>

    <p style="font-size: 16px; line-height: 1.6;">
        <strong>Applicant:</strong> {{ $applicantName }}<br>
        <strong>Stage:</strong> {{ $stage }}<br>
        <strong>Scheduled Date:</strong> {{ \Carbon\Carbon::parse($interview->scheduled_date)->format('F d, Y h:i A') }}
    </p>

    <p style="font-size: 16px; line-height: 1.6;">
        Please access the HR System using the link below to approve or decline this assignment:
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
