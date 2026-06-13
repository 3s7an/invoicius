<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateTypeExports extends Command
{
    protected $signature = 'typescript:exports';

    protected $description = 'Generate resources/js/types/index.ts re-exports from generated.d.ts';

    public function handle(): int
    {
        $generatedPath = resource_path('js/types/generated.d.ts');
        $outputPath = resource_path('js/types/index.ts');

        if (! file_exists($generatedPath)) {
            $this->error("generated.d.ts not found at {$generatedPath}. Run `php artisan typescript:transform` first.");
            return self::FAILURE;
        }

        $content = (string) file_get_contents($generatedPath);

        $resources = $this->extractNames($content, 'Http', 'Resources');
        $forms     = $this->extractNames($content, 'DTOs', 'Forms');
        $enums     = $this->extractNames($content, 'Enums');

        $lines = ['// Resources (BE → FE output, Inertia props)'];
        foreach ($resources as $name) {
            $lines[] = "export type {$name} = App.Http.Resources.{$name}";
        }

        if ($forms) {
            $lines[] = '';
            $lines[] = '// Form Data (FE → BE input, useForm<T>)';
            foreach ($forms as $name) {
                $lines[] = "export type {$name} = App.DTOs.Forms.{$name}";
            }
        }

        if ($enums) {
            $lines[] = '';
            $lines[] = '// Enums';
            foreach ($enums as $name) {
                $lines[] = "export type {$name}Value = App.Enums.{$name}";
            }
        }

        file_put_contents($outputPath, implode("\n", $lines) . "\n");

        $total = count(array_merge($resources, $forms, $enums));
        $this->info("Generated {$outputPath} with {$total} type exports.");

        return self::SUCCESS;
    }

    /**
     * Extract exported type names from a namespace path within App.
     *
     * @return string[]
     */
    private function extractNames(string $content, string ...$nsParts): array
    {
        // Walk into each namespace block in sequence
        array_unshift($nsParts, 'App');
        // Skip the outermost `declare namespace App` — handled specially below
        $current = $content;

        foreach ($nsParts as $i => $part) {
            $keyword = $i === 0 ? 'declare namespace' : 'namespace';
            $pos = $this->findNamespace($current, $keyword . ' ' . $part);
            if ($pos === null) {
                return [];
            }
            $current = $this->extractBlock($current, $pos);
            if ($current === null) {
                return [];
            }
        }

        preg_match_all('/export type (\w+)\s*=/', $current, $matches);

        return $matches[1] ?? [];
    }

    /** Find the position of `{` that opens the given namespace declaration. */
    private function findNamespace(string $content, string $declaration): ?int
    {
        $pos = strpos($content, $declaration);
        if ($pos === false) {
            return null;
        }
        $bracePos = strpos($content, '{', $pos + strlen($declaration));
        return $bracePos !== false ? $bracePos : null;
    }

    /**
     * Given a position of `{` in $content, extract the text between
     * the matching `{` and `}` (not including the braces themselves).
     */
    private function extractBlock(string $content, int $openBrace): ?string
    {
        $depth = 0;
        $start = $openBrace + 1;
        $len = strlen($content);

        for ($i = $openBrace; $i < $len; $i++) {
            if ($content[$i] === '{') {
                $depth++;
            } elseif ($content[$i] === '}') {
                $depth--;
                if ($depth === 0) {
                    return substr($content, $start, $i - $start);
                }
            }
        }

        return null;
    }
}
