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
                                    <th>Description</th>
                                    <th>price</th>
                                    <th>Paid</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                    <tr>
                                        <td>{{ $order->customer_name }}</td>
                                        <td>{{ $order->item_name }}</td>
                                        <td>{{ $order->description }}</td>
                                        <td>{{ $order->price }}</td>
                                        <td>{{ $order->paid ? "Yes" : "No" }}</td>
                                    </tr>
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