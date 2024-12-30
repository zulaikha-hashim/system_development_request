<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Application Report</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12px;
            margin-bottom: 60px;
            color: #333;
        }

        h2 {
            text-align: center;
            font-size: 18px;
            margin-top: 20px;
            text-transform: uppercase;
        }

        .section-title {
            font-weight: bold;
            font-size: 14px;
            margin-top: 30px;
            color: #333;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }

        .header,
        .report-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header img {
            width: 150px;
            margin-right: 20px;
        }

        .company-info {
            line-height: 1.2;
        }

        .company-info p {
            margin: 0;
        }

        .contact-info {
            font-weight: bold;
        }

        .report-info {
            margin-top: 10px;
            font-size: 12px;
            text-align: right;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            table-layout: fixed;
            /* Ensures columns are stretched */
        }

        table th,
        table td {
            padding: 10px;
            text-align: left;
            font-size: 12px;
        }

        table th {
            color: #555;
            font-weight: bold;
            font-size: 12px;
            text-transform: uppercase;
        }

        table td {
            font-size: 12px;
            color: #555;
            word-wrap: break-word;
            /* Ensures long text wraps instead of overflowing */
        }
    </style>
</head>

<body>

    <!-- Header Section -->
    <div class="header">
        <img src="{{ public_path('import/assets/img/intec-logo.png') }}" alt="INTEC Logo">
        <div class="company-info">
            <p>Jalan Senangin Satu 17/2A, Seksyen 17,</p>
            <p>40200 Shah Alam, Selangor Darul Ehsan, MALAYSIA</p>
            <p class="contact-info">+603 8603 7000 | www.intec.edu.my</p>
        </div>
    </div>

    <!-- Additional Information Section -->
    <div class="report-info">
        <p>Generated on: {{ now()->timezone('Asia/Kuala_Lumpur')->format('d-m-Y H:i:s') }}</p>
    </div>
    

    <h2>Application Report</h2>

    <!-- Personal Details Table -->
    <h3 class="section-title">Personal Details</h3>
    <table>
        <tr>
            <th>Applicant ID</th>
            <td>{{ $application->applicant->applicant_id ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>NRIC</th>
            <td>{{ $application->applicant->applicant_ic ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Full Name</th>
            <td>{{ $application->applicant->applicant_name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Email</th>
            <td>{{ $application->applicant->applicant_email ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Phone</th>
            <td>{{ $application->applicant->applicant_phone ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Department</th>
            <td>{{ $application->applicant->applicant_depart ?? 'N/A' }}</td>
        </tr>
    </table>


    <!-- System Development Request Details Table -->
    <h3 class="section-title">System Development Request Details</h3>
    <table>
        <tr>
            <th>Application ID</th>
            <td>{{ $application->applications_id ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Developer ID</th>
            <td>{{ $application->dev_id ?? 'N/A' }} </td>
        </tr>
        <tr>
            <th>Developer Name</th>
            <td>{{ $application->developer->dev_name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Status</th>
            <td>{{ $application->status->status_name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>System Name</th>
            <td>{{ $application->applications_system_name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>System Description</th>
            <td>{{ $application->applications_system_desc ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Urgency</th>
            <td>{{ $application->applications_urgency ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Request Date & Time</th>
            <td>{{ $application->created_at ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Meeting Confirmation</th>
            <td>{{ $application->date_confirm ?? 'N/A' }}, {{ $application->applications_time ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>System Deadline</th>
            <td>{{ $application->applications_deadline ?? 'N/A' }}</td>
        </tr>
        
    </table>

</body>

</html>
