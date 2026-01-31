<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; background-color: #f9f9f9; }
        .header { background-color: #2563eb; color: white; padding: 20px; text-align: center; }
        .content { background-color: white; padding: 20px; margin-top: 10px; }
        .button { background-color: #2563eb; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; margin-top: 15px; }
        .footer { text-align: center; font-size: 12px; color: #999; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Xác Nhận Email - Charcoal</h1>
        </div>
        
        <div class="content">
            <p>Xin chào {{ $user->FullName }},</p>
            
            <p>Cảm ơn bạn đã đăng ký tài khoản khách hàng với chúng tôi. Để hoàn tất đăng ký, vui lòng xác nhận email của bạn bằng cách nhấp vào nút bên dưới:</p>
            
            <center>
                <a href="{{ $verificationLink }}" class="button">Xác Nhận Email</a>
            </center>
            
            <p style="margin-top: 20px;">Nếu bạn không yêu cầu đăng ký này, vui lòng bỏ qua email này.</p>
            
            <p style="margin-top: 30px;">Trân trọng,<br>Đội ngũ Charcoal</p>
        </div>
        
        <div class="footer">
            <p>&copy; 2026 Charcoal. Tất cả các quyền được bảo lưu.</p>
        </div>
    </div>
</body>
</html>
