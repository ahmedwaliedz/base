<?php
namespace App\Services\Admin\Export\Strategies;

use App\Services\Admin\Export\Contracts\ExporterInterface;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\Jc;

class WordExporter implements ExporterInterface {
	public function export($query, array $options = []) {
		$rows     = $query->get();
		$columns  = $options['columns'] ?? $this->getDefaultColumns($rows);
		// Translate labels if they are translation keys
		$columns  = array_map(function ($col) {
			if (is_array($col)) {
				$label = $col['label'] ?? '';
				$col['label'] = is_string($label) ? __($label) : $label;
				// sanitize potential invalid XML chars to avoid corrupted docx
				$col['label'] = $this->sanitizeText((string) ($col['label'] ?? ''));
			}
			return $col;
		}, $columns);
		$model    = $options['model'] ?? null;
		$title    = $this->sanitizeText(__("admin/main.export") . ' - ' . class_basename($model ?? 'Model'));
		$filename = strtolower(class_basename($model ?? 'data')) . '-' . now()->format('Ymd-His') . '.docx';

		$phpWord = new PhpWord();
		$phpWord->setDefaultFontName('Arial');
		$phpWord->setDefaultFontSize(11);

		$section = $phpWord->addSection([
			'marginTop'    => 800,
			'marginRight'  => 800,
			'marginBottom' => 800,
			'marginLeft'   => 800,
			'orientation'  => 'landscape',
		]);

		// Title
		$section->addText($title, ['bold' => true, 'size' => 14], ['alignment' => Jc::CENTER]);
		$section->addTextBreak(1);

        // Table styles - set width to 100% of page (pct uses 50ths of a percent → 5000 = 100%)
        $tableStyleName = 'ExportTable';
        $phpWord->addTableStyle($tableStyleName, [
            'borderSize'  => 6,
            'borderColor' => 'dddddd',
            'cellMargin'  => 80,
            'width'       => 5000, // 100%
            'unit'        => 'pct',
        ], [
            'alignment' => Jc::CENTER,
        ]);

		$table = $section->addTable($tableStyleName);

		// Header row
		$headerFont = ['bold' => true];
		$headerPara = ['alignment' => Jc::CENTER];
		$table->addRow();
		foreach ($columns as $col) {
			$label = $this->sanitizeText((string) ($col['label'] ?? ''));
			$table->addCell()->addText($label, $headerFont, $this->paragraphForText($label));
		}

		// Data rows
		foreach ($rows as $row) {
			$table->addRow();
			foreach ($columns as $col) {
				$value = isset($col['value']) && is_callable($col['value'])
					? call_user_func($col['value'], $row)
					: data_get($row, $col['key'] ?? '');
				if (is_array($value) || is_object($value)) {
					$value = json_encode($value, JSON_UNESCAPED_UNICODE);
				}
				$value = $this->sanitizeText((string) $value);
				$table->addCell()->addText($value, [], $this->paragraphForText($value));
			}
		}

		$tmpPath = tempnam(sys_get_temp_dir(), 'export-');
		$docx    = $tmpPath . '.docx';
		// On Windows, tempnam creates the file; ensure clean destination
		if (file_exists($docx)) {
			@unlink($docx);
		}
		$writer = IOFactory::createWriter($phpWord, 'Word2007');
		$writer->save($docx);

		return response()->download($docx, $filename)->deleteFileAfterSend(true);
	}

	protected function sanitizeText(?string $text): string {
		if ($text === null) return '';
		// Ensure UTF-8; if string is not valid UTF-8, convert best-effort
		if (function_exists('mb_detect_encoding') && ! mb_detect_encoding($text, 'UTF-8', true)) {
			$text = @mb_convert_encoding($text, 'UTF-8', 'auto');
		}
		// Drop invalid XML chars that break docx (except tab, LF, CR)
		$text = preg_replace('/[^\x09\x0A\x0D\x20-\x{D7FF}\x{E000}-\x{FFFD}]/u', '', $text);
		// Strip NULs and control leftovers just in case
		$text = str_replace("\x00", '', $text);
		// Remove HTML tags if any were passed in
		$text = strip_tags($text);
		return $text;
	}

	protected function paragraphForText(string $text): array {
		// Basic RTL/LTR alignment based on Arabic character presence
		$hasArabic = $this->containsArabic($text);
		return [
			'alignment' => $hasArabic ? Jc::RIGHT : Jc::LEFT,
		];
	}

	protected function containsArabic(?string $text): bool {
		if (! $text) return false;
		return preg_match('/\p{Arabic}/u', $text) === 1;
	}

	protected function getDefaultColumns($rows) {
		if ($rows->isEmpty()) {
			return [];
		}
		$first = (array) $rows->first();
		return collect($first)->keys()->map(fn($key) => ['key' => $key, 'label' => ucfirst($key)])->toArray();
	}
}


