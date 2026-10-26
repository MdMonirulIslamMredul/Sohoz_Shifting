<div class="row g-3">
    <div class="col-md-6"><label class="form-label">Title</label><input name="title" class="form-control"
            value="{{ old('title', $product->title ?? '') }}" required></div>
    <div class="col-md-6"><label class="form-label">Slug</label><input name="slug" class="form-control"
            value="{{ old('slug', $product->slug ?? '') }}"></div>
    <div class="col-md-4"><label class="form-label">Category</label>
        <select name="product_category_id" id="product_category_id" class="form-select">
            <option value="">Select category</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}"
                    {{ old('product_category_id', $product->product_category_id ?? '') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4"><label class="form-label">Subcategory</label>
        <select name="product_subcategory_id" id="product_subcategory_id" class="form-select">
            <option value="">Select subcategory</option>
            @foreach ($subcategories as $subcategory)
                <option value="{{ $subcategory->id }}" data-category="{{ $subcategory->product_category_id }}"
                    {{ old('product_subcategory_id', $product->product_subcategory_id ?? '') == $subcategory->id ? 'selected' : '' }}>
                    {{ $subcategory->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4"><label class="form-label">Price</label><input name="price" type="number" step="0.01"
            class="form-control" value="{{ old('price', $product->price ?? '') }}"></div>
    <div class="col-md-4"><label class="form-label">Sort Order</label><input name="sort_order" type="number"
            class="form-control" value="{{ old('sort_order', $product->sort_order ?? 0) }}"></div>
    <div class="col-md-2 form-check mt-4"><input class="form-check-input" type="checkbox" name="is_featured"
            value="1" {{ old('is_featured', $product->is_featured ?? false) ? 'checked' : '' }}><label
            class="form-check-label">Featured</label></div>
    <div class="col-md-2 form-check mt-4"><input class="form-check-input" type="checkbox" name="status" value="1"
            {{ old('status', $product->status ?? true) ? 'checked' : '' }}><label
            class="form-check-label">Active</label></div>
    <div class="col-12"><label class="form-label">Short Description</label><input name="short_description"
            class="form-control" value="{{ old('short_description', $product->short_description ?? '') }}"></div>
    <div class="col-12"><label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="4">{{ old('description', $product->description ?? '') }}</textarea>
    </div>
    <div class="col-12"><label class="form-label">Image</label><input type="file" name="image"
            class="form-control">

        @if (isset($product) && $product->image)
            <div class="mt-2">
                <img src="{{ asset('storage/' . $product->image) }}" alt="Product Image" style="max-width: 200px;">
            </div>
        @endif


        <!-- Image preview script -->
        <script>
            document.querySelector('input[name="image"]').addEventListener('change', function(event) {
                var reader = new FileReader();
                reader.onload = function() {
                    var img = document.querySelector('.image-preview');
                    if (!img) {
                        img = document.createElement('img');
                        img.classList.add('image-preview', 'mt-2');
                        document.querySelector('input[name="image"]').parentNode.appendChild(img);
                    }
                    img.src = reader.result;
                    img.style.maxWidth = '200px';
                }
                reader.readAsDataURL(event.target.files[0]);
            });
        </script>


    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var categorySelect = document.getElementById('product_category_id');
            var subcategorySelect = document.getElementById('product_subcategory_id');
            if (!categorySelect || !subcategorySelect) {
                return;
            }

            var subcategories = Array.from(subcategorySelect.querySelectorAll('option'));

            function filterSubcategories() {
                var selectedCategory = categorySelect.value;
                subcategories.forEach(function(option) {
                    if (!option.value) {
                        option.hidden = false;
                        return;
                    }
                    option.hidden = selectedCategory ? option.dataset.category !== selectedCategory : false;
                });
            }

            categorySelect.addEventListener('change', filterSubcategories);
            filterSubcategories();
        });
    </script>
@endpush
