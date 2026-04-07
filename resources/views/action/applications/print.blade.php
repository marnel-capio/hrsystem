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
        }

        h1, h2 {
            margin: 0 0 10px 0;
        }

        .header {
            margin-bottom: 20px;
        }

        .section {
            margin-bottom: 24px;
            page-break-inside: avoid;
        }

        .section-title {
            background: #0d6efd;
            color: #fff;
            padding: 8px 10px;
            font-weight: bold;
            font-size: 13px;
            margin-bottom: 0;
        }

        .box {
            border: 1px solid #d9d9d9;
            padding: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #d9d9d9;
            padding: 6px 8px;
            vertical-align: top;
            text-align: left;
        }

        th {
            background: #f3f4f6;
        }

        .label {
            width: 22%;
            font-weight: bold;
            background: #fafafa;
        }

        .muted {
            color: #666;
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

        $initialInterviewResult = match((int) $application->initial_interview_result) {
            1 => 'Pending',
            2 => 'Passed',
            3 => 'Failed',
            default => '-',
        };

        $finalInterviewResult = match((int) $application->final_interview_result) {
            1 => 'Pending',
            2 => 'Passed',
            3 => 'Failed',
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

        $formatDate = function ($value) {
            return $value ? \Carbon\Carbon::parse($value)->format('F d, Y h:i A') : '-';
        };
    @endphp

    <div class="no-print">
        <button class="print-btn" onclick="window.print()">Print / Save as PDF</button>
    </div>

    <div class="header">
        <h1>ACTION Application Details</h1>
        <div class="muted">Generated on {{ now()->format('F d, Y h:i A') }}</div>
    </div>

<div class="section">
    <div class="section-title">Applicant Information</div>
    <div class="box">
        <table>
            <tr>
                <td class="label">Full Name</td>
                <td>
                    {{ $applicant->last_name ?? '' }},
                    {{ $applicant->first_name ?? '' }}
                    {{ $applicant->middle_name ?? '' }}
                </td>

                <td class="label">Email Address</td>
                <td>{{ $applicant->email_address ?? '-' }}</td>
            </tr>

            <tr>
                <td class="label">Contact Number</td>
                <td>{{ $applicant->contact_number ?? '-' }}</td>

                <td class="label">Age</td>
                <td>{{ $applicant->age ?? '-' }}</td>
            </tr>

            <tr>
                <td class="label">Degree</td>
                <td>{{ $applicant->degree ?? '-' }}</td>

                <td class="label">Other Degree</td>
                <td>{{ $applicant->others_degree ?? '-' }}</td>
            </tr>

            <tr>
                <td class="label">School</td>
                <td>{{ $applicant->school ?? '-' }}</td>

                <td class="label">Course</td>
                <td>{{ $applicant->course ?? '-' }}</td>
            </tr>

            <tr>
                <td class="label">Batch</td>
                <td>{{ $application->batch->action_batch ?? '-' }}</td>

                <td class="label">Application Status</td>
                <td>{{ $application->remarks ?: '-' }}</td>
            </tr>
        </table>
    </div>
</div>

    <div class="section">
        <div class="section-title">Exam Details</div>
        <div class="box">
            <table>
                <tr>
                    <td class="label">Plan Date</td>
                    <td>{{ $formatDate($application->exam_plan_date) }}</td>
                    <td class="label">Actual Date</td>
                    <td>{{ $formatDate($application->exam_actual_date) }}</td>
                </tr>
                <tr>
                    <td class="label">Venue</td>
                    <td>{{ $examVenues[$application->exam_venue] ?? '-' }}</td>
                    <td class="label">Result</td>
                    <td>{{ $examResult }}</td>
                </tr>
                <tr>
                    <td class="label">ATPP Result</td>
                    <td>{{ $application->exam_atpp_result ?? '-' }}</td>
                    <td class="label">GIT Result</td>
                    <td>{{ $application->exam_git_result ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Programming Result</td>
                    <td>{{ $application->exam_prg_result ?? '-' }}</td>
                    <td class="label">Remarks</td>
                    <td>{{ $application->exam_remarks ?: '-' }}</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Initial Interview Details</div>
        <div class="box">
            <table>
                <tr>
                    <td class="label">Plan Date</td>
                    <td>{{ $formatDate($application->initial_interview_plan_date) }}</td>
                    <td class="label">Actual Date</td>
                    <td>{{ $formatDate($application->initial_interview_actual_date) }}</td>
                </tr>
                <tr>
                    <td class="label">Venue</td>
                    <td>{{ $examVenues[$application->initial_interview_venue] ?? '-' }}</td>
                    <td class="label">Final Score</td>
                    <td>{{ $application->initial_interview_final ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Result</td>
                    <td>{{ $initialInterviewResult }}</td>
                    <td class="label">Remarks</td>
                    <td>{{ $application->initial_interview_remarks ?: '-' }}</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Final Interview Details</div>
        <div class="box">
            <table>
                <tr>
                    <td class="label">Interview Date</td>
                    <td>{{ $formatDate($application->final_interview_date) }}</td>
                    <td class="label">Final Score</td>
                    <td>{{ $application->final_interview_final ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">SF Score</td>
                    <td>{{ $application->final_interview_sf ?? '-' }}</td>
                    <td class="label">IB Score</td>
                    <td>{{ $application->final_interview_ib ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">RV Score</td>
                    <td>{{ $application->final_interview_rv ?? '-' }}</td>
                    <td class="label">MA Score</td>
                    <td>{{ $application->final_interview_ma ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Result</td>
                    <td>{{ $finalInterviewResult }}</td>
                    <td class="label">Remarks</td>
                    <td>{{ $application->final_interview_remarks ?: '-' }}</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Job Offer Details</div>
        <div class="box">
            <table>
                <tr>
                    <td class="label">Schedule</td>
                    <td>{{ $formatDate($application->job_offer_schedule) }}</td>
                    <td class="label">Status</td>
                    <td>{{ $jobOfferStatus }}</td>
                </tr>
                <tr>
                    <td class="label">Remarks</td>
                    <td colspan="3">{{ $application->job_offer_remarks ?: '-' }}</td>
                </tr>
            </table>
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
