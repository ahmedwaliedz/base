<?php
namespace App\Services\Admin\Export\Strategies;

use App\Services\Admin\Export\Contracts\ExporterInterface;
use App\Services\Admin\Export\Support\ExportColumnResolver;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\Jc;

class WordExporter implements ExporterInterface {
	public function export($query, array $options = []) {
		$columns  = ! empty($options['columns'])
			? $options['columns']
			: $this->getDefaultColumns($query);
		$columns  = array_map(function ($col) {
			if (is_array($col)) {
				$label = $col['label'] ?? '';
				$col['label'] = is_string($label) ? __($label) : $label;
				$col['label'] = $this->sanitizeText((string) ($col['label'] ?? ''));
			}
			return $col;
		}, $columns);

		$rows     = $query->get();
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

		$section->addText($title, ['bold' => true, 'size' => 14], ['alignment' => Jc::CENTER]);
		$section->addTextBreak(1);

        $tableStyleName = 'ExportTable';
        $phpWord->addTableStyle($tableStyleName, [
            'borderSize'  => 6,
            'borderColor' => 'dddddd',
            'cellMargin'  => 80,
            'width'       => 5000,
            'unit'        => 'pct',
        ], [
            'alignment' => Jc::CENTER,
        ]);

		$table = $section->addTable($tableStyleName);

		$headerFont = ['bold' => true];
		$headerPara = ['alignment' => Jc::CENTER];
		$table->addRow();
		foreach ($columns as $col) {
			$label = $this->sanitizeText((string) ($col['label'] ?? ''));
			$table->addCell()->addText($label, $headerFont, $this->paragraphForText($label));
		}

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
		if (file_exists($docx)) {
			@unlink($docx);
		}
		$writer = IOFactory::createWriter($phpWord, 'Word2007');
		$writer->save($docx);

		return response()->download($docx, $filename)->deleteFileAfterSend(true);
	}

	protected function sanitizeText(?string $text): string {
		if ($text === null) return '';
		if (function_exists('mb_detect_encoding') && ! mb_detect_encoding($text, 'UTF-8', true)) {
			$text = @mb_convert_encoding($text, 'UTF-8', 'auto');
		}
		$text = preg_replace('/[^\x09\x0A\x0D\x20-\x{D7FF}\x{E000}-\x{FFFD}]/u', '', $text);
		$text = str_replace("\x00", '', $text);
		$text = strip_tags($text);
		return $text;
	}

	protected function paragraphForText(string $text): array {
		$hasArabic = $this->containsArabic($text);
		return [
			'alignment' => $hasArabic ? Jc::RIGHT : Jc::LEFT,
		];
	}

	protected function containsArabic(?string $text): bool {
		if (! $text) return false;
		return preg_match('/\p{Arabic}/u', $text) === 1;
	}

	protected function getDefaultColumns($query) {
        $first = (clone $query)->limit(1)->get()->first();

		if (! $first) {
			return [];
		}

		return ExportColumnResolver::columnsFromSample($first);
	}
}
