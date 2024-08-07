@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">

        @foreach ($items as $item)

        <div class="col-md-4 mb-4">
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
                @can('delete', Auth::user())
                    <div class="card-footer d-flex justify-content-between">
                        <a href="{{ route('items.show', $item->id) }}" class="btn btn-warning">Update</a>
                        <a href="#" class="btn btn-danger" onclick="deleteItem({{ $item->id }})">Remove</a>
                    </div>
                @else
                    <div class="card-footer">
                        <a href="{{ route('items.show', $item->id) }}" class="btn btn-warning float-end">View Detail</a>
                        @auth
                            <button class="btn btn-success float-start" onclick="addToCart({{ $item->id }})">Add To Cart</button>
                        @endauth
                    </div>
                @endcan
            </div>

        </div>

        @endforeach

        {{-- Create Model --}}
        <div class="modal fade" id="createModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="createForm">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">Create Item</h5>
                            <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group" id="createInfo">
                            </div>
                            <div class="form-group name">
                                <label for="name">Name</label>
                                <input type="text" id="name" class="form-control" name="name" />
                            </div>
                            <div class="form-group description">
                                <label for="description">Description</label>
                                <textarea name="description" id="description" class="form-control"></textarea>
                            </div>
                            <div class="form-group price">
                                <label for="price">Price</label>
                                <input type="number" min="0.00" step="0.01" id="price" name="price" class="form-control" />
                            </div>
                            <div class="form-group img">
                                <label for="img">Image</label>
                                <input type="file" id="img" class="form-control" name="img">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary close" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        {{-- End Create Model --}}
    </div>
</div>
@endsection

@section('js')

<script>

    // Common Function
        // Message function
        const successMessage = (message, infoEl) => {
            let alertSuccess = document.createElement('span');
            alertSuccess.className = 'alert alert-success form-control';
            alertSuccess.textContent = message;
            infoEl.append(alertSuccess);
        }
        // End Massage function

        // Error Handler function
        const errorHandler = (error, showError) => {
            let imgError = document.createElement('span');
            imgError.className = 'text-danger';
            imgError.textContent = error[0];
            showError.append(imgError);
        }

        const removeError = (showError) => {
            showError.forEach(errorName => {
                if(errorName.querySelector("span")) {
                    errorName.querySelector("span").remove();
                }
            });
        }

        // Remove Error with keyup
        const removeErrorWithKeyup = (showError) => {
            showError.forEach((errorName => {
                errorName.addEventListener('keyup', () => {
                    if(errorName.querySelector('span')) {
                        errorName.querySelector('span').remove();
                    }
                })
            }))
        }
        // End Remove Error with keyup

        // End Error Handler function
    // End Common Function

    // Create Item Modal
    let createFormEl = document.getElementById('createForm');
    let createInfoEl  = document.getElementById('createInfo');
    let createNameEl = document.querySelector('#createModal .name');
    let createDescriptionEl = document.querySelector('#createModal .description');
    let createPriceEl = document.querySelector('#createModal .price');
    let createImgEl = document.querySelector('#createModal .img');
    let createCloseEl = document.querySelectorAll('#createModal .close');

    // Create Item
    createFormEl.addEventListener('submit', (e) => {
        e.preventDefault();
        
        const createItem = new FormData(createFormEl);

        axios.post('{{ route('items.store') }}', createItem, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        })
            .then( (response) => {
                if(response.data.success) {
                    successMessage(response.data.success, createInfoEl);
                }
                createFormEl.reset();
            })
            .catch((error) => {

                // Remove Error
                removeError([createNameEl, createDescriptionEl, createPriceEl, createImgEl]);
                // End Error

                // Show Error
                if(error.response.data.errors.name) {
                    errorHandler(error.response.data.errors.name, createNameEl);
                }
                if(error.response.data.errors.description) {
                    errorHandler(error.response.data.errors.description, createDescriptionEl);
                }
                if(error.response.data.errors.price) {
                    errorHandler(error.response.data.errors.price, createPriceEl);
                }
                if(error.response.data.errors.img) {
                    errorHandler(error.response.data.errors.img, createImgEl);
                }
                // End Error
            });
    });
    // End Create Item

    // Remove Error with keyup
    removeErrorWithKeyup([createNameEl, createPriceEl, createDescriptionEl, createImgEl]);

    // Page Reload
    createCloseEl.forEach(closeButton => {
        closeButton.addEventListener('click', () => {
            location.reload();
        });
    });
    // End Page Reload

    // End Create Item Modal

    // Delete Item
    const deleteItem = (itemId) => {
        if (confirm('Are you sure you want to delete this item?')) {
            fetch(`/items/${itemId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            }).then((response) => {
                return response.json();
            }).then(data => {
                localStorage.setItem('toastrMessage', data.success);
                location.reload();
            }); 
        }
    }
    // End Delete Item

    window.addEventListener('load', () => {
        const message = localStorage.getItem('toastrMessage');
        
        if (message) {
            toastr.success(message, {timeOut: 5000});
            localStorage.removeItem('toastrMessage');
        }
    });

    // AddToCart
    const addToCart = (itemId) => {
        axios({
            method: 'post',
            url: '/cart',
            data: {
                item_id: itemId
            }
        }).then(response => {
            localStorage.setItem('toastrMessage', response.data.success);
            location.reload();
        })
    }
</script>

@endsection