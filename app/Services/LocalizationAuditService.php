<?php

namespace App\Services;

use Illuminate\Support\Facades\File;

class LocalizationAuditService
{
    protected array $languages = ['ar', 'en', 'fr'];
    protected array $translationFiles = [];
    protected array $loadedTranslations = [];
    protected array $referencedKeys = [];
    protected array $hardcodedStrings = [];
    protected array $syntaxErrors = [];

    public function __construct(array $languages = ['ar', 'en', 'fr'])
    {
        $this->languages = $languages;
    }

    /**
     * Run full localization audit.
     */
    public function audit(): array
    {
        $this->loadTranslationFiles();
        $this->scanSourceCode();

        $missingKeys = $this->findMissingKeys();
        $unusedKeys = $this->findUnusedKeys();
        $discrepancies = $this->findKeyDiscrepancies();

        return [
            'summary' => [
                'total_languages' => count($this->languages),
                'total_translation_files' => count($this->translationFiles),
                'total_referenced_keys' => count($this->referencedKeys),
                'total_missing_keys' => count($missingKeys),
                'total_unused_keys' => count($unusedKeys),
                'total_hardcoded_strings' => count($this->hardcodedStrings),
                'total_syntax_errors' => count($this->syntaxErrors),
            ],
            'languages' => $this->languages,
            'missing_keys' => $missingKeys,
            'unused_keys' => $unusedKeys,
            'discrepancies' => $discrepancies,
            'hardcoded_strings' => $this->hardcodedStrings,
            'syntax_errors' => $this->syntaxErrors,
        ];
    }

    /**
     * Load all translation files (app & modules).
     */
    protected function loadTranslationFiles(): void
    {
        $paths = [
            lang_path(),
            resource_path('lang'),
        ];

        // Include Modules lang directories
        $modulesPath = base_path('Modules');
        if (File::isDirectory($modulesPath)) {
            foreach (File::directories($modulesPath) as $moduleDir) {
                $moduleLang = $moduleDir . '/resources/lang';
                if (File::isDirectory($moduleLang)) {
                    $paths[] = $moduleLang;
                }
            }
        }

        foreach ($paths as $baseLangDir) {
            if (!File::isDirectory($baseLangDir)) {
                continue;
            }

            foreach ($this->languages as $lang) {
                $langDir = $baseLangDir . '/' . $lang;
                if (!File::isDirectory($langDir)) {
                    continue;
                }

                foreach (File::allFiles($langDir) as $file) {
                    if ($file->getExtension() === 'php') {
                        $this->loadPhpTranslation($file->getPathname(), $lang, $baseLangDir);
                    } elseif ($file->getExtension() === 'json') {
                        $this->loadJsonTranslation($file->getPathname(), $lang);
                    }
                }
            }
        }
    }

    protected function loadPhpTranslation(string $filePath, string $lang, string $baseDir): void
    {
        $filename = pathinfo($filePath, PATHINFO_FILENAME);
        $this->translationFiles[] = $filePath;

        try {
            $content = require $filePath;
            if (is_array($content)) {
                $flattened = $this->flattenArray($content, $filename);
                foreach ($flattened as $key => $val) {
                    $this->loadedTranslations[$lang][$key] = $val;
                }
            }
        } catch (\Throwable $e) {
            $this->syntaxErrors[] = [
                'file' => $filePath,
                'error' => $e->getMessage(),
            ];
        }
    }

    protected function loadJsonTranslation(string $filePath, string $lang): void
    {
        $this->translationFiles[] = $filePath;
        $content = File::get($filePath);
        $decoded = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->syntaxErrors[] = [
                'file' => $filePath,
                'error' => json_last_error_msg(),
            ];
            return;
        }

        if (is_array($decoded)) {
            foreach ($decoded as $key => $val) {
                $this->loadedTranslations[$lang][$key] = $val;
            }
        }
    }

    /**
     * Flatten multi-dimensional array into dot notation.
     */
    protected function flattenArray(array $array, string $prefix = ''): array
    {
        $result = [];
        foreach ($array as $key => $value) {
            $newKey = $prefix !== '' ? $prefix . '.' . $key : $key;
            if (is_array($value)) {
                $result = array_merge($result, $this->flattenArray($value, $newKey));
            } else {
                $result[$newKey] = $value;
            }
        }
        return $result;
    }

    /**
     * Scan source code for translation functions and hardcoded strings.
     */
    protected function scanSourceCode(): void
    {
        $scanPaths = [
            app_path(),
            resource_path('views'),
            base_path('Modules'),
        ];

        $patterns = [
            '/__(?:\(\s*[\'"]([^\'"]+)[\'"]\s*\))/U',
            '/trans(?:\(\s*[\'"]([^\'"]+)[\'"]\s*\))/U',
            '/trans_choice(?:\(\s*[\'"]([^\'"]+)[\'"]\s*\))/U',
            '/@lang\(\s*[\'"]([^\'"]+)[\'"]\s*\)/U',
            '/Lang::get\(\s*[\'"]([^\'"]+)[\'"]\s*\)/U',
            '/Lang::choice\(\s*[\'"]([^\'"]+)[\'"]\s*\)/U',
        ];

        foreach ($scanPaths as $scanDir) {
            if (!File::isDirectory($scanDir)) {
                continue;
            }

            foreach (File::allFiles($scanDir) as $file) {
                $ext = $file->getExtension();
                if (!in_array($ext, ['php', 'blade.php', 'js', 'vue'])) {
                    continue;
                }

                $content = File::get($file->getPathname());

                // Find translation references
                foreach ($patterns as $pattern) {
                    if (preg_match_all($pattern, $content, $matches)) {
                        foreach ($matches[1] as $key) {
                            $this->referencedKeys[$key] = ($this->referencedKeys[$key] ?? 0) + 1;
                        }
                    }
                }

                // Check hardcoded strings in Blade views
                if (str_contains($file->getFilename(), '.blade.php')) {
                    $this->detectHardcodedStringsInBlade($file->getPathname(), $content);
                }
            }
        }
    }

    protected function detectHardcodedStringsInBlade(string $filePath, string $content): void
    {
        // Simple heuristic for unlocalized English text inside tags
        if (preg_match_all('/>\s*([A-Z][a-zA-Z\s]{4,30})\s*</', $content, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $string = trim($match[1]);
                if (!in_array($string, ['PHP', 'HTML', 'CSS', 'JS', 'URL', 'API', 'EGP', 'USD', 'EUR', 'CANCEL', 'OK'])) {
                    $this->hardcodedStrings[] = [
                        'file' => str_replace(base_path() . DIRECTORY_SEPARATOR, '', $filePath),
                        'string' => $string,
                    ];
                }
            }
        }
    }

    /**
     * Find keys referenced in code but missing from language files.
     */
    protected function findMissingKeys(): array
    {
        $missing = [];
        foreach ($this->referencedKeys as $key => $count) {
            foreach ($this->languages as $lang) {
                if (!isset($this->loadedTranslations[$lang][$key])) {
                    $missing[] = [
                        'key' => $key,
                        'language' => $lang,
                        'references_count' => $count,
                    ];
                }
            }
        }
        return $missing;
    }

    /**
     * Find keys present in translation files but never referenced in code.
     */
    protected function findUnusedKeys(): array
    {
        $unused = [];
        foreach ($this->languages as $lang) {
            if (isset($this->loadedTranslations[$lang])) {
                foreach ($this->loadedTranslations[$lang] as $key => $val) {
                    if (!isset($this->referencedKeys[$key])) {
                        $unused[] = [
                            'key' => $key,
                            'language' => $lang,
                        ];
                    }
                }
            }
        }
        return $unused;
    }

    /**
     * Find discrepancies where a key exists in one language but missing in another.
     */
    protected function findKeyDiscrepancies(): array
    {
        $allKeys = [];
        foreach ($this->languages as $lang) {
            if (isset($this->loadedTranslations[$lang])) {
                foreach (array_keys($this->loadedTranslations[$lang]) as $key) {
                    $allKeys[$key][$lang] = true;
                }
            }
        }

        $discrepancies = [];
        foreach ($allKeys as $key => $presentLangs) {
            $missingIn = [];
            foreach ($this->languages as $lang) {
                if (!isset($presentLangs[$lang])) {
                    $missingIn[] = $lang;
                }
            }
            if (!empty($missingIn)) {
                $discrepancies[] = [
                    'key' => $key,
                    'present_in' => implode(',', array_keys($presentLangs)),
                    'missing_in' => implode(',', $missingIn),
                ];
            }
        }
        return $discrepancies;
    }

    /**
     * Export report to JSON.
     */
    public function exportJson(string $path, array $data): bool
    {
        return File::put($path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) !== false;
    }

    /**
     * Export report to Markdown.
     */
    public function exportMarkdown(string $path, array $data): bool
    {
        $md = "# 🌐 Localization Audit Report\n\n";
        $md .= "Generated at: " . date('Y-m-d H:i:s') . "\n\n";

        $md .= "## Summary\n\n";
        foreach ($data['summary'] as $metric => $val) {
            $md .= "- **" . ucwords(str_replace('_', ' ', $metric)) . ":** {$val}\n";
        }

        $md .= "\n## Missing Keys (" . count($data['missing_keys']) . ")\n\n";
        $md .= "| Key | Language | Ref Count |\n| :--- | :--- | :--- |\n";
        foreach (array_slice($data['missing_keys'], 0, 50) as $item) {
            $md .= "| `{$item['key']}` | {$item['language']} | {$item['references_count']} |\n";
        }

        $md .= "\n## Key Discrepancies (" . count($data['discrepancies']) . ")\n\n";
        $md .= "| Key | Present In | Missing In |\n| :--- | :--- | :--- |\n";
        foreach (array_slice($data['discrepancies'], 0, 50) as $item) {
            $md .= "| `{$item['key']}` | {$item['present_in']} | {$item['missing_in']} |\n";
        }

        $md .= "\n## Hardcoded Strings (" . count($data['hardcoded_strings']) . ")\n\n";
        $md .= "| File | String |\n| :--- | :--- |\n";
        foreach (array_slice($data['hardcoded_strings'], 0, 50) as $item) {
            $md .= "| `{$item['file']}` | `{$item['string']}` |\n";
        }

        return File::put($path, $md) !== false;
    }
}
