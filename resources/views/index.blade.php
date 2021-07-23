
@extends('layout')

@section('title', 'Currency Index Page')

@section('content')
<div class="row py-5">
    <div class="col">
        <table class="table">
            <thead>
            <tr>
                <th>Currency</th>
                <th>Rate</th>
                <th>Date</th>
                <th>Link</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($currencies as $currency)
                <tr>
                    <td class="px-3">{{ $currency->name }}</td>
                    <td class="px-3">{{ $currency->latest_rate['rate'] }}</td>
                    <td class="px-3">{{ $currency->latest_rate['datetime'] }}</td>
                    <td class="px-3">
                        <a class="px-4 py-2 text-center border rounded border-primary" href="{{ route('currency.show', $currency['id']) }}">
                            History
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="row pagination-link">
    {{ $currencies->links("pagination::bootstrap-4" )}}
</div>
@endsection
