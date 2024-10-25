<div class="card mb-4">
    <div class="card-header">Account Settings</div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3 text-center">
                <div class="account-img"></div>
            </div>
            <div class="col-md-9">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Display Name</label>
                        <input type="text" class="form-control" placeholder="Display Name">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Full Name</label>
                        <input type="text" class="form-control" placeholder="Full Name" wire:model="name">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Email</label>
                        <input type="email" class="form-control" placeholder="Email" wire:model="email">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Phone Number</label>
                        <input type="text" class="form-control" placeholder="Phone Number" wire:model="phone">
                    </div>
                </div>
                <button class="btn btn-custom" wire:click="savePersonalInfo">Save Changes</button>
                @if (session()->has('message'))
                <div class="alert alert-success mt-3">
                    {{ session('message') }}
                </div>
                @endif
            </div>

        </div>
    </div>
</div>