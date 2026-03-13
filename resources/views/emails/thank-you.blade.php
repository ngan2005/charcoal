<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chào mừng đến với Pink Charcoal</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Arial, sans-serif; background-color: #f5f5f5;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f5f5f5; padding: 40px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
                    <!-- Header with Pink Charcoal branding -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #ec4899 0%, #f472b6 100%); padding: 40px 30px; text-align: center;">
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center">
                                        <!-- Logo/Brand Icon -->
                                        <div style="width: 80px; height: 80px; background-color: #ffffff; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
                                            <span style="font-size: 36px;">🎀</span>
                                        </div>
                                        <!-- Brand Name -->
                                        <h1 style="color: #ffffff; margin: 0; font-size: 28px; font-weight: 700; text-shadow: 0 2px 4px rgba(0,0,0,0.1);">Pink Charcoal</h1>
                                        <p style="color: rgba(255,255,255,0.9); margin: 8px 0 0 0; font-size: 14px;">Nơi thú cưng được yêu thương</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Welcome Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td>
                                        <h2 style="color: #1e293b; margin: 0 0 20px 0; font-size: 24px; font-weight: 700;">Chào mừng {{ $user->FullName }}! 🎉</h2>
                                        
                                        <p style="color: #475569; margin: 0 0 16px 0; font-size: 16px; line-height: 1.6;">
                                            Cảm ơn bạn đã đồng hành cùng <strong style="color: #ec4899;">Pink Charcoal</strong>! 
                                            Tài khoản của bạn đã được kích hoạt thành công và sẵn sàng để khám phá thế giới thú cưng đáng yêu của chúng tôi.
                                        </p>

                                        <!-- Account Info Box -->
                                        <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f8fafc; border-radius: 12px; margin: 24px 0; border: 1px solid #e2e8f0;">
                                            <tr>
                                                <td style="padding: 20px;">
                                                    <p style="color: #64748b; margin: 0 0 8px 0; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">Thông tin tài khoản</p>
                                                    <table width="100%" cellpadding="0" cellspacing="0">
                                                        <tr>
                                                            <td style="padding: 8px 0; border-bottom: 1px solid #e2e8f0;">
                                                                <span style="color: #64748b; font-size: 14px;">📧 Email:</span>
                                                                <span style="color: #1e293b; font-size: 14px; font-weight: 600; margin-left: 8px;">{{ $user->Email }}</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="padding: 8px 0 0 0;">
                                                                <span style="color: #64748b; font-size: 14px;">👤 Tên:</span>
                                                                <span style="color: #1e293b; font-size: 14px; font-weight: 600; margin-left: 8px;">{{ $user->FullName }}</span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>

                                        <!-- CTA Button -->
                                        <table width="100%" cellpadding="0" cellspacing="0" style="margin: 32px 0;">
                                            <tr>
                                                <td align="center">
                                                    <a href="{{ route('shop') }}" style="display: inline-block; background: linear-gradient(135deg, #ec4899 0%, #f472b6 100%); color: #ffffff; text-decoration: none; padding: 16px 40px; border-radius: 50px; font-size: 16px; font-weight: 600; box-shadow: 0 4px 15px rgba(236, 72, 153, 0.4); transition: transform 0.2s, box-shadow 0.2s;">
                                                        🐾 Khám phá ngay
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>

                                        <!-- Features List -->
                                        <table width="100%" cellpadding="0" cellspacing="0" style="margin-top: 24px;">
                                            <tr>
                                                <td style="padding: 16px 0;">
                                                    <p style="color: #1e293b; margin: 0 0 12px 0; font-size: 15px; font-weight: 600;">Bạn có thể làm gì với tài khoản:</p>
                                                    <ul style="color: #475569; margin: 0; padding-left: 20px; font-size: 14px; line-height: 1.8;">
                                                        <li>Đặt lịch hẹn dịch vụ spa & grooming cho thú cưng</li>
                                                        <li>Mua sắm các sản phẩm chăm sóc thú cưng chính hãng</li>
                                                        <li>Theo dõi đơn hàng và lịch sử mua hàng</li>
                                                        <li>Nhận các ưu đãi độc quyền dành riêng cho thành viên</li>
                                                    </ul>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #1e293b; padding: 30px; text-align: center;">
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center">
                                        <p style="color: #94a3b8; margin: 0 0 8px 0; font-size: 13px;">
                                            Cần hỗ trợ? Liên hệ chúng tôi qua 
                                            <a href="mailto:support@pinkcharcoal.com" style="color: #ec4899; text-decoration: none;">support@pinkcharcoal.com</a>
                                        </p>
                                        <p style="color: #64748b; margin: 0; font-size: 12px;">
                                            &copy; 2026 Pink Charcoal. All rights reserved.<br>
                                            <span style="color: #94a3b8;">Nơi thú cưng được yêu thương ❤️</span>
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
