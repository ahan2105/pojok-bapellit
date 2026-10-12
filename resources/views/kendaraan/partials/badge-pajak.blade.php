@php
    $cardStyles = [
        'red' => ['classes' => 'bg-red-600 text-white border-red-700 font-semibold'],
        'yellow' => ['classes' => 'bg-amber-50 text-amber-700 border-amber-200'],
        'blue' => ['classes' => 'bg-blue-50 text-blue-700 border-blue-200'],
        'green' => ['classes' => 'bg-emerald-50 text-emerald-700 border-emerald-200'],
        'gray' => ['classes' => 'bg-gray-50 text-gray-500 border-gray-200'],
    ];

    $style = $cardStyles[$pajak->warna] ?? $cardStyles['gray'];
@endphp

<div class="border p-3 {{ $style['classes'] }}">
    <p class="text-xs font-medium">{{ $pajak->label_sisa }}</p>
</div>
    