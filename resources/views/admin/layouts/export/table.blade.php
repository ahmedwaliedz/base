<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? __('admin/main.export') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet" />
    <style>
        :root{--text:#222;--muted:#666;--border:#e4e6eb}
        *{box-sizing:border-box}
        html,body{margin:0;padding:0}
        body{font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif;color:var(--text);background:#fff}
        .export-container{padding:24px}
        .export-header{margin-bottom:16px}
        .export-title{margin:0 0 8px;font-size:20px;font-weight:600}
        .export-meta{margin:0;color:var(--muted);font-size:12px}
        table{width:100%;border-collapse:collapse}
        thead th{font-weight:600;background:#fafbfc}
        th,td{border:1px solid var(--border);padding:10px 12px;text-align:left;vertical-align:middle}
        .nowrap{white-space:nowrap}
        @media print {
            .export-container{padding:0}
            .export-meta{display:none}
            thead{display: table-header-group;}
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="export-container">
        <div class="export-header">
            <h1 class="export-title">{{ $title ?? '' }}</h1>
            <p class="export-meta">{{ now()->format('Y-m-d H:i') }}</p>
        </div>

        <table>
            <thead>
                <tr>
                    @foreach(($columns ?? []) as $col)
                        <th class="nowrap">{{ $col['label'] ?? $col['key'] ?? '' }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @forelse(($rows ?? []) as $row)
                    <tr>
                        @foreach(($columns ?? []) as $col)
                            @php
                                $val = null;
                                if (isset($col['value']) && is_callable($col['value'])) { $val = call_user_func($col['value'], $row); }
                                elseif (isset($col['key'])) { $val = data_get($row, $col['key']); }
                            @endphp
                            <td>{!! e(is_array($val) || is_object($val) ? json_encode($val, JSON_UNESCAPED_UNICODE) : (string) $val) !!}</td>
                        @endforeach
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count($columns ?? []) }}" style="text-align:center;color:var(--muted);padding:24px">{{ __('admin/main.no_data_found') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @stack('scripts')
    @if(request()->has('_print'))
    <script>
        window.addEventListener('load', function(){
            try { window.print(); } catch(_) {}
        });
    </script>
    @endif
</body>
</html>

