<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; background-color: #f9f9f9; }
        .header { background-color: #2563eb; color: white; padding: 20px; text-align: center; }
        .content { background-color: white; padding: 20px; margin-top: 10px; }
        .info { background-color: #f0f4f8; padding: 15px; border-left: 4px solid #2563eb; margin-top: 15px; }
        .footer { text-align: center; font-size: 12px; color: #999; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Yêu Cầu Tuyển Dụng Nhân Viên - Charcoal</h1>
        </div>
        
        <div class="content">
            <p>Xin chào {{ $staffRequest->FullName }},</p>
            
            <p>Cảm ơn bạn đã gửi yêu cầu tuyển dụng nhân viên cho chúng tôi. Chúng tôi đã nhận được hồ sơ của bạn và sẽ xem xét nó trong thời gian sớm nhất.</p>
            
            <div class="info">
                <strong>Thông tin yêu cầu:</strong><br>
                <strong>Vị trí:</strong> {{ $staffRequest->Position }}<br>
                <strong>Email:</strong> {{ $staffRequest->Email }}<br>
                <strong>Điện thoại:</strong> {{ $staffRequest->Phone ?? 'Không cập nhật' }}<br>
                <strong>Trạng thái:</strong> <span style="color: #f59e0b; font-weight: bold;">Đang chờ duyệt</span>
            </div>
            
            <p style="margin-top: 20px;">Chúng tôi sẽ liên hệ với bạn qua email hoặc điện thoại để thông báo kết quả sớm nhất. Vui lòng chú ý kiểm tra email định kỳ.</p>
            
            <p style="margin-top: 30px;">Trân trọng,<br>Đội ngũ Charcoal</p>
        </div>
        
        <div class="footer">
            <p>&copy; 2026 Charcoal. Tất cả các quyền được bảo lưu.</p>
        </div>
    </div>
</body>
</html>
