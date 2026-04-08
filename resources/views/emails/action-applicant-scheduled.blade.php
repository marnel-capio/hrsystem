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

            return [
                'stage' => $stageLabel,
                'datetime' => \Carbon\Carbon::parse($interview->scheduled_date)->format('F d, Y h:i A'),
                'key' => (int) $interview->interview_type . '|' . \Carbon\Carbon::parse($interview->scheduled_date)->format('Y-m-d H:i:s'),
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
        $intro = match($stageNames->first()) {
            'exam' => 'We are pleased to inform you that your exam schedule has been confirmed.',
            'initial interview' => 'We are pleased to inform you that your initial interview schedule has been confirmed.',
            'final interview' => 'We are pleased to inform you that your final interview schedule has been confirmed.',
            default => 'We are pleased to inform you that your schedule has been confirmed.',
        };
    } else {
        $intro = 'We are pleased to inform you that your assessment schedule has been confirmed.';
    }
@endphp

<p style="font-size: 16px;">Good Day {{ $applicantName }},</p>

<p style="font-size: 16px; line-height: 1.6;">
    {{ $intro }}
</p>

<p style="font-size: 16px; line-height: 1.6;">
    Please see the details of your scheduled assessment(s) below:
</p>

<ul style="font-size: 16px; line-height: 1.6;">
    @foreach ($uniqueSchedules as $schedule)
        <li>
            {{ $schedule['stage'] }} — {{ $schedule['datetime'] }}
        </li>
    @endforeach
</ul>

<p style="font-size: 16px; line-height: 1.6;">
    Kindly ensure your availability on the scheduled date(s).
</p>

<p style="font-size: 16px; line-height: 1.6;">
    You may view your application details here:<br>
    <a href="{{ $link }}" style="color: #0d6efd;">View Application</a>
</p>

<p style="font-size: 16px; line-height: 1.6;">
    Thank you,<br>
    <strong>AWS HR Team</strong>
</p>
@endsection
