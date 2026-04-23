<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Intermediate Application Details</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
            color: #222;
            margin: 16px;
            font-size: 11px;
            line-height: 1.3;
        }
        h1 { font-size: 18px; margin-bottom: 2px; }
        h2 { font-size: 13px; }
        .header-table { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        .header-table td { border: none; vertical-align: top; padding: 0; }
        .generated { color: #666; font-size: 10px; }
        .applicant-name { font-size: 16px; font-weight: bold; margin-top: 8px; }
        .applicant-photo { width: 90px; height: 90px; object-fit: cover; }
        .section { margin-bottom: 14px; page-break-inside: avoid; }
        .section-title {
            background: #2f359e; color: #fff;
            padding: 5px 8px; font-weight: bold; font-size: 11px;
        }
        .box { border: 1px solid #d9d9d9; border-top: none; padding: 8px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #d9d9d9; padding: 4px 5px; vertical-align: top; text-align: left; }
        th { background: #f3f4f6; font-weight: bold; }
        .label { width: 18%; font-weight: bold; background: #fafafa; }
        .comments-box { margin-top: 6px; border: 1px solid #d9d9d9; background: #fafafa; padding: 6px; }
        .subheading { font-weight: bold; margin: 8px 0 4px; font-size: 11px; }
        .badge {
            display: inline-block; padding: 2px 6px; border-radius: 999px;
            font-size: 10px; font-weight: bold;
        }
        .badge-green { background: #dcfce7; color: #166534; }
        .badge-red { background: #fee2e2; color: #991b1b; }
        .badge-yellow { background: #fef3c7; color: #92400e; }
        .badge-blue { background: #dbeafe; color: #1d4ed8; }
        .badge-purple { background: #f3e8ff; color: #6b21a8; }
        .badge-gray { background: #e5e7eb; color: #374151; }
        .skills-list { display: flex; flex-wrap: wrap; gap: 5px; }
        .skill-tag { background: #e5e7eb; padding: 2px 8px; border-radius: 999px; font-size: 10px; }
        .two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
        .compact-table td, .compact-table th { padding: 3px 4px; }
        .no-print { margin-bottom: 12px; }
        .print-btn { background: #2f359e; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-size: 12px; }
        @media print {
            .no-print { display: none !important; }
            body { margin: 8px; }
        }
    </style>
</head>
<body>
    @php
        $applicant = $application->intermediateApplicant ?? null;
        
        $paperScreeningStatus = match((int) $application->paper_screening_status) {
            1 => 'Pending', 2 => 'Done', 3 => 'Passed', 4 => 'P2', 5 => 'Failed', default => '-',
        };
        $examResult = match((int) $application->exam_result) {
            1 => 'Pending', 2 => 'Passed', 3 => 'Failed', default => '-',
        };
        $examApplicationStatus = match((int) $application->exam_application_status) {
            1 => 'Pending', 2 => 'Done', 3 => 'Passed', 4 => 'P2', 5 => 'Failed', default => '-',
        };
        $initialInterviewResult = match((int) $application->initial_interview_result) {
            1 => 'Pending', 2 => 'Passed', 3 => 'Failed', default => '-',
        };
        $initialInterviewStatus = match((int) $application->initial_interview_application_status) {
            1 => 'Pending', 2 => 'Done', 3 => 'Passed', 4 => 'P2', 5 => 'Failed', default => '-',
        };
        $finalInterviewResult = match((int) $application->final_interview_result) {
            1 => 'Pending', 2 => 'Passed', 3 => 'Failed', default => '-',
        };
        $finalInterviewStatus = match((int) $application->final_interview_application_status) {
            1 => 'Pending', 2 => 'Done', 3 => 'Passed', 4 => 'P2', 5 => 'Failed', default => '-',
        };
        $jobOfferStatus = match((int) $application->job_offer_status) {
            1 => 'Pending', 2 => 'Done', 3 => 'Accept', 4 => 'Decline', 5 => 'Withdraw', 6 => 'Retracted', default => '-',
        };
        $interviewStatus = fn($s) => match((int) $s) {
            1 => 'Pending', 2 => 'Approved', 3 => 'Declined', 4 => 'Done', default => '-',
        };
        $stageLabel = fn($t) => match((int) $t) {
            1 => 'Exam', 2 => 'Initial', 3 => 'Final', default => '-',
        };
        $appStageLabel = match((int) $application->application_stage) {
            1 => 'New',
            2 => 'For Exam',
            3 => 'For Initial Interview',
            4 => 'For Final Interview',
            5 => 'For Job Offer',
            6 => 'Failed',
            default => '-',
        };
        $evaluationLabel = fn($r) => match((int) $r) {
            1 => 'Pending', 2 => 'Passed', 3 => 'Failed', default => '-',
        };
        $formatDate = fn($v) => $v ? \Carbon\Carbon::parse($v)->format('M d, Y h:i A') : '-';
        $badgeClass = function ($v) {
            $v = strtolower((string) $v);
            if (str_contains($v, 'pass') || str_contains($v, 'accept') || str_contains($v, 'done')) return 'badge badge-green';
            if (str_contains($v, 'fail') || str_contains($v, 'decline')) return 'badge badge-red';
            if (str_contains($v, 'pending')) return 'badge badge-yellow';
            if (str_contains($v, 'p2') || str_contains($v, '2nd')) return 'badge badge-purple';
            return 'badge badge-gray';
        };
    @endphp

    <div class="no-print">
        <button class="print-btn" onclick="window.print()">Print / Save as PDF</button>
    </div>

    <table class="header-table">
        <tr>
            <td>
                <h1>Intermediate Application</h1>
                <div class="generated">{{ now()->format('M d, Y h:i A') }}</div>
                <div class="applicant-name">{{ $applicant->last_name ?? '' }}, {{ $applicant->first_name ?? '' }} {{ $applicant->middle_name ?? '' }}</div>
                <div>{{ $applicant->email_address ?? '-' }} | {{ $applicant->contact_no ?? '-' }}</div>
            </td>
            <td style="width: 100px; text-align: right;">
                @if (!empty($application->upload_pic))
                    @php $picPath = str_starts_with($application->upload_pic, 'pictures/') ? $application->upload_pic : 'pictures/' . $application->upload_pic; @endphp
                    <img src="{{ asset('storage/' . $picPath) }}" alt="Photo" class="applicant-photo">
                @endif
            </td>
        </tr>
    </table>

    <!-- Basic Info + Screening (2 columns) -->
    <div class="two-col">
        <div class="section">
            <div class="section-title">Basic Information</div>
            <div class="box">
                <table class="compact-table">
                    <tr><td class="label">Project</td><td>{{ $projectName ?? '-' }}</td></tr>
                    <tr><td class="label">Position</td><td>{{ $application->position ?? '-' }}</td></tr>
                    <tr><td class="label">Stage</td><td><span class="{{ $badgeClass($appStageLabel) }}">{{ $appStageLabel }}</span></td></tr>
                    <tr><td class="label">Paper Screening</td><td><span class="{{ $badgeClass($paperScreeningStatus) }}">{{ $paperScreeningStatus }}</span></td></tr>
                </table>
            </div>
        </div>
        <div class="section">
            <div class="section-title">Screening Questions</div>
            <div class="box">
                <table class="compact-table">
                    <tr><td>1. Applied before?</td><td>{{ $application->answer_q1 ? 'Yes' : 'No' }}</td></tr>
                    <tr><td>2. Friends/relatives in AWS?</td><td>{{ $application->answer_q2 ? 'Yes' : 'No' }}</td></tr>
                    <tr><td>3. Worked in AWS?</td><td>{{ $application->answer_q3 ? 'Yes' : 'No' }}</td></tr>
                    <tr><td>4. Will travel?</td><td>{{ $application->answer_q4 ? 'Yes' : 'No' }}</td></tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Availability & Compensation (2 columns) -->
    <div class="two-col">
        <div class="section">
            <div class="section-title">Preferences</div>
            <div class="box">
                <table class="compact-table">
                    <tr><td class="label">Available</td><td>{{ $application->availability_date ?: '-' }}</td></tr>
                    <tr><td class="label">Work Pref</td><td>{{ $application->work_preference ?: '-' }}</td></tr>
                    <tr><td class="label">Desired Salary</td><td>{{ $application->desired_salary_range ?: '-' }}</td></tr>
                    <tr><td class="label">Target Co</td><td>{{ $application->targeted_company ?: '-' }}</td></tr>
                </table>
            </div>
        </div>
        <div class="section">
            <div class="section-title">Compensation</div>
            <div class="box">
                <table class="compact-table">
                    <tr><td class="label">Basic Pay</td><td>{{ $application->basic_pay ?: '-' }}</td></tr>
                    <tr><td class="label">Bonuses</td><td>{{ $application->bonuses ?: '-' }}</td></tr>
                    <tr><td class="label">Allowances</td><td>{{ $application->allowances ?: '-' }}</td></tr>
                    <tr><td class="label">HMO/Leaves</td><td>{{ $application->hmo ?: '-' }} / {{ $application->leaves ?: '-' }}</td></tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Skills & Work Experience (2 columns if both exist) -->
    @if(!empty($skills) && count($skills) > 0)
    <div class="section">
        <div class="section-title">Skills</div>
        <div class="box">
            <div class="skills-list">
                @foreach($skills as $skill)
                    <span class="skill-tag">{{ $skill['skill'] ?? '-' }}</span>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    @if(!empty($workExperiences) && count($workExperiences) > 0)
    <div class="section">
        <div class="section-title">Work Experience</div>
        <div class="box">
            @foreach($workExperiences as $index => $exp)
                <div style="margin-bottom: {{ $index < count($workExperiences) - 1 ? '10px' : '0' }};">
                    <div class="subheading">{{ $exp['employer'] ?? '-' }} — {{ $exp['job_title'] ?? '-' }}</div>
                    <table class="compact-table">
                        <tr><td class="label">Period</td><td>{{ $exp['date_employed'] ?? '-' }}</td></tr>
                        <tr><td class="label">Supervisor</td><td>{{ $exp['name_supervisor'] ?? '-' }}</td></tr>
                        <tr><td class="label">Description</td><td>{{ $exp['work_description'] ?? '-' }}</td></tr>
                    </table>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Exam Details -->
    <div class="section">
        <div class="section-title">Exam Details</div>
        <div class="box">
            <table class="compact-table">
                <tr>
                    <td class="label">Plan Date</td><td>{{ $formatDate($application->exam_plan_date) }}</td>
                    <td class="label">Actual Date</td><td>{{ $formatDate($application->exam_actual_date) }}</td>
                </tr>
                <tr>
                    <td class="label">Venue</td><td>{{ $examVenues[$application->exam_venue] ?? '-' }}</td>
                    <td class="label">Result</td><td><span class="{{ $badgeClass($examResult) }}">{{ $examResult }}</span></td>
                </tr>
                <tr>
                    <td class="label">ATPP Result</td><td>{{ $application->exam_atpp_result ?? '-' }}</td>
                    <td class="label">Tech Result</td><td>{{ $application->exam_tech_result ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">App Status</td><td colspan="3"><span class="{{ $badgeClass($examApplicationStatus) }}">{{ $examApplicationStatus }}</span></td>
                </tr>
            </table>
            @if($application->exam_remarks)
            <div class="subheading">Comments</div>
            <div class="comments-box">{{ $application->exam_remarks }}</div>
            @endif
        </div>
    </div>

    <!-- Initial & Final Interview (2 columns) -->
    <div class="two-col">
        <div class="section">
            <div class="section-title">Initial Interview</div>
            <div class="box">
                <table class="compact-table">
                    <tr><td class="label">Plan</td><td>{{ $formatDate($application->initial_interview_plan_date) }}</td></tr>
                    <tr><td class="label">Actual</td><td>{{ $formatDate($application->initial_interview_actual_date) }}</td></tr>
                    <tr><td class="label">Venue</td><td>{{ $examVenues[$application->initial_interview_venue] ?? '-' }}</td></tr>
                    <tr><td class="label">Score</td><td>{{ $application->initial_interview_final ?? '-' }}</td></tr>
                    <tr><td class="label">Result</td><td><span class="{{ $badgeClass($initialInterviewResult) }}">{{ $initialInterviewResult }}</span></td></tr>
                    <tr><td class="label">Status</td><td><span class="{{ $badgeClass($initialInterviewStatus) }}">{{ $initialInterviewStatus }}</span></td></tr>
                </table>
                @php $initialInts = collect($interviews ?? [])->filter(fn($i) => ($i['interview_type'] ?? 0) == 2); @endphp
                @if(count($initialInts) > 0)
                <div class="subheading">Interviewers</div>
                @foreach($initialInts as $ix)
                    <div style="margin-bottom: 4px; padding-bottom: 4px; border-bottom: 1px dashed #ddd;">
                        <strong>{{ $ix['name'] ?? '-' }}</strong> ({{ $ix['role_label'] ?? '-' }})<br>
                        Score: {{ $ix['evaluation_score'] ?? '-' }} | 
                        <span class="{{ $badgeClass($evaluationLabel($ix['evaluation_results'] ?? null)) }}">{{ $evaluationLabel($ix['evaluation_results'] ?? null) }}</span>
                        @if($ix['evaluation_remarks'])<br><small>{{ $ix['evaluation_remarks'] }}</small>@endif
                    </div>
                @endforeach
                @endif
                @if($application->initial_interview_remarks)
                <div class="subheading">Comments</div>
                <div class="comments-box">{{ $application->initial_interview_remarks }}</div>
                @endif
            </div>
        </div>
        <div class="section">
            <div class="section-title">Final Interview</div>
            <div class="box">
                <table class="compact-table">
                    <tr><td class="label">Date</td><td>{{ $formatDate($application->final_interview_date) }}</td></tr>
                    <tr><td class="label">Score</td><td>{{ $application->final_interview_final ?? '-' }}</td></tr>
                    <tr><td class="label">Result</td><td><span class="{{ $badgeClass($finalInterviewResult) }}">{{ $finalInterviewResult }}</span></td></tr>
                    <tr><td class="label">Status</td><td><span class="{{ $badgeClass($finalInterviewStatus) }}">{{ $finalInterviewStatus }}</span></td></tr>
                </table>
                @php $finalInts = collect($interviews ?? [])->filter(fn($i) => ($i['interview_type'] ?? 0) == 3); @endphp
                @if(count($finalInts) > 0)
                <div class="subheading">Interviewers</div>
                @foreach($finalInts as $ix)
                    <div style="margin-bottom: 4px; padding-bottom: 4px; border-bottom: 1px dashed #ddd;">
                        <strong>{{ $ix['name'] ?? '-' }}</strong> ({{ $ix['role_label'] ?? '-' }})<br>
                        Score: {{ $ix['evaluation_score'] ?? '-' }} | 
                        <span class="{{ $badgeClass($evaluationLabel($ix['evaluation_results'] ?? null)) }}">{{ $evaluationLabel($ix['evaluation_results'] ?? null) }}</span>
                        @if($ix['evaluation_remarks'])<br><small>{{ $ix['evaluation_remarks'] }}</small>@endif
                    </div>
                @endforeach
                @endif
                @if($application->final_interview_remarks)
                <div class="subheading">Comments</div>
                <div class="comments-box">{{ $application->final_interview_remarks }}</div>
                @endif
            </div>
        </div>
    </div>

    <!-- Job Offer & Additional Info (2 columns) -->
    <div class="two-col">
        <div class="section">
            <div class="section-title">Job Offer</div>
            <div class="box">
                <table class="compact-table">
                    <tr><td class="label">Schedule</td><td>{{ $formatDate($application->job_offer_schedule) }}</td></tr>
                    <tr><td class="label">Status</td><td><span class="{{ $badgeClass($jobOfferStatus) }}">{{ $jobOfferStatus }}</span></td></tr>
                </table>
                @if($application->job_offer_remarks)
                <div class="subheading">Comments</div>
                <div class="comments-box">{{ $application->job_offer_remarks }}</div>
                @endif
            </div>
        </div>
        <div class="section">
            <div class="section-title">Additional Information</div>
            <div class="box">
                <table class="compact-table">
                    <tr><td class="label">General Remarks</td><td>{{ $application->remarks ?: '-' }}</td></tr>
                    @if(!empty($application->reason_for_decline))
                    <tr><td class="label">Decline Reason</td><td>{{ $application->reason_for_decline }}</td></tr>
                    @endif
                    @if(!empty($application->parked_to))
                    <tr><td class="label">Parked To</td><td>{{ $application->parked_to }}</td></tr>
                    @endif
                    @if(!empty($application->aws_rank))
                    <tr><td class="label">AWS Rank</td><td>{{ $application->aws_rank }}</td></tr>
                    @endif
                    @if(!empty($application->aws_start_date))
                    <tr><td class="label">AWS Start</td><td>{{ $formatDate($application->aws_start_date) }}</td></tr>
                    @endif
                </table>
            </div>
        </div>
    </div>

    <script>window.onload = function () { window.print(); };</script>
</body>
</html>