@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">

        <div class="col-md-6 offset-md-3">

            @if(Session::has('success'))
                <div class="alert alert-success">
                    {{ Session::get('success') }}
                </div>
            @endif

            @if ($user)
                <form action="{{ route('user.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('patch')
                    <div class="card">
                        <div class="card-header">
                            <h1>Update Profile</h1>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="name">Name:</label>
                                <input type="text" id="name" class="form-control" value="{{ old('name') ? old('name') : $user->name }}" name="name">
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="email">Eamil:</label>
                                <input type="text" id="email" class="form-control" value="{{ old('email') ? old('email') : $user->email }}" name="email">
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="password">Password:</label>
                                <input type="password" id="password" class="form-control" name="password" value="{{ old('password') ? old('password') : $user->password }}">
                                @error('password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <div class="user-img mb-3 text-center">
                                    <img src="{{ asset('storage/img/users/' . $user->img) }}" alt="{{ $user->img }}" class="w-100 h-100">
                                </div>
                                <label for="img">Image</label>
                                <input type="file" id="img" class="form-control" name="img">
                            </div>
                        </div>
                        <div class="card-footer">
                            <input type="submit" class="btn btn-warning float-end" value="Update">
                        </div>
                    </div>
                </form>
            @endif

        </div>

    </div>
</div>
@endsection

@section('js')

<script>

</script>

@endsection