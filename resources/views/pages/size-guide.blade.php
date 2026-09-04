@extends('layouts.customer-care')

@php
    $sizeGuide = $careData['size_guide'] ?? [];
    $ringSizes = !empty($sizeGuide['ring_sizes']) ? $sizeGuide['ring_sizes'] : [
        ['indian' => '8', 'us' => '4.5', 'diameter' => '15.3 mm'],
        ['indian' => '10', 'us' => '5.5', 'diameter' => '16.1 mm'],
        ['indian' => '12', 'us' => '6.0', 'diameter' => '16.5 mm'],
        ['indian' => '14', 'us' => '7.0', 'diameter' => '17.3 mm'],
        ['indian' => '16', 'us' => '8.0', 'diameter' => '18.1 mm'],
        ['indian' => '18', 'us' => '8.5', 'diameter' => '18.9 mm'],
        ['indian' => '20', 'us' => '9.5', 'diameter' => '19.8 mm'],
    ];
@endphp

@section('title', 'Size Guide | Aura Fine Jewellery')
@section('meta_description', 'Find your perfect fit for rings, necklaces, bracelets, and bangles with our sizing charts.')

@section('care_content')
<h2>{{ $sizeGuide['title'] ?? 'Size Guide' }}</h2>
<p style="margin-bottom: 2rem; color: #666; line-height: 1.7;">
    {{ $sizeGuide['intro'] ?? 'Ensuring the perfect fit is essential for comfortable and elegant wear. Explore our comprehensive sizing guides below.' }}
</p>

<h3 style="font-family: 'Cinzel', serif; font-size: 1.3rem; margin: 2rem 0 1rem; color: #9c7f4c;">{{ $sizeGuide['ring_title'] ?? 'Ring Sizing Chart' }}</h3>
<p style="color: #666; line-height: 1.6; font-size: 0.95rem;">{{ $sizeGuide['ring_desc'] ?? 'The most accurate way to determine your ring size is by visiting an Aura boutique. You can also measure the internal diameter of an existing ring that fits well.' }}</p>

<div style="overflow-x: auto; margin-top: 1.5rem; margin-bottom: 3rem;">
    <table style="width: 100%; border-collapse: collapse; min-width: 450px;">
        <thead>
            <tr style="background: #fafafa; border-bottom: 2px solid #9c7f4c;">
                <th style="padding: 1rem; text-align: left; font-family: 'Cinzel', serif;">Indian Size</th>
                <th style="padding: 1rem; text-align: left; font-family: 'Cinzel', serif;">US / Intl Size</th>
                <th style="padding: 1rem; text-align: left; font-family: 'Cinzel', serif;">Internal Diameter (mm)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ringSizes as $r)
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 0.9rem 1rem;">{{ $r['indian'] ?? '' }}</td>
                    <td style="padding: 0.9rem 1rem;">{{ $r['us'] ?? '' }}</td>
                    <td style="padding: 0.9rem 1rem;">{{ $r['diameter'] ?? '' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<h3 style="font-family: 'Cinzel', serif; font-size: 1.3rem; margin: 2rem 0 1rem; color: #9c7f4c;">{{ $sizeGuide['necklace_title'] ?? 'Necklace Lengths' }}</h3>
<p style="color: #666; line-height: 1.7; font-size: 0.95rem;">
    {{ $sizeGuide['necklace_desc'] ?? 'Our necklaces typically range from 16 to 24 inches. A 16-inch necklace rests loosely near the collarbone, while an 18-inch necklace rests on the collarbone (the most versatile length). Most Aura pendants include an adjustable 16-18 inch chain.' }}
</p>
@endsection
