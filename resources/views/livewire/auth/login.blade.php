<div class="d-flex align-items-center justify-content-center vh-100 bg-light">
    <div class="card shadow-sm" style="width: 100%; max-width: 400px;">
        <div class="card-body p-4">
            <h4 class="text-center mb-4 fw-bold">
                <i class="las la-store-alt"></i> Seller Panel
            </h4>

            <form wire:submit.prevent="login">
                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold">Email address</label>
                    <input wire:model.defer="email" id="email" type="email" class="form-control @error('email') is-invalid @enderror" placeholder="Enter your email">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold">Password</label>
                    <input wire:model.defer="password" id="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter your password">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="form-check">
                        <input wire:model="remember" class="form-check-input" type="checkbox" id="remember">
                        <label class="form-check-label" for="remember">Remember me</label>
                    </div>
                    <a href="#" class="text-decoration-none small text-muted">Forgot Password?</a>
                </div>

                <button type="submit" class="btn btn-primary w-100" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="login">Login</span>
                    <span wire:loading wire:target="login">
                        <span class="spinner-border spinner-border-sm me-1"></span> Processing...
                    </span>
                </button>
            </form>
        </div>

        <div class="card-footer text-center small text-muted py-2">
            &copy; {{ date('Y') }} Seller Panel
        </div>
    </div>
</div>
