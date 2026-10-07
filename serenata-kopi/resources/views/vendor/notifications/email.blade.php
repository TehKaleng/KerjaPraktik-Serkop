{{-- Template email notifikasi versi Indonesia (menimpa template bawaan Laravel) --}}
<x-mail::message>
{{-- Sapaan --}}
@if (! empty($greeting))
# {{ $greeting }}
@else
# Halo!
@endif

{{-- Paragraf pembuka --}}
@foreach ($introLines as $line)
{{ $line }}

@endforeach

{{-- Tombol --}}
@isset($actionText)
<x-mail::button :url="$actionUrl" color="primary">
{{ $actionText }}
</x-mail::button>
@endisset

{{-- Paragraf penutup --}}
@foreach ($outroLines as $line)
{{ $line }}

@endforeach

{{-- Salam --}}
Salam hangat,<br>
{{ config('app.name') }}

{{-- Tautan cadangan kalau tombol tidak bisa diklik --}}
@isset($actionText)
<x-slot:subcopy>
Kalau tombol "{{ $actionText }}" tidak bisa diklik, salin dan tempel tautan berikut ke browser: <span class="break-all">[{{ $displayableActionUrl }}]({{ $actionUrl }})</span>
</x-slot:subcopy>
@endisset
</x-mail::message>
