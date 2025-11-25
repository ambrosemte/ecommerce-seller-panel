<style>
    .sidebar-toggle {
        display: none;
        position: fixed;
        top: 15px;
        left: 15px;
        background: #202224;
        color: white;
        border: none;
        padding: 10px 14px;
        border-radius: 4px;
        font-size: 18px;
        z-index: 9999;
    }

    @media (max-width: 768px) {

        /* Show toggle button on mobile */
        .sidebar-toggle {
            display: block;
        }
    }
</style>

<!-- Topbar -->
<div class="w-100 bg-light shadow-sm px-4 d-flex align-items-center justify-content-between"
    style="height: 70px; position: relative;">

    <!-- Left: div -->
    <div class="d-flex align-items-center">
        <button class="sidebar-toggle" onclick="toggleSidebar()">
            ☰
        </button>
    </div>

    <!-- Right: User Profile + Logout -->
    <div class="d-flex align-items-center">
        <img src="{{ asset('images/default.png') }}" alt="Profile" class="rounded-circle me-2" width="40" height="40">
        <span class="me-5">{{ $name }}</span>
        <button type="submit" class="btn btn-outline-danger btn-sm" wire:loading.attr="disabled" wire:click="logout">
            <span wire:loading.remove>Logout</span>
            <span wire:loading>
                <span class="spinner-border spinner-border-sm me-1"></span> Processing...
            </span>
        </button>
    </div>

</div>
