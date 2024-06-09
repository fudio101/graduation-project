<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">

    <title>Hợp Đồng Thuê Nhà Trọ</title>
    <style>
        body {
            margin: 20px;
            font-family: 'DejaVu Sans';
            src: url('{{ storage_path('fonts/DejaVuSans.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        .center {
            text-align: center;
        }

        .content {
            margin-top: 20px;
        }

        .section {
            margin-top: 30px;
        }

        .field {
            margin-bottom: 10px;
        }

        .bold {
            font-weight: bold;
        }

        @font-face {
            font-family: 'DejaVu Sans';
            src: url('{{ storage_path('fonts/DejaVuSans.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }
    </style>
</head>

<body>
    <div class="center">
        <p>CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM</p>
        <p><b>Độc lập – Tự do – Hạnh phúc</b></p>
        <p>{{ $date['day_name'] ?? '' }}, ngày {{ $date['day'] ?? '' }} tháng {{ $date['month'] ?? '' }} năm
            {{ $date['year'] ?? '' }}</p>
        <h2>HỢP ĐỒNG THUÊ NHÀ TRỌ</h2>
    </div>
    <div class="content">
        <p>- Căn cứ Luật Nhà ở ngày 25 tháng 11 năm 2014;</p>
        <p>- Căn cứ vào các quy định pháp luật có liên quan;</p>
        <p>Tại số nhà {{ $contract->room->house->address ?? '' }}, phường (xã)
            {{ optional($contract->room->house->ward)->name ?? '.....' }}, quận
            (huyện) {{ optional($contract->room->house->districts)->name ?? '.....' }} , thành phố
            (tỉnh) {{ $contract->room->house->province->name }} .
        </p>
    </div>
    <div class="content">
        <p>Chúng tôi gồm:</p>
    </div>
    <div class="section">
        <div class="field"><b>BÊN CHO THUÊ NHÀ (Sau đây gọi tắt là bên A):</b></div>
        <div class="field">Ông (bà): <span>__________________________</span></div>
        <div class="field">CMND số: <span>__________________________</span></div>
        <div class="field">HKTT/Chỗ ở hiện tại: <span>__________________________</span></div>
        <div class="field">Điện thoại liên hệ: <span>__________________________</span></div>
    </div>
    <div class="section">
        <div class="field"><b>BÊN THUÊ NHÀ Ở (Sau đây gọi tắt là bên B):</b></div>
        <div class="field">Ông (bà): <span>__________________________</span></div>
        <div class="field">CMND số: <span>__________________________</span></div>
        <div class="field">HKTT: <span>__________________________</span></div>
        <div class="field">Chỗ ở hiện tại: <span>__________________________</span></div>
        <div class="field">Điện thoại liên hệ: <span>__________________________</span></div>
    </div>
    <div class="section">
        <p>Hai bên thống nhất ký kết Hợp đồng cho thuê nhà để ở với các nội dung sau:</p>
    </div>
    <div class="section">
        <h3>ĐIỀU 1 : NỘI DUNG HỢP ĐỒNG</h3>
        <!-- Add more content for the contract here -->
    </div>
</body>

</html>
