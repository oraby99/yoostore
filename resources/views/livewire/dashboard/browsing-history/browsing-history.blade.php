<div>
    <div class="container my-5">
        <h5>Browsing History</h5>
        <div class="row mt-3">
            <!-- Search and Date Input -->
            <div class="search-input d-flex ">
                <!-- Search Input -->
                <div class="input-group " style="width: 500px;">
                    <span class="input-group-text">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" class="form-control" placeholder="Search in browsing history" wire:model.debounce.500ms="searchTerm">
                </div>
                <div>
                    <button type="button" class="btn btn-primary" style="margin-left: 10px; width: 80px; font-weight: 600" wire:click="fetchProducts">Find</button>
                </div>



                <!-- Date Picker -->
                <div class="input-group date-picker" style="width: 500px;">
                    <span class="input-group-text">
                        <i class="fa-regular fa-calendar"></i>
                    </span>
                    <input type="date" class="form-control" placeholder="DD/MM/YYYY" wire:model="selectedDate">
                </div>
                <div>
                    <button type="button" class="btn btn-primary" style="margin-left: 10px; width: 80px; font-weight: 600" wire:click="fetchProducts">Find</button>
                </div>
            </div>


            <div class="row mt-5">

                <!-- First Card -->
                @foreach ($products as $product)
                <div class="col-md-4">
                    <div class="card custom-card" style="height: 300px; position: relative; overflow: hidden;">
                        @if ($product->product->in_stock == 0)
                        <span class="badge bg-dark position-absolute top-0 start-0 m-2">Out of stock</span>
                        @else
                        <span class="badge bg-success position-absolute top-0 start-0 m-2">In stock</span>
                        @endif
                        <div style="height: 66.67%; overflow: hidden;">
                            <img src="{{ asset('storage/' . optional($product->product->images->first())->image_path) }}" alt="Product Image" style="width: 100%; height: 100%; object-fit: cover;" />

                        </div>
                        <div class="card-body" style="height: 33.33%;">
                            <div class="rating my-2">
                                <span class="text-warning">★★★★★</span>
                                <span>(738)</span>
                            </div>
                            <h4 class="card-title" style="font-size: 1rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                <a href="{{ route('product', $product->product->id) }}" style="text-decoration: none; color: black">{{ $product->product->name }}</a>
                            </h4>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Toggle Switch -->
            <div class="toggle-switch d-none">
                <label for="toggleHistory">Turn Browsing History on/off</label>
                <input type="checkbox" class="form-check-input" id="toggleHistory">
            </div>
        </div>
    </div>
</div>