@if (session()->has('error'))
<div class="w-full p-2 bg-red-300" role="alert">
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif