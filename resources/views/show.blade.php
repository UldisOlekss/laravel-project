
@extends('layout')

@section('title', "$currencyName Show Page")

@section('content')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.4.1/chart.min.js" integrity="sha512-5vwN8yor2fFT9pgPS9p9R7AszYaNn0LkQElTXIsZFCL7ucT8zDCAqlQXDdaqgA1mZP47hdvztBMsIoFxq/FyyQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <div class="row py-5">
        <canvas id="myChart" width="400" height="400"></canvas>
        <script>
            const currencyChart = {!! $currencyChart !!};
            var ctx = document.getElementById('myChart').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    datasets: [{
                        data: currencyChart,
                        borderColor: [
                            'rgb(255,56,60)',
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        </script>
    </div>
    <div class="row">
        <div class="col">
            <h3 class="col text-center">
                {{ $currencyName }}
            </h3>
            <div class="col text-center">
                Current history for {{ $currencyName }} currency
            </div>
            <div class="row pagination-link">
                <a class="nav-link d-block btn-back px-4 py-2 border rounded ml-3 mb-3" href="{{ route('currency.index') }}">
                    Back
                </a>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Rate</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($currencyRates as $item)
                    <tr>
                        <td>{{ $item->rate }}</td>
                        <td>{{ $item->datetime }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="row pagination-link">
        {{ $currencyRates->links("pagination::bootstrap-4" )}}
        <a class="nav-link d-block btn-back px-4 py-2 border rounded ml-3 mb-3" href="{{ route('currency.index') }}">
            Back
        </a>
    </div>
    <div class="row">
    </div>
@endsection

