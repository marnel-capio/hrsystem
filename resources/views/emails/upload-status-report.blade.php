<div style="font-family: Arial, sans-serif; background:#f4f4f5; padding:30px;">

    <!-- CARD -->
    <div style="background:#ffffff; border:1px solid #B1B6C4; border-radius:10px; overflow:hidden;">

        <!-- HEADER -->
        <div style="background:#cae1fc; color:#fff; padding:15px 20px; border-radius:10px 10px 0 0;">

            <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                <tr>

                    <!-- LEFT LOGO -->
                    <td width="50" style="vertical-align:middle;">
                        <img 
                            src="{{ url('images/aws.png') }}"
                            alt="AWS Logo"
                            width="40"
                            style="display:block; border-radius:6px;"
                        >
                    </td>

                </tr>
            </table>

        </div>

        <!-- BODY CARD -->
        <div style="background:#ffffff; border-radius:0 0 10px 10px; overflow:hidden;">

            <!-- CONTENT -->
            <div style="padding:25px; color:#333;">

                <h3 style="margin-top:0;">
                    Upload Status Report ({{ $batchName }}) as of {{ $date }}
                </h3>

                <br>

                <p><strong>Total Applicants:</strong> {{ $totalApplicants }}</p>

                <p><strong>[Successful]</strong></p>

                <p>New Applicants: {{ $newApplicants }}</p>
                <p>Existing Applicants: {{ $existingApplicants }}</p>

                <p style="margin-left:15px;">
                    For Exam: {{ $forExam }}<br>
                    For Initial Interview: {{ $forInitialInterview }}<br>
                    For Final Interview: {{ $forFinalInterview }}
                </p>

                <br>

                <p><strong>[Failed]</strong></p>

                <p>Rejected Applicants: {{ $rejectedApplicants }}</p>

                @if (!empty($failedList))
                    @foreach ($failedList as $failed)
                        <p style="margin-left:15px; margin-bottom:5px;">
                            - {{ $failed['name'] ?? 'Unknown' }}, {{ $failed['reason'] ?? 'No reason provided' }}
                        </p>
                    @endforeach
                @else
                    <p style="margin-left:15px;">No failed applicants.</p>
                @endif

                <br>

                <p style="font-size:14px">This is an automated report from the HR system.</p>

                <br>
                <p style="font-size:14px;">Thank you,</p>
                <strong style="font-size:14px;">{{ $senderName }}</strong>
                <p style="font-size:14px; margin:0;">{{ $senderRole }}</p>
                {{-- <p style="font-size:14px">Thank you,</p>
                        <strong style="font-size:14px">' . e($this->senderName) . '</strong>
                        <p style="font-size:14px">' . e($this->senderRole) . '</p> --}}
            </div>


        </div>
        

        <!-- FOOTER (BOTTOM OF CARD, EDGE-TO-EDGE) -->
        <div style="padding:10px; background:#f3f4f6; text-align:center; border-top:1px solid #cfd0d5;">
            <p style="font-size:11px; color:#6b7280; margin:0;">
                © 2026 Advanced World Solutions. All rights reserved.
            </p>
        </div>

    </div>
</div>