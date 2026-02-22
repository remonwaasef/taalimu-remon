<!DOCTYPE html>
<html dir="rtl">
<head>
    <meta charset="utf-8">
    <title>{{ __('center::messages.blade_0156') }}</title>
    <style>
        @font-face {
            font-family: 'Amiri';
            src: url('https://cdnjs.cloudflare.com/ajax/libs/amiri-font/0.1.2/Amiri-Regular.ttf') format('truetype');
        }
        body {
            font-family: 'Amiri', serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }
        .certificate-container {
            width: 100%;
            height: 100%;
            padding: 50px;
            box-sizing: border-box;
            border: 20px solid #1a73e8;
            background-color: #fff;
            position: relative;
        }
        .certificate-border {
            border: 2px solid #1a73e8;
            padding: 40px;
            height: 100%;
            box-sizing: border-box;
        }
        .header {
            text-align: center;
            margin-bottom: 50px;
        }
        .header h1 {
            font-size: 50px;
            color: #1a73e8;
            margin: 0;
        }
        .content {
            text-align: center;
        }
        .content p {
            font-size: 24px;
            margin: 10px 0;
        }
        .student-name {
            font-size: 40px;
            font-weight: bold;
            color: #333;
            margin: 30px 0;
            border-bottom: 2px solid #eee;
            display: inline-block;
            padding: 0 50px;
        }
        .course-title {
            font-size: 30px;
            font-weight: bold;
            color: #1a73e8;
        }
        .footer {
            margin-top: 80px;
            display: table;
            width: 100%;
        }
        .footer-cell {
            display: table-cell;
            width: 33%;
            text-align: center;
            vertical-align: bottom;
        }
        .qr-placeholder {
            width: 100px;
            height: 100px;
            margin: 0 auto;
            border: 1px solid #eee;
        }
        .uuid {
            font-size: 12px;
            color: #999;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        <div class="certificate-border">
            <div class="header">
                <h1>{{ __('center::messages.blade_0157') }}</h1>
                <p>{{ __('center::messages.blade_0158') }}</p>
            </div>
            
            <div class="content">
                <p>{{ __('center::messages.blade_0159') }}</p>
                <div class="student-name">{{ $certificate->metadata['student_name'] ?? $student->user->name }}</div>
                
                <p>{{ __('center::messages.blade_0160') }}</p>
                <div class="course-title">{{ $certificate->metadata['course_title'] ?? $course->title }}</div>
                
                <p>وذلك بتاريخ {{ $certificate->issued_at->format('Y/m/d') }}</p>
            </div>
            
            <div class="footer">
                <div class="footer-cell">
                    <p>{{ __('center::messages.blade_0161') }}</p>
                    <div style="height: 60px;"></div>
                </div>
                <div class="footer-cell">
                    <div class="qr-placeholder">
                        <small style="font-size: 10px; display: block; margin-top: 40px;">{{ __('center::messages.blade_0162') }}</small>
                    </div>
                </div>
                <div class="footer-cell">
                    <p>{{ __('center::messages.blade_0163') }}</p>
                    <div style="height: 60px;"></div>
                </div>
            </div>
            
            <div style="text-align: center;" class="uuid">
                رقم التحقق: {{ $certificate->uuid }}
            </div>
        </div>
    </div>
</body>
</html>
