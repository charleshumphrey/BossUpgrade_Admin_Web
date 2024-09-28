@if (session()->has('success'))
<div class="notification bg-green-300">
    <div class="flex flex-col md:flex-row items-center justify-between space-y-2 md:space-y-0">
        <div>
            <span class="icon has-text-success">
                <i class="fas fa-check-square"></i>
            </span>
            <b>{{ session('success') }}</b>
        </div>
        <button type="button" class="button delete is-large small textual --jb-notification-dismiss">Dismiss</button>
    </div>
</div>
@endif