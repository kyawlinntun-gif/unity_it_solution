@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">

        <div class="col-md-6 offset-md-3">

            @if(count($customers))
                <table class="table table-dark table-striped">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($customers as $customer)
                            <tr>
                                <td scope="row">{{ $customer->id }}</td>
                                <td>{{ $customer->name }}</td>
                                <td>{{ $customer->email }}</td>
                                <td>
                                    @can('delete', Auth::user())
                                        <button class="btn btn-danger" onclick="deleteCustomer({{ $customer->id }})">Delete</button>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

        </div>

    </div>
</div>
@endsection

@section('js')

<script>
    const deleteCustomer = (id) => {
        axios({
            method: 'delete',
            url: `/customers/${id}`,
        }).then((response) => {
            localStorage.setItem('toastrMessage', response.data.success);
            location.reload();
        });
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