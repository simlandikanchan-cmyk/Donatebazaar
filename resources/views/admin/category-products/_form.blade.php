<div class="row">

    <div class="col-md-6 mb-3">
        <label>Category</label>

        <select name="category_id" class="form-control" required>

            <option value="">Select Category</option>

            @foreach($categories as $category)

                <option
                    value="{{ $category->id }}"
                    {{ old('category_id', $categoryProduct->category_id ?? '') == $category->id ? 'selected' : '' }}
                >
                    {{ $category->name }}
                </option>

            @endforeach

        </select>
    </div>

    <div class="col-md-6 mb-3">
        <label>Product Name</label>

        <input
            type="text"
            name="name"
            class="form-control"
            value="{{ old('name', $categoryProduct->name ?? '') }}"
            required
        >
    </div>

    <div class="col-md-6 mb-3">
        <label>Price</label>

        <input
            type="number"
            step="0.01"
            name="price"
            class="form-control"
            value="{{ old('price', $categoryProduct->price ?? '') }}"
            required
        >
    </div>

    <div class="col-md-6 mb-3">
        <label>Stock</label>

        <input
            type="number"
            name="stock"
            class="form-control"
            value="{{ old('stock', $categoryProduct->stock ?? 0) }}"
            required
        >
    </div>

    <div class="col-md-6 mb-3">
        <label>Product Type</label>

        <select name="product_type" class="form-control">

            <option value="physical">Physical</option>

            <option value="digital"
                {{ old('product_type', $categoryProduct->product_type ?? '') == 'digital' ? 'selected' : '' }}>
                Digital
            </option>

            <option value="service"
                {{ old('product_type', $categoryProduct->product_type ?? '') == 'service' ? 'selected' : '' }}>
                Service
            </option>

            <option value="donation"
                {{ old('product_type', $categoryProduct->product_type ?? '') == 'donation' ? 'selected' : '' }}>
                Donation
            </option>

        </select>
    </div>

    <div class="col-md-6 mb-3">

        <label>Image</label>

        <input
            type="file"
            name="image"
            class="form-control"
        >

        @if(!empty($categoryProduct?->image))
            <img
                src="{{ asset('storage/' . $categoryProduct->image) }}"
                width="80"
                class="mt-2 rounded"
            >
        @endif

    </div>

    <div class="col-md-12 mb-3">

        <label>Description</label>

        <textarea
            name="description"
            rows="4"
            class="form-control"
        >{{ old('description', $categoryProduct->description ?? '') }}</textarea>

    </div>

    <div class="col-md-12 mb-3">

        <div class="form-check">

            <input
                type="checkbox"
                name="is_active"
                value="1"
                class="form-check-input"
                {{ old('is_active', $categoryProduct->is_active ?? true) ? 'checked' : '' }}
            >

            <label class="form-check-label">
                Active Product
            </label>

        </div>

    </div>

</div>