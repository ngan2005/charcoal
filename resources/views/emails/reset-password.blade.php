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
            <h1>Đặt Lại Mật Khẩu - Charcoal</h1>
        </div>
        
        <div class="content">
            <p>Xin chào {{ $user->FullName }},</p>
            
            <p>Chúng tôi nhận được yêu cầu đặt lại mật khẩu cho tài khoản của bạn. Nhấp vào nút bên dưới để tạo mật khẩu mới:</p>
            
            <center>
                <a href="{{ $resetLink }}" class="button">Đặt Lại Mật Khẩu</a>
            </center>
            
            <p style="margin-top: 20px;"><strong>Lưu ý:</strong> Liên kết này sẽ hết hạn sau 1 giờ.</p>
            
            <p>Nếu bạn không yêu cầu đặt lại mật khẩu, vui lòng bỏ qua email này hoặc liên hệ với chúng tôi ngay lập tức.</p>
            
            <p style="margin-top: 30px;">Trân trọng,<br>Đội ngũ Charcoal</p>
        </div>
        
        <div class="footer">
            <p>&copy; 2026 Charcoal. Tất cả các quyền được bảo lưu.</p>
        </div>
    </div>
</body>
</html>
