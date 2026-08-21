<?php

return [
    'seo' => [
        'title' => 'Taalimu — The easiest way to run your educational center without manual administration',
        'description' => 'All-in-one system for student management, smart QR attendance, fees & installment tracking, financial reports, and automated WhatsApp alerts for parents.',
    ],
    'nav' => [
        'features' => 'Features',
        'whatsapp' => 'WhatsApp Alerts',
        'excel_migration' => 'Excel Import',
        'how_it_works' => 'How It Works',
        'pricing' => 'Pricing',
        'faq' => 'FAQ',
        'sign_in' => 'Sign In',
        'start_trial' => 'Try for Free',
    ],
    'hero' => [
        'badge' => 'Designed for tutoring centers and educators across Egypt & MENA',
        'headline' => 'Run your educational center',
        'headline_highlight' => 'without manual administration.',
        'description' => 'Taalimu manages students, attendance, installments, and subscriptions, sending automated notifications via WhatsApp — so you focus on teaching, not daily admin.',
        'cta_free' => 'Try Taalimu Free',
        'cta_demo' => 'See How It Works',
        'check_nocard' => 'No credit card required',
        'check_setup' => 'Quick 2-minute setup',
        'check_trial' => 'Full 30-day free trial',
        'check_arabic' => '100% Arabic & English ready',
        
        'pillar1_title' => 'Automated WhatsApp',
        'pillar1_sub' => 'Attendance, absence & reminders',
        'pillar2_title' => 'Instant Attendance',
        'pillar2_sub' => 'Fast QR code scanning',
        'pillar3_title' => '30 Days',
        'pillar3_sub' => 'Full-featured free trial',

        'card_whatsapp_title' => 'Automated WhatsApp Alert',
        'card_whatsapp_sub' => 'Installment reminder sent to parent',
        'card_qr_title' => 'Instant Attendance',
        'card_qr_sub' => 'Ahmed Omar — Checked in at 04:30 PM',
    ],
    'pain_points' => [
        'badge' => 'Daily Reality of Center Management',
        'title_prefix' => 'Is managing your center taking',
        'title_highlight' => 'more time than it should?',
        'subtitle' => 'Most tutoring and learning centers struggle with daily chaos caused by disconnected tools.',
        'items' => [
            [
                'icon' => 'fa-file-excel',
                'title' => 'Scattered Data',
                'desc' => 'Student numbers and parent contacts are fragmented across Excel files, WhatsApp chats, and paper notes.',
            ],
            [
                'icon' => 'fa-money-bill-wave',
                'title' => 'Lost Overdues & Fees',
                'desc' => 'Tracking payments manually leads to forgotten debts and awkward repeated phone calls.',
            ],
            [
                'icon' => 'fa-user-clock',
                'title' => 'Wasted Class Time on Attendance',
                'desc' => 'Staff waste valuable teaching minutes calling roll call on paper and entering it manually later.',
            ],
            [
                'icon' => 'fa-comments',
                'title' => 'Endless Parent Inquiries',
                'desc' => 'Daily repetitive messages asking about student attendance, class schedules, and exam marks.',
            ],
            [
                'icon' => 'fa-chart-line',
                'title' => 'Unclear Financial Visibility',
                'desc' => 'Calculating monthly teacher shares, expenses, and net profit takes days of tedious spreadsheet math.',
            ],
            [
                'icon' => 'fa-network-wired',
                'title' => 'Reliance on People, Not Systems',
                'desc' => 'If a single assistant is absent, the day turns chaotic with conflicting schedules and lost records.',
            ],
        ],
        'solution_banner_title' => 'Taalimu brings all these operations into one centralized place',
        'solution_banner_subtitle' => 'and automates repetitive tasks so you regain complete control over your center.',
    ],
    'outcome' => [
        'badge' => 'The Real Transformation',
        'title' => 'From manual administration to an automated center',
        'subtitle' => 'See the difference when your center moves from legacy tools to a platform built specifically for learning centers.',
        'before_title' => 'Your Center Before Taalimu',
        'before_items' => [
            'Scattered Excel sheets and out-of-sync updates',
            'Manually copying and sending WhatsApp messages to parents',
            'Tracking overdue fees on paper with awkward calls',
            'Paper attendance that wastes class time and is prone to errors',
            'Searching through chat histories to find a student record',
            'Manually calculating instructor revenue splits every month',
        ],
        'after_title' => 'Your Center With Taalimu',
        'after_items' => [
            'Centralized student database updated in real time',
            'Instant, automated WhatsApp notifications to parents',
            'Clear view of who paid and who is overdue without friction',
            'Fast QR code attendance with instant parent check-in alerts',
            'Comprehensive profile for every student (attendance, fees, marks)',
            'Instant financial reports with automated teacher splits',
        ],
    ],
    'whatsapp_killer' => [
        'badge' => 'The #1 Center Superpower',
        'title' => 'Your center communicates with parents automatically.',
        'subtitle' => 'No more manual texting. Every event inside the center triggers a professional, instant WhatsApp notification.',
        'cases' => [
            'attendance' => [
                'tag' => 'Student Check-in',
                'title' => 'Class Arrival Notification',
                'desc' => 'As soon as the student scans their QR code, the parent receives an instant arrival confirmation.',
                'msg' => 'Dear Parent of Ahmed Omar, we are pleased to inform you that Ahmed has checked into Physics class (Sat 04:30 PM). Have a great session! 📚',
            ],
            'absence' => [
                'tag' => 'Student Absence',
                'title' => 'Instant Absence Alert',
                'desc' => 'If a student misses their session, parents are notified automatically for quick follow-up.',
                'msg' => 'Notice to the parent of Salma Khaled: Salma was marked absent today from Math class (05:00 PM group). Please reach out to coordinate. ⚠️',
            ],
            'payment_due' => [
                'tag' => 'Payment Due',
                'title' => 'Polite Pre-Due Reminder',
                'desc' => 'A respectful and automated reminder sent ahead of the due date to avoid accumulation.',
                'msg' => 'Hello Mr. Mahmoud, this is a gentle reminder that the October subscription for Youssef ($20) is due tomorrow. Thank you for your cooperation! 💳',
            ],
            'payment_overdue' => [
                'tag' => 'Overdue Follow-up',
                'title' => 'Structured Overdue Notice',
                'desc' => 'Automated, polite follow-up without the awkwardness of manual phone calls.',
                'msg' => 'Outstanding Balance Notice: We kindly remind you of a pending balance ($20) for Chemistry. Please settle by Thursday. Thank you! 📋',
            ],
            'exam_result' => [
                'tag' => 'Exam Result',
                'title' => 'Grading & Evaluation Report',
                'desc' => 'As soon as marks are entered, parents receive a direct grade report on WhatsApp.',
                'msg' => 'Congratulations! Omar Yassin scored (48 / 50) on the Monthly Biology Test. Grade: Excellent 🌟 We appreciate his hard work!',
            ],
            'announcement' => [
                'tag' => 'Important Notice',
                'title' => 'Schedule Updates & Announcements',
                'desc' => 'Send time changes or holiday notices to all group students and parents with a single click.',
                'msg' => 'Notice for Sunday Group: The upcoming revision session has been rescheduled to start at 06:00 PM instead of 05:00 PM. Best of luck! 📢',
            ],
        ],
    ],
    'payments_section' => [
        'badge' => 'Billing & Cash Flow',
        'title' => 'Know who paid, who owes what, and what is happening in your center.',
        'subtitle' => 'Complete visibility over your revenue, expenses, and collections without spreadsheet errors.',
        'points' => [
            [
                'title' => 'Instant Overdue Breakdown',
                'desc' => 'One click displays total outstanding balances and a list of students with unpaid fees.',
            ],
            [
                'title' => 'Digital Receipts & Invoices',
                'desc' => 'Record payments, issue serial-numbered electronic receipts, and send them directly to parents.',
            ],
            [
                'title' => 'Support for Sessions, Monthly & Installments',
                'desc' => 'Flexible payment models: per-session, monthly subscriptions, or scheduled course installments.',
            ],
            [
                'title' => 'Automated Teacher Revenue Splits',
                'desc' => 'Set percentage splits or per-session rates for each instructor, and let the system calculate payouts.',
            ],
        ],
    ],
    'attendance_section' => [
        'badge' => 'Smart Attendance',
        'title' => 'Attendance is no longer paper or Excel.',
        'subtitle' => 'Save 20 minutes every class with lightning-fast QR scanning linked to parent notifications.',
        'points' => [
            [
                'title' => 'Quick QR Code Scanning',
                'desc' => 'Each student has a unique QR code scanned via smartphone camera or barcode scanner in a second.',
            ],
            [
                'title' => 'Fast Manual Roster Check-in',
                'desc' => 'An interactive roster to mark the entire group in seconds if a student forgets their card.',
            ],
            [
                'title' => 'Historical Attendance Records',
                'desc' => 'Track attendance percentages and absence trends over the entire month or term for proactive action.',
            ],
            [
                'title' => 'Instructor & Assistant Tracking',
                'desc' => 'Accurately monitor teacher check-ins, assistant hours, and classroom occupancy.',
            ],
        ],
    ],
    'excel_migration' => [
        'badge' => 'Smooth Migration',
        'title' => 'Already have student data in Excel?',
        'title_sub' => 'Don’t start from scratch.',
        'subtitle' => 'Import your student records, parent phone numbers, and course groups in seconds and get running immediately.',
        'step1' => 'Upload your existing file (Excel or CSV)',
        'step2' => 'The system maps columns automatically',
        'step3' => 'Start managing right away with zero manual re-entry',
        'cta' => 'Start with Your Existing Data',
    ],
    'showcase' => [
        'badge' => 'Inside the Platform',
        'title' => 'Every tool solves a real center problem',
        'subtitle' => 'A clean, intuitive, and responsive interface designed for your staff without steep learning curves.',
        'tabs' => [
            'dashboard' => 'Dashboard',
            'students' => 'Student Profiles',
            'attendance' => 'Attendance Roster',
            'payments' => 'Fees & Billing',
            'whatsapp' => 'WhatsApp Log',
            'reports' => 'Financial Reports',
        ],
        'items' => [
            'dashboard' => [
                'title' => 'Centralized Management Dashboard',
                'desc' => 'See today’s active students, total revenue, pending overdues, and upcoming classes on a single screen.',
            ],
            'students' => [
                'title' => 'Every Student Record in One Place',
                'desc' => 'Complete profile containing parent contacts, enrolled courses, attendance history, payment records, and grades.',
            ],
            'attendance' => [
                'title' => 'Smart Attendance in Seconds',
                'desc' => 'Scan QR codes or tap check-in with instant WhatsApp arrival notifications dispatched to parents.',
            ],
            'payments' => [
                'title' => 'Complete Financial Accountability',
                'desc' => 'Record payments, print receipts, and view live debtor lists with one-click reminder triggers.',
            ],
            'whatsapp' => [
                'title' => 'Full Automated Messaging History',
                'desc' => 'Track the delivery status of all automated alerts (attendance, absence, fees, announcements).',
            ],
            'reports' => [
                'title' => 'Know Your Numbers Without Manual Math',
                'desc' => 'Live reports for gross revenue, expenses, net profits, and instructor payouts ready to print and export.',
            ],
        ],
    ],
    'how_it_works' => [
        'badge' => 'Get Started in 3 Simple Steps',
        'title_prefix' => 'How to start with',
        'title_highlight' => 'Taalimu',
        'subtitle' => 'No complex technical setup or onboarding friction. Run your center today.',
        'step1' => [
            'num' => '01',
            'title' => 'Create Your Center Account',
            'desc' => 'Sign up in under 60 seconds with no credit card required.',
        ],
        'step2' => [
            'num' => '02',
            'title' => 'Add or Import Students',
            'desc' => 'Upload your existing Excel spreadsheet or enter students manually into groups.',
        ],
        'step3' => [
            'num' => '03',
            'title' => 'Start Automated Operations',
            'desc' => 'Track attendance, manage fees, and let WhatsApp handle parent communications automatically.',
        ],
    ],
    'trust' => [
        'badge' => 'Security & Reliability',
        'title' => 'Built with high security and privacy standards for your data',
        'subtitle' => 'Your center records, student lists, and financials are completely isolated and protected.',
        'items' => [
            [
                'icon' => 'fa-shield-alt',
                'title' => 'Strict Tenant Isolation',
                'desc' => 'Your center data and database records are isolated; no other center or unauthorized party can access them.',
            ],
            [
                'icon' => 'fa-database',
                'title' => 'Regular Automated Backups',
                'desc' => 'Continuous automated cloud backups protect your center against data loss or local hardware failures.',
            ],
            [
                'icon' => 'fa-user-shield',
                'title' => 'Granular Staff Permissions',
                'desc' => 'Define exact access for receptionists, assistants, and instructors while protecting sensitive financial reports.',
            ],
            [
                'icon' => 'fa-headset',
                'title' => 'Dedicated Onboarding & Support',
                'desc' => 'Our support team helps you set up, import data, and answer questions throughout the week.',
            ],
        ],
    ],
    'pricing' => [
        'badge' => 'Transparent & Fair Pricing',
        'title' => 'Plans tailored to your center size',
        'subtitle' => 'All plans include a full 30-day free trial with no credit card required. Only pay once you see real value.',
        'monthly' => 'Monthly',
        'term' => 'Per Term (Save 15%)',
        'yearly' => 'Yearly (Save 20%)',
        'per_month' => 'per month',
        'per_term' => 'per term',
        'per_year' => 'per year',
        'featured' => 'Most Popular for Centers',
        'trial_days' => ':days-Day Free Trial',
        'cta_free' => 'Start 30-Day Free Trial',
        'cta_paid' => 'Subscribe Now',
        'cta_note' => 'No credit card required • Cancel anytime',
        'bottom_note' => 'Have multiple branches or custom requirements? Contact us for a customized enterprise package.',
        'plans' => [
            'basic' => [
                'name' => 'Starter',
                'description' => 'For individual tutors, small groups, and centers up to 100 students.',
            ],
            'pro' => [
                'name' => 'Growth',
                'description' => 'The best choice for growing tutoring and course centers up to 500 students.',
            ],
            'enterprise' => [
                'name' => 'Institution',
                'description' => 'For larger centers and branch networks with unlimited students and instructors.',
            ],
        ],
    ],
    'faq' => [
        'badge' => 'Frequently Asked Questions',
        'title' => 'Everything you need to know about Taalimu',
        'subtitle' => 'Direct answers to the most common questions from center owners and managers.',
        'items' => [
            [
                'q' => 'Do I need a credit card to start the free trial?',
                'a' => 'Not at all. You can sign up and explore every single feature completely free for 30 full days with zero credit card or billing details required.',
            ],
            [
                'q' => 'How do I migrate my existing student data from Excel?',
                'a' => 'Taalimu includes a 1-click import tool. Upload your Excel (.xlsx) or CSV file, and the system matches columns and creates student records, groups, and phone numbers in seconds.',
            ],
            [
                'q' => 'How do automated WhatsApp alerts work?',
                'a' => 'The system generates formatted notifications for each event (check-in, absence, due fees, test scores). You can dispatch them instantly so parents receive timely updates on their phones.',
            ],
            [
                'q' => 'Can my assistants and staff use the system with restricted permissions?',
                'a' => 'Yes. You can create accounts for receptionists, assistants, and teachers with restricted roles (e.g., allow attendance marking without viewing financial reports or revenue).',
            ],
            [
                'q' => 'Does the system work on mobile phones and tablets?',
                'a' => 'Yes, Taalimu is fully responsive and optimized for smartphones, tablets, and desktop computers, featuring both Arabic (RTL) and English (LTR) layouts.',
            ],
            [
                'q' => 'What happens when the 30-day free trial ends?',
                'a' => 'Your center data remains completely safe and preserved. You can activate any paid plan that fits your center size whenever you’re ready to continue.',
            ],
        ],
    ],
    'cta' => [
        'title' => 'Ready to stop manual administration?',
        'subtitle' => 'Start running your center from one place with Taalimu, and save hours of manual follow-up every single week.',
        'cta_primary' => 'Start Your Free Trial Now',
        'trust_note' => '30-day free trial • No credit card required • Setup in 2 minutes',
    ],
    'footer' => [
        'description' => 'The easiest way to run your educational center without manual administration.',
        'quick_links' => 'Quick Links',
        'features_link' => 'Features',
        'pricing_link' => 'Pricing Plans',
        'faq_link' => 'FAQ',
        'login_link' => 'Center Login',
        'register_link' => 'Create Account',
        'privacy' => 'Privacy Policy',
        'terms' => 'Terms of Service',
        'rights' => 'All rights reserved © ' . date('Y') . ' Taalimu',
    ],
];
