    <style>
        :root {
            --elite-shadow: 0 20px 50px -15px rgba(22, 143, 124, 0.15);
            --indigo-accent: #168F7C;
        }
        
        body { background-color: #f8fafc; }
        .shadow-elite { box-shadow: var(--elite-shadow) !important; }
        .rounded-5 { border-radius: 2rem !important; }
        .text-indigo { color: var(--indigo-accent); }
        .font-arabic { font-family: 'Cairo', sans-serif; }
        .extra-small { font-size: 0.75rem; }

        /* Navigation */
        .elite-profile-nav .nav-link {
            border: none;
            color: #64748b;
            font-weight: 700;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: transparent;
            position: relative;
        }
        .elite-profile-nav .nav-link:hover { background: #f1f5f9; color: var(--bs-primary); transform: translateX(-5px); }
        .elite-profile-nav .nav-link.active {
            background: #fff;
            color: var(--bs-primary);
            box-shadow: 0 10px 20px rgba(0,0,0,0.05);
            transform: scale(1.02);
            border-right: 4px solid var(--bs-primary);
        }

        /* Widgets & Stats */
        .stats-item { transition: 0.3s; }
        .stats-item:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important; }
        .opacity-05 { opacity: 0.05; }

        /* Widgets */
        .stats-mini-card:hover { transform: translateY(-5px); background: #fff !important; box-shadow: 0 10px 20px rgba(0,0,0,0.05); }
        .icon-circle { width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; border-radius: 1rem; font-size: 1.2rem; }
        .icon-sq { width: 56px; height: 56px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; }
        
        .pulse-success { animation: pulse-green 2s infinite; }
        @keyframes pulse-green {
            0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(16, 185, 129, 0); }
            100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }

        .hover-lift:hover { transform: translateY(-3px); box-shadow: 0 15px 30px rgba(0,0,0,0.08) !important; }
        .border-dashed-warning { border: 2px dashed rgba(255, 193, 7, 0.3); }

        /* Ticket Styles */
        .premium-ticket {
            border-radius: 20px;
            overflow: hidden;
            position: relative;
        }
        .border-dashed {
            border-left: 2px dashed #dee2e6 !important;
        }
        .ticket-stub-decoration {
            position: absolute;
            width: 30px;
            height: 30px;
            background: #f8fafc;
            border-radius: 50%;
            left: -15px;
            z-index: 10;
        }
        .ticket-stub-decoration.top { top: -15px; }
        .ticket-stub-decoration.bottom { bottom: -15px; }
        
        @media (max-width: 768px) {
            .border-dashed {
                border-left: none !important;
                border-top: 2px dashed #dee2e6 !important;
            }
            .ticket-stub-decoration {
                display: none;
            }
        }
        
        .grade-badge { width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 0.85rem; }

        /* Timeline */
        .timeline-item:last-child .timeline-content { border-bottom: none !important; }
        
        /* PRINT SPECIFIC STYLES - ID CARD */
        @media print {
            /* ONLY APPLY IF body.print-id-card IS PRESENT */
            body.print-id-card > :not(.id-card-print) {
                display: none !important;
            }
            
            body.print-id-card .id-card-print {
                display: flex !important;
                visibility: visible !important;
                position: fixed !important;
                left: 0 !important;
                top: 0 !important;
                width: 100vw;
                height: 100vh;
                align-items: center;
                justify-content: center;
                background: white !important;
                padding: 0 !important;
                margin: 0 !important;
                z-index: 999999;
                direction: rtl !important;
                text-align: right;
            }

            body.print-id-card .id-card-print * {
                visibility: visible !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }

        /* Screen Styles for ID Card - Hide offscreen but rendered for QR */
        .id-card-print {
            position: fixed;
            left: -9999px;
            top: 0;
            opacity: 0;
            z-index: -100;
            /* Do NOT use display: none, otherwise QR code won't generate dimensions */
        }

            .id-card-container {
                width: 85.6mm; /* Standard ID Card Credit Card Size */
                height: 54mm;
                position: relative;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                z-index: 100000;
                margin: 0 auto;
                direction: rtl !important;
            }

            .id-card {
                width: 100%;
                height: 100%;
                border-radius: 4mm;
                overflow: hidden;
                position: relative;
                background: white;
                border: 1px solid #e2e8f0;
                display: flex;
                flex-direction: column;
            }

            /* Decorative Background Elements */
            .id-card::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                height: 35%;
                background: var(--bs-primary);
                background: linear-gradient(135deg, var(--bs-primary) 0%, #0D7465 100%);
                clip-path: polygon(0 0, 100% 0, 100% 70%, 0 100%);
                z-index: 0;
            }

            .id-header {
                position: relative;
                z-index: 1;
                display: flex;
                align-items: center;
                padding: 4mm 5mm 0;
                gap: 3mm;
                color: white;
            }

            .logo-area img {
                width: 10mm;
                height: 10mm;
                object-fit: contain;
                filter: brightness(0) invert(1); /* Make logo white if possible, or remove filter */
                background: rgba(255,255,255,0.2);
                border-radius: 2mm;
                padding: 1px;
            }
            
            .center-name h1 {
                font-size: 8pt;
                font-weight: 800;
                margin: 0;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }
            
            .center-name span {
                font-size: 6pt;
                opacity: 0.9;
                font-weight: 600;
            }

            .id-body {
                flex-grow: 1;
                position: relative;
                z-index: 2;
                display: flex;
                flex-direction: column;
                align-items: center;
                padding-top: 2mm;
            }

            .student-photo-wrapper {
                width: 18mm;
                height: 18mm;
                border-radius: 50%;
                border: 2px solid white;
                box-shadow: 0 2px 5px rgba(0,0,0,0.2);
                overflow: hidden;
                margin-bottom: 2mm;
                background: #f1f5f9;
                position: relative;
            }
             
            .student-photo {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }
            
            .student-photo-placeholder {
                width: 100%;
                height: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: bold;
                color: var(--bs-primary);
                font-size: 14pt;
            }

            .student-name {
                font-size: 11pt;
                font-weight: 800;
                color: #000000 !important; /* Force Black */
                margin: 0 0 1mm;
                text-align: center;
            }

            .student-meta {
                display: flex;
                gap: 2mm;
                margin-bottom: 2mm;
            }

            .student-meta .grade-badge {
                font-size: 6pt;
                background: #e0e7ff !important;
                color: #4338ca !important; /* Force Blue */
                padding: 0.5mm 2mm;
                border-radius: 2mm;
                font-weight: 700;
                width: auto;
                height: auto;
            }

            .info-grid {
                display: flex;
                justify-content: space-between;
                width: 80%;
                margin-bottom: 2mm;
                border-top: 1px solid #f1f5f9;
                padding-top: 2mm;
            }

            .info-item {
                text-align: center;
                /* flex: 1; */
            }

            .info-item label {
                display: block;
                font-size: 6pt !important;
                color: #64748b !important;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                margin-bottom: 0.5mm;
            }

            .info-item strong {
                display: block;
                font-size: 8pt !important;
                color: #000000 !important; /* Force Black */
                font-weight: bold !important;
            }

            .qr-area {
                margin-top: auto;
                margin-bottom: 2mm;
                text-align: center;
                width: 90%;
            }
            
            .qr-area canvas,
            .qr-area img {
                width: 18mm !important;
                height: 18mm !important;
                display: block !important;
                visibility: visible !important;
                margin: 0 auto;
            }
            
            #student-qrcode {
                display: block !important;
                visibility: visible !important;
            }
            
            .code-text {
                font-size: 6pt;
                font-family: monospace;
                letter-spacing: 2px;
                color: #475569;
                display: block;
                margin-top: 1px;
            }

            .id-footer {
                background: #f8fafc;
                padding: 1.5mm;
                text-align: center;
                border-top: 1px solid #e2e8f0;
            }
            
            .id-footer p {
                margin: 0;
                font-size: 5pt;
                color: #94a3b8;
            }
        }
    </style>
