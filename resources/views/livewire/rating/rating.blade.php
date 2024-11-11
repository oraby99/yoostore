<div class="star-rating">
    @for ($i = 1; $i <= 5; $i++)
        <span 
            class="star" 
            wire:click="setRating({{ $i }})"
            style="color: {{ $i <= $rating ? '#ffcc00' : '#ccc' }}; font-size: 2rem; cursor: pointer; transition: color 0.2s;"
        >
            &#9733;
        </span>
    @endfor
    @if (session()->has('error'))
        <div class="alert alert-success mt-3 w-75">
            {{ session('error') }}
        </div>
    @endif
</div>
