@extends('layouts.app')

@section('title', 'Edit Product - Coffee Street')

@section('content')
<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-header text-center" style="background-color: #F6EBDA;">
                    <h3>Edit <span style="color: #FF902A;">Product</span></h3>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="name" class="form-label"><b>Product Name</b></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                id="name" name="name" value="{{ old('name', $product->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label"><b>Description</b></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                id="description" name="description" rows="3" required>{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="price" class="form-label"><b>Price (in thousands)</b></label>
                                <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" 
                                    id="price" name="price" value="{{ old('price', $product->price) }}" required>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="rating" class="form-label"><b>Rating (0-5)</b></label>
                                <input type="number" step="0.1" min="0" max="5" 
                                    class="form-control @error('rating') is-invalid @enderror" 
                                    id="rating" name="rating" value="{{ old('rating', $product->rating) }}">
                                @error('rating')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="category" class="form-label"><b>Category</b></label>
                            <select class="form-select @error('category') is-invalid @enderror" 
                                id="category" name="category" required>
                                <option value="">Select Category</option>
                                <option value="hot" {{ old('category', $product->category) == 'hot' ? 'selected' : '' }}>Hot</option>
                                <option value="cold" {{ old('category', $product->category) == 'cold' ? 'selected' : '' }}>Cold</option>
                            </select>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label"><b>Product Image</b></label>
                            <div class="mb-2">
                                @if($product->image)
                                    <img src="{{ str_starts_with($product->image, 'images/') ? asset($product->image) : Storage::url($product->image) }}" alt="{{ $product->name }}" 
                                        class="img-thumbnail" style="max-width: 200px;">
                                @endif
                            </div>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                id="image" name="image" accept="image/*">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Leave empty to keep current image. Max size: 2MB. Formats: JPEG, PNG, JPG, GIF</small>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_featured" 
                                name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_featured">
                                <b>Featured Product</b> (Display in "Popular Now" section)
                            </label>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <a href="{{ route('products.index') }}" class="btn btn-secondary rounded-5 px-4">
                                Cancel
                            </a>
                            <button type="submit" class="btn rounded-5 px-4" 
                                style="background-color: #FF902A; color: white;">
                                <i class="fa fa-save"></i> Update Product
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
