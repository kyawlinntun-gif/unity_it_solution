@extends('layouts.app')

@section('content')
@php
    $price = 0;
@endphp
<div class="container">
    <div class="row">

        <div class="col-md-8 offset-md-2">

            @if (Session::has('success'))
                <div class="alert alert-success">
                    {{ Session::get('success') }}
                </div>
            @endif

            @if(count($items))

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h1>Cart List</h1>
                        <span class="badge rounded-pill bg-secondary">Cart: {{ Auth::user()->getCartCount() }}</span>
                    </div>
                    <div class="card-body">
                        <table class="table table-dark table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">Name</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">Image</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $item)
                                    @php
                                        $price += $item->price;
                                    @endphp
                                    <tr>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->description }}</td>
                                        <td>{{ $item->price }}</td>
                                        <td>
                                            <div class="item-img my-3">
                                                <img src="{{ asset('storage/img/items/' . $item->img) }}" alt="{{ $item->img }}" class="w-100 h-100">
                                            </div>
                                        </td>
                                        <td>
                                            <button class="btn btn-danger" onclick="removeFromCart({{ $item->id }})">Remove</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="display-6">
                                        Total: {{ $price }}
                                    </td>
                                    <td colspan="2">
                                        <button class="btn btn-lg  btn-primary float-end" onclick="document.getElementById('paid').submit();">Paid</button>
                                        <form action="{{ route('carts.paid') }}" method="POST" id="paid">
                                            @csrf
                                        </form>
                                    </td>
                                </tr>
                            </tfoot>
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
// RemoveFromCart

const removeFromCart = (itemId) => {
    axios({
        method: 'delete',
        url: `/cart/${itemId}`
    }).then(response => {
        localStorage.setItem('toastrMessage', response.data.success);
        location.reload();
    })
}

window.addEventListener('load', () => {
    const message = localStorage.getItem('toastrMessage');
    
    if (message) {
        toastr.success(message, {timeOut: 5000});
        localStorage.removeItem('toastrMessage');
    }
});

</script>

@endsection