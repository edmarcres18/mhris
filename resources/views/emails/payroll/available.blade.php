<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payroll Available</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; margin: 0; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background: #fff; border: 1px solid #ddd; border-radius: 5px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <div style="background: #007bff; color: white; padding: 15px 20px; border-radius: 5px 5px 0 0;">
            <h2 style="margin: 0; font-size: 20px;">Payroll Available</h2>
        </div>

        <div style="padding: 20px;">
            <p>Dear {{ $data['employee_name'] }},</p>

            <p>Your payroll for the period of {{ $data['start_date'] }} to {{ $data['end_date'] }} is now available. You can log in to your account to download or print your payslip.</p>

            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ route('payroll.myPayrolls') }}" style="background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;">
                    View My Payrolls
                </a>
            </div>

            <hr style="border: none; border-top: 1px solid #eee; margin: 20px 0;">

            <p style="margin-bottom: 0;">
                Thanks,<br>
                {{ config('app.name') }}
            </p>
        </div>
    </div>
</body>
</html>
