@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">

        <div class="col-md-8 offset-md-2">

            @if(count($orders))

                <div class="card">
                    <div class="card-header">
                        Order List
                    </div>
                    <div class="card-body">
                        <table class="table table-dark">
                            <thead>
                                <tr>
                                    <th>Customer Name</th>
                                    <th>Item name</th>
                                    <th>Price</th>
                                    <th>Count</th>
                                    <th>Paid</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $customer => $items)
                                    @foreach ($items['item_name'] as $item => $details)
                                    <tr>
                                        <td>{{ $customer }}</td>
                                        <td>{{ $item }}</td>
                                        <td>{{ $details['total_price'] }}</td>
                                        <td>{{ $details['total_count'] }}</td>
                                        <td>{{ $items['item_paid'][0] ? "Yes" : "No" }}</td>
                                    </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            @endif

        </div>

    </div>
</div>
@endsection

@section('js')

<script>

</script>

@endsection