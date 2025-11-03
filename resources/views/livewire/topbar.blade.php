<!-- Topbar -->
<div class="w-100 bg-light shadow-sm px-4 d-flex align-items-center justify-content-between"
    style="height: 70px; position: relative;">

    <!-- Left: Search -->
    <form class="d-flex" role="search">
        <input class="form-control me-2" type="search" placeholder="Search..." aria-label="Search">
        <button class="btn btn-outline-primary" type="submit">Search</button>
    </form>

    <!-- Right: User Profile + Logout -->
    <div class="d-flex align-items-center">
        <img src="https://dummyimage.com/40" alt="Profile" class="rounded-circle me-2" width="40" height="40">
        <span class="me-5">{{ $name }}</span>
        <button type="submit" class="btn btn-outline-danger btn-sm" wire:loading.attr="disabled" wire:click="logout">
            <span wire:loading.remove>Logout</span>
            <span wire:loading>
                <span class="spinner-border spinner-border-sm me-1"></span> Processing...
            </span>
        </button>
    </div>

</div>
