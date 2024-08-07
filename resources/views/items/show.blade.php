@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">

        @can('update', Auth::user())
            @if($item)

            <div class="col-md-6 offset-md-3 mb-4">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                <form action="{{ route('items.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h1>Update Item</h1>
                        </div>
                        <div class="card-body">
                            <div class="form-group name">
                                <label for="name">Name</label>
                                <input type="text" id="name" class="form-control" name="name" value="{{ old('name') ? old('name') : $item->name }}" />
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group description">
                                <label for="description">Description</label>
                                <textarea name="description" id="description" class="form-control" rows="5">{{ old('description') ? old('description') : $item->description }}</textarea>
                                @error('description')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group price">
                                <label for="price">Price</label>
                                <input type="number" min="0.00" step="0.01" id="price" name="price" class="form-control" value="{{ old('price') ? old('price') : $item->price }}" />
                                @error('price')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group img">
                                <div class="item-img my-3">
                                    <img src="{{ asset('storage/img/items/' . $item->img) }}" alt="{{ $item->img }}" class="w-100 h-100">
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
            </div>

            @endif

        @else

            @if($item)

                <div class="col-md-6 mb-4 offset-md-3">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h1>{{ $item->name }}</h1>
                            <span class="badge rounded-pill bg-secondary">Price: {{ $item->price }}</span>
                        </div>
                        <div class="card-body">
                            <div class="item-img mb-3">
                                <img src="{{ asset('storage/img/items/' . $item->img) }}" alt="{{ $item->img }}" class="w-100 h-100">
                            </div>
                            <p>
                                {{ $item->description }}
                            </p>
                        </div>
                    </div>
        
                </div>

            @endif

        @endcan


    </div>
</div>
@endsection

@section('js')

<script>

    
</script>

@endsection