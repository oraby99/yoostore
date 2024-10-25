@if (session()->has('success'))
<div class="alert alert-success mt-3">
    {{ session('success') }}
</div>
@endif
<div class="row">
    <div class="col-xl-6">
        <div class="card mb-4">
            <div class="card-header">Home Address</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Name</label>
                        <input type="text" class="form-control" placeholder="First Name" wire:model="homeName">
                        @error('homeName') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Flat Number</label>
                        <input type="text" class="form-control" placeholder="Flat Number" wire:model="homeFlat">
                        @error('homeFlat') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Country</label>
                        <input type="text" class="form-control" placeholder="Country" wire:model="homeCountry">
                        @error('homeCountry') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Landmark</label>
                        <input type="text" class="form-control" placeholder="Landmark" wire:model="homeLandmark">
                        @error('homeLandmark') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Area</label>
                        <input type="text" class="form-control" placeholder="Area" wire:model="homeArea">
                        @error('homeArea') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>City</label>
                        <input type="text" class="form-control" placeholder="City" wire:model="homeCity">
                        @error('homeCity') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Street</label>
                        <input type="text" class="form-control" placeholder="Street" wire:model="homeStreet">
                        @error('homeStreet') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-md-12 mb-3">
                        <label>Phone Number</label>
                        <input type="text" class="form-control" placeholder="Phone Number" wire:model="homePhone">
                        @error('homePhone') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
                <button class="btn btn-custom" wire:click="saveHomeAddress">Save Changes</button>
            </div>

        </div>
    </div>

    <div class="col-xl-6">
        <div class="card mb-4">
            <div class="card-header">Shipping Address</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label>Name</label>
                        <input type="text" class="form-control" placeholder="First Name" wire:model="officeName">
                        @error('officeCountry') <span class="text-danger">{{ $message }}</span> @enderror

                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Flat Number</label>
                        <input type="text" class="form-control" placeholder="Flat Number" wire:model="officeFlat">
                        @error('officeFlat') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Country</label>
                        <input type="text" class="form-control" placeholder="Country" wire:model="officeCountry">
                        @error('officeCountry') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Landmark</label>
                        <input type="text" class="form-control" placeholder="Landmark" wire:model="officeLandmark">
                        @error('officeLandmark') <span class="text-danger">{{ $message }}</span> @enderror

                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Area</label>
                        <input type="text" class="form-control" placeholder="Area" wire:model="officeArea">
                        @error('officeArea') <span class="text-danger">{{ $message }}</span> @enderror

                    </div>

                    <div class="col-md-6 mb-3">
                        <label>City</label>
                        <input type="text" class="form-control" placeholder="City" wire:model="officeCity">
                        @error('officeCity') <span class="text-danger">{{ $message }}</span> @enderror

                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Street</label>
                        <input type="text" class="form-control" placeholder="Street" wire:model="officeStreet">
                        @error('officeStreet') <span class="text-danger">{{ $message }}</span> @enderror

                    </div>

                    <div class="col-md-12 mb-3">
                        <label>Phone Number</label>
                        <input type="text" class="form-control" placeholder="Phone Number" wire:model="officePhone">
                        @error('officePhone') <span class="text-danger">{{ $message }}</span> @enderror

                    </div>
                </div>
                <button class="btn btn-custom" wire:click="saveOfficeAddress">Save Changes</button>
            </div>
        </div>
    </div>


    
    <div class="col-xl-12">
    <div class="card mb-4">
        <div class="card-header">Addresses</div>
        <div class="card-body">
            <div class="row">
                @foreach($addresses as $address)
                    <div class="col-md-6 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">{{ $address->name }}</h5>
                                <p class="card-text">
                                    {{ $address->flat_number }}, {{ $address->street }}, {{ $address->area }}<br>
                                    {{ $address->city }}, {{ $address->country }}<br>
                                    Phone: {{ $address->phone }}
                                </p>
                                <input 
                                    type="radio" 
                                    wire:click="setDefault({{ $address->id }})" 
                                    name="defaultAddress" 
                                    value="{{ $address->id }}" 
                                    @if($address->is_default) checked @endif
                                > Choose as default
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

</div>