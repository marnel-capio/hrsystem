<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>ACTION Application Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #222;
            margin: 24px;
            font-size: 12px;
            line-height: 1.45;
        }

        h1, h2, h3, p {
            margin: 0;
        }

        .header {
            margin-bottom: 18px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }

        .header-table td {
            border: none;
            vertical-align: top;
            padding: 0;
        }

        .generated {
            color: #666;
            margin-top: 4px;
        }

        .applicant-name {
            font-size: 22px;
            font-weight: bold;
            margin-top: 14px;
            margin-bottom: 4px;
        }

        .applicant-photo {
            width: 120px;
            height: 120px;
            object-fit: cover;
        }

        .section {
            margin-bottom: 22px;
            page-break-inside: avoid;
        }

        .section-title {
            background: #0d6efd;
            color: #fff;
            padding: 8px 10px;
            font-weight: bold;
            font-size: 13px;
        }

        .box {
            border: 1px solid #d9d9d9;
            border-top: none;
            padding: 12px;
        }

        .summary-grid {
            width: 100%;
            border-collapse: separate;
            border-spacing: 10px 10px;
            margin: -10px;
        }

        .summary-card {
            border: 1px solid #d9d9d9;
            background: #fafafa;
            padding: 10px;
            border-radius: 4px;
        }

        .summary-label {
            font-size: 11px;
            color: #666;
            margin-bottom: 4px;
        }

        .summary-value {
            font-size: 13px;
            font-weight: bold;
            color: #111;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #d9d9d9;
            padding: 7px 8px;
            vertical-align: top;
            text-align: left;
        }

        th {
            background: #f3f4f6;
            font-weight: bold;
        }

        .label {
            width: 22%;
            font-weight: bold;
            background: #fafafa;
        }

        .muted {
            color: #666;
        }

        .comments-box {
            margin-top: 12px;
            border: 1px solid #d9d9d9;
            background: #fafafa;
            padding: 10px;
            min-height: 24px;
        }

        .subheading {
            font-weight: bold;
            margin-bottom: 8px;
            font-size: 12px;
        }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: bold;
        }

        .badge-green {
            background: #dcfce7;
            color: #166534;
        }

        .badge-red {
            background: #fee2e2;
            color: #991b1b;
        }

        .badge-yellow {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-blue {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .badge-gray {
            background: #e5e7eb;
            color: #374151;
        }

        .no-print {
            margin-bottom: 18px;
        }

        .print-btn {
            background: #0d6efd;
            color: white;
            border: none;
            padding: 10px 14px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            body {
                margin: 0;
            }
        }
    </style>
</head>
<body>
    @php
        $applicant = $application->applicant;

        $examResult = match((int) $application->exam_result) {
            1 => 'Pending',
            2 => 'Passed',
            3 => 'Failed',
            default => '-',
        };

        $examApplicationStatus = match((int) $application->exam_application_status) {
            1 => 'Pending',
            3 => '2nd Priority (P2)',
            4 => 'Done',
            5 => 'Passed',
            6 => 'Failed',
            default => '-',
        };

        $initialInterviewResult = match((int) $application->initial_interview_result) {
            1 => 'Pending',
            2 => 'Passed',
            3 => 'Failed',
            default => '-',
        };

        $initialInterviewStatus = match((int) $application->initial_interview_application_status) {
            1 => 'Pending',
            2 => 'Done',
            3 => 'Passed',
            4 => 'P2',
            5 => 'Failed',
            default => '-',
        };

        $finalInterviewResult = match((int) $application->final_interview_result) {
            1 => 'Pending',
            2 => 'Passed',
            3 => 'Failed',
            default => '-',
        };

        $finalInterviewStatus = match((int) $application->final_interview_application_status) {
            1 => 'Pending',
            2 => 'Done',
            3 => 'Passed',
            4 => 'P2',
            5 => 'Failed',
            default => '-',
        };

        $jobOfferStatus = match((int) $application->job_offer_status) {
            1 => 'Pending',
            2 => 'Done',
            3 => 'Accept',
            4 => 'Decline',
            5 => 'Withdraw',
            6 => 'Retracted',
            default => '-',
        };

        $interviewStatus = fn($status) => match((int) $status) {
            1 => 'Pending Approval',
            2 => 'Approved',
            3 => 'Declined',
            4 => 'Done',
            default => '-',
        };

        $stageLabel = fn($type) => match((int) $type) {
            1 => 'Exam',
            2 => 'Initial Interview',
            3 => 'Final Interview',
            default => '-',
        };

        $evaluationLabel = fn($result) => match((int) $result) {
            1 => 'Pending',
            2 => 'Passed',
            3 => 'Failed',
            default => '-',
        };

        $formatDate = function ($value) {
            return $value ? \Carbon\Carbon::parse($value)->format('F d, Y h:i A') : '-';
        };

        $badgeClass = function ($value) {
            $value = strtolower((string) $value);

            if (str_contains($value, 'pass') || str_contains($value, 'accept')) {
                return 'badge badge-green';
            }

            if (str_contains($value, 'fail') || str_contains($value, 'decline') || str_contains($value, 'retract')) {
                return 'badge badge-red';
            }

            if (str_contains($value, 'pending')) {
                return 'badge badge-yellow';
            }

            if (str_contains($value, 'done')) {
                return 'badge badge-blue';
            }

            return 'badge badge-gray';
        };
    @endphp

    <div class="no-print">
        <button class="print-btn" onclick="window.print()">Print / Save as PDF</button>
    </div>

    <table class="header-table">
        <tr>
            <td>
                <h1>ACTION Application Details</h1>
                <div class="generated">Generated on {{ now()->format('F d, Y h:i A') }}</div>

                <div class="applicant-name">
                    {{ $application->applicant->last_name }},
                    {{ $application->applicant->first_name }}
                    {{ $application->applicant->middle_name }}
                </div>
                <div>{{ $application->applicant->email_address }}</div>
            </td>

            <td style="width: 140px; text-align: right;">
                @if (!empty($application->upload_pic))
                    <img
                        src="{{ asset('storage/' . $application->upload_pic) }}"
                        alt="Applicant 2x2 Picture"
                        class="applicant-photo"
                    >
                @endif
            </td>
        </tr>
    </table>

    <div class="section">
        <div class="section-title">Applicant Information</div>
        <div class="box">
            <table>
                <tr>
                    <td class="label">Applicant Name</td>
                    <td>
                        {{ $applicant->last_name ?? '' }},
                        {{ $applicant->first_name ?? '' }}
                        {{ $applicant->middle_name ?? '' }}
                    </td>
                    <td class="label">Gender</td>
                    <td>
                        {{ match((int) ($applicant->gender ?? 0)) {
                            1 => 'Male',
                            2 => 'Female',
                            default => '-',
                        } }}
                    </td>
                </tr>
                <tr>
                    <td class="label">Email Address</td>
                    <td>{{ $applicant->email_address ?? '-' }}</td>
                    <td class="label">Age</td>
                    <td>{{ $applicant->age ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">School</td>
                    <td>{{ $applicant->school ?? '-' }}</td>
                    <td class="label">Degree</td>
                    <td>{{ $applicant->degree ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Other Degree</td>
                    <td>{{ $applicant->others_degree ?: '-' }}</td>
                    <td class="label">Expected Graduation</td>
                    <td>{{ $applicant->expected_graduation ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Address</td>
                    <td>{{ $applicant->address ?? '-' }}</td>
                    <td class="label">Batch</td>
                    <td>{{ $application->batch->action_batch ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Source Type</td>
                    <td>
                        {{ match((int) ($applicant->source_type ?? 0)) {
                            1 => 'Campus Recruitment',
                            2 => 'Academe Partner',
                            3 => 'Recruitment Portals',
                            4 => 'Employee Referral',
                            5 => 'Walk-in',
                            default => '-',
                        } }}
                    </td>
                    <td class="label">Source</td>
                    <td>{{ $applicant->other_source ?: ($applicant->source ?? '-') }}</td>
                </tr>
                <tr>
                    <td class="label">Awards / Recognition</td>
                    <td colspan="3">{{ $applicant->awards_recognition ?: '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Other Examinations / Certifications</td>
                    <td colspan="3">{{ $applicant->other_examination_certificate ?: '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Thesis / Project</td>
                    <td colspan="3">{{ $applicant->thesis_project ?: '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Extra-curricular Activities</td>
                    <td colspan="3">{{ $applicant->extra_curricular ?: '-' }}</td>
                </tr>

<tr>
    <td class="label">Programming Languages</td>
    <td colspan="3">
        @if($applicant->programmingLanguages && $applicant->programmingLanguages->count())
            {{ $applicant->programmingLanguages->pluck('program_language')->join(', ') }}
        @else
            -
        @endif
    </td>
</tr>

<tr>
    <td class="label">Technical Skills</td>
    <td colspan="3">
        @if($applicant->skills && $applicant->skills->count())
            {{ $applicant->skills->pluck('skill')->join(', ') }}
        @else
            -
        @endif
    </td>
</tr>

<tr>
    <td class="label">Japanese Language Background</td>
    <td colspan="3">
        @php
            $jpBackground = config('constants.japanese_backgrounds')[$applicant->japanese_background ?? 0] ?? '-';
            $jpLevel = config('constants.japanese_levels')[$applicant->japanese_level ?? 0] ?? null;
        @endphp

        {{ $jpBackground }}@if($jpLevel) / {{ $jpLevel }}@endif

        @if(!empty($applicant->background_remarks))
            — {{ $applicant->background_remarks }}
        @endif
    </td>
</tr>
                <tr>
                    <td class="label">General Remarks</td>
                    <td colspan="3">{{ $application->remarks ?: '-' }}</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Exam Details</div>
        <div class="box">
            <table class="summary-grid">
                <tr>
                    <td class="summary-card">
                        <div class="summary-label">Plan Date</div>
                        <div class="summary-value">{{ $formatDate($application->exam_plan_date) }}</div>
                    </td>
                    <td class="summary-card">
                        <div class="summary-label">Actual Date</div>
                        <div class="summary-value">{{ $formatDate($application->exam_actual_date) }}</div>
                    </td>
                    <td class="summary-card">
                        <div class="summary-label">Venue</div>
                        <div class="summary-value">{{ $examVenues[$application->exam_venue] ?? '-' }}</div>
                    </td>
                    <td class="summary-card">
                        <div class="summary-label">Result</div>
                        <div class="summary-value">
                            <span class="{{ $badgeClass($examResult) }}">{{ $examResult }}</span>
                        </div>
                    </td>
                </tr>
            </table>

            <div class="subheading" style="margin-top: 10px;">ATPP Breakdown</div>
            <table>
                <thead>
                    <tr>
                        <th>Part</th>
                        <th>Correct</th>
                        <th>Wrong</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Part I - Sequence / Pattern Analysis</td>
                        <td>{{ $application->exam_atpp_part1_correct ?? '-' }}</td>
                        <td>{{ $application->exam_atpp_part1_wrong ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Part II - Abstract Reasoning</td>
                        <td>{{ $application->exam_atpp_part2_correct ?? '-' }}</td>
                        <td>{{ $application->exam_atpp_part2_wrong ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Part III - Problem Solving</td>
                        <td>{{ $application->exam_atpp_part3_correct ?? '-' }}</td>
                        <td>{{ $application->exam_atpp_part3_wrong ?? '-' }}</td>
                    </tr>
                </tbody>
            </table>

            <div class="subheading" style="margin-top: 12px;">Scores Summary</div>
            <table>
                <tr>
                    <td class="label">ATPP Final Result</td>
                    <td>{{ $application->exam_atpp_result ?? '-' }}</td>
                    <td class="label">GIT Result</td>
                    <td>{{ $application->exam_git_result ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Programming Result</td>
                    <td>{{ $application->exam_prg_result ?? '-' }}</td>
                    <td class="label">Application Status</td>
                    <td>
                        <span class="{{ $badgeClass($examApplicationStatus) }}">{{ $examApplicationStatus }}</span>
                    </td>
                </tr>
            </table>

            <div class="subheading" style="margin-top: 12px;">Comments</div>
            <div class="comments-box">{{ $application->exam_remarks ?: 'No comments' }}</div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Initial Interview Details</div>
        <div class="box">
            <table class="summary-grid">
                <tr>
                    <td class="summary-card">
                        <div class="summary-label">Plan Date</div>
                        <div class="summary-value">{{ $formatDate($application->initial_interview_plan_date) }}</div>
                    </td>
                    <td class="summary-card">
                        <div class="summary-label">Actual Date</div>
                        <div class="summary-value">{{ $formatDate($application->initial_interview_actual_date) }}</div>
                    </td>
                    <td class="summary-card">
                        <div class="summary-label">Venue</div>
                        <div class="summary-value">{{ $examVenues[$application->initial_interview_venue] ?? '-' }}</div>
                    </td>
                    <td class="summary-card">
                        <div class="summary-label">Final Score</div>
                        <div class="summary-value">{{ $application->initial_interview_final ?? '-' }}</div>
                    </td>
                </tr>
            </table>

            <table style="margin-top: 12px;">
                <tr>
                    <td class="label">Result</td>
                    <td><span class="{{ $badgeClass($initialInterviewResult) }}">{{ $initialInterviewResult }}</span></td>
                    <td class="label">Application Status</td>
                    <td><span class="{{ $badgeClass($initialInterviewStatus) }}">{{ $initialInterviewStatus }}</span></td>
                </tr>
            </table>

            <div class="subheading" style="margin-top: 12px;">Comments</div>
            <div class="comments-box">{{ $application->initial_interview_remarks ?: 'No comments' }}</div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Final Interview Details</div>
        <div class="box">
            <table class="summary-grid">
                <tr>
                    <td class="summary-card">
                        <div class="summary-label">Interview Date</div>
                        <div class="summary-value">{{ $formatDate($application->final_interview_date) }}</div>
                    </td>
                    <td class="summary-card">
                        <div class="summary-label">Final Score</div>
                        <div class="summary-value">{{ $application->final_interview_final ?? '-' }}</div>
                    </td>
                    <td class="summary-card">
                        <div class="summary-label">Result</div>
                        <div class="summary-value"><span class="{{ $badgeClass($finalInterviewResult) }}">{{ $finalInterviewResult }}</span></div>
                    </td>
                    <td class="summary-card">
                        <div class="summary-label">Application Status</div>
                        <div class="summary-value"><span class="{{ $badgeClass($finalInterviewStatus) }}">{{ $finalInterviewStatus }}</span></div>
                    </td>
                </tr>
            </table>

            <div class="subheading" style="margin-top: 12px;">Approved Final Interviewers</div>
            <table>
                <thead>
                    <tr>
                        <th>Interviewer</th>
                        <th>Role</th>
                        <th>Score</th>
                        <th>Result</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse(($finalInterviewAssignments ?? []) as $assignment)
                        <tr>
                            <td>{{ $assignment['name'] ?? '-' }}</td>
                            <td>{{ $assignment['role_label'] ?? '-' }}</td>
                            <td>{{ $assignment['score'] ?? '-' }}</td>
                            <td>
                                @php $evalLabel = $evaluationLabel($assignment['evaluation_result'] ?? null); @endphp
                                <span class="{{ $badgeClass($evalLabel) }}">{{ $evalLabel }}</span>
                            </td>
                            <td>{{ $assignment['evaluation_remarks'] ?: '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center;">No final interviewers assigned.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="subheading" style="margin-top: 12px;">Comments</div>
            <div class="comments-box">{{ $application->final_interview_remarks ?: 'No comments' }}</div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Job Offer Details</div>
        <div class="box">
            <table class="summary-grid">
                <tr>
                    <td class="summary-card">
                        <div class="summary-label">Schedule</div>
                        <div class="summary-value">{{ $formatDate($application->job_offer_schedule) }}</div>
                    </td>
                    <td class="summary-card">
                        <div class="summary-label">Status</div>
                        <div class="summary-value"><span class="{{ $badgeClass($jobOfferStatus) }}">{{ $jobOfferStatus }}</span></div>
                    </td>
                </tr>
            </table>

            <div class="subheading" style="margin-top: 12px;">Comments</div>
            <div class="comments-box">{{ $application->job_offer_remarks ?: 'No comments' }}</div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Interviewers & Conductors</div>
        <div class="box">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Stage</th>
                        <th>Scheduled Date</th>
                        <th>Status</th>
                        <th>Decline Reason</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($interviews as $interview)
                        <tr>
                            <td>{{ $interview['name'] }}</td>
                            <td>{{ $interview['role_label'] }}</td>
                            <td>{{ $stageLabel($interview['interview_type']) }}</td>
                            <td>{{ $formatDate($interview['scheduled_date']) }}</td>
                            <td>{{ $interviewStatus($interview['status']) }}</td>
                            <td>{{ $interview['decline_reason'] ?: '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center;">No interviewers or conductors assigned.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        window.onload = function () {
            window.print();
        };
    </script>
</body>
</html>
