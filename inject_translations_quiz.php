<?php
$files = ['ar', 'en', 'fr'];
foreach ($files as $lang) {
    $path = __DIR__ . '/Modules/Center/resources/lang/' . $lang . '/messages.php';
    if (!file_exists($path)) {
        continue;
    }
    $content = file_get_contents($path);
    $keys = [];
    if ($lang == 'ar') {
        $keys = [
            'blade_quiz_modal_title' => 'كيف أضيف اختباراً جديداً؟',
            'blade_quiz_modal_desc' => 'للحفاظ على تسلسل المادة العلمية، يتم إضافة الاختبارات مباشرة كجزء من الدروس في دوراتك.',
            'blade_quiz_modal_steps_title' => 'الخطوات:',
            'blade_quiz_modal_step1' => '1. اذهب إلى إدارة الدورات.',
            'blade_quiz_modal_step2' => '2. اختر الدورة ثم القسم ثم الدرس المراد تقييمه.',
            'blade_quiz_modal_step3' => '3. انقر على "إضافة اختبار" ضمن محتوى الدرس.',
            'blade_quiz_modal_close' => 'إغلاق',
            'blade_quiz_modal_go' => 'الذهاب إلى الدورات'
        ];
    } elseif ($lang == 'en') {
        $keys = [
            'blade_quiz_modal_title' => 'How to add a new quiz?',
            'blade_quiz_modal_desc' => 'To maintain the progression of the material, quizzes are added directly as part of the lessons in your courses.',
            'blade_quiz_modal_steps_title' => 'Steps:',
            'blade_quiz_modal_step1' => '1. Go to Course Management.',
            'blade_quiz_modal_step2' => '2. Select the course, then the section, then the lesson.',
            'blade_quiz_modal_step3' => '3. Click on "Add Quiz" within the lesson content.',
            'blade_quiz_modal_close' => 'Close',
            'blade_quiz_modal_go' => 'Go to Courses'
        ];
    } elseif ($lang == 'fr') {
        $keys = [
            'blade_quiz_modal_title' => 'Comment ajouter un nouveau quiz?',
            'blade_quiz_modal_desc' => 'Pour maintenir la progression du materiel, les quiz sont ajoutes directement dans les lecons de vos cours.',
            'blade_quiz_modal_steps_title' => 'Etapes:',
            'blade_quiz_modal_step1' => '1. Allez dans la Gestion des cours.',
            'blade_quiz_modal_step2' => '2. Selectionnez le cours, puis la section, puis la lecon.',
            'blade_quiz_modal_step3' => '3. Cliquez sur "Ajouter un quiz" dans le contenu de la lecon.',
            'blade_quiz_modal_close' => 'Fermer',
            'blade_quiz_modal_go' => 'Aller aux cours'
        ];
    }
    
    $str = "";
    foreach ($keys as $k => $v) {
        $str .= "    '" . $k . "' => '" . addslashes($v) . "',\n";
    }
    $content = preg_replace('/\];/', $str . '];', $content);
    file_put_contents($path, $content);
}
echo "Done\n";
