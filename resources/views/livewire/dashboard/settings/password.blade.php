<div class="col-md-12">
    <div class="card mb-4">
        <div class="card-header">Change Password</div>
        <div class="card-body">
            @if (session()->has('success'))
                <div class="alert alert-success mt-3">
                    {{ session('success') }}
                </div>
            @endif

            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label>Current Password</label>
                            <input type="password" class="form-control" placeholder="Current Password" wire:model="currentPassword">
                            @error('currentPassword') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-12 mb-3">
                            <label>New Password</label>
                            <input type="password" class="form-control" placeholder="New Password" wire:model="newPassword">
                            @error('newPassword') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-12 mb-3">
                            <label>Confirm New Password</label>
                            <input type="password" class="form-control" placeholder="Confirm New Password" wire:model="confirmNewPassword">
                            @error('confirmNewPassword') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <button class="btn btn-custom" wire:click="saveChanges">Save Changes</button>
                </div>
            </div>
        </div>
    </div>
</div>
