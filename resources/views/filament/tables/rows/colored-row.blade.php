@php
    $color = $getRecord()->backend_user?->parameter?->expense_color ?? null;
@endphp

<tr
    {{
        $getRecordClasses()
            ?->merge(['style' => $color ? "background-color: {$color}33;" : null])
            ->toHtmlAttributes()
    }}
>
    {{ $slot }}
</tr>
