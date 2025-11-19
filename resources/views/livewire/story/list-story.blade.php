<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold mb-0">Stories List</h3>
    </div>

    <h4 class="text-secondary mb-3">Summary Stats</h4>
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-bg-primary text-center">
                <div class="card-body">
                    <h5 class="card-title">Total Stories</h5>
                    <h3 class="fw-bold">{{ $totalStories }}</h3>
                </div>
            </div>
        </div>
    </div>

    <h4 class="text-secondary mb-3">Stories Table</h4>
    <table class="table table-bordered table-hover table-striped align-middle">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Caption</th>
                <th>Store</th>
                <th>Product</th>
                <th>Expires</th>
                <th>View</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($stories as $index => $story)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="d-flex align-items-center" style="cursor: pointer;" data-bs-toggle="modal"
                        data-bs-target="#storyModal-{{ $story['id'] }}">

                        @if($story['type'] === 'image')
                            <img src="{{ $story['media_url'] }}" alt="{{ $story['caption'] }}" class="rounded-circle me-2"
                                width="40" height="40">
                        @else
                            <div class="rounded-circle bg-dark d-flex justify-content-center align-items-center me-2"
                                style="width: 40px; height: 40px;">
                                <i class="fas fa-play text-white"></i>
                            </div>
                        @endif

                        {{ $story['caption'] }}
                    </td>

                    <td>{{ $story['store']['name'] }}</td>
                    <td>{{ $story['product']['name'] ?? '-' }}</td>
                    <td>{{ Carbon\Carbon::parse($story['expires_at'])->format('Y-m-d H:i:s') }}</td>
                    {{-- <td>
                        <a href="{{route('product.view', $order['product']['id'])}}" class="btn btn-primary">View
                            Product</a>
                    </td> --}}
                </tr>

                <div class="modal fade" id="storyModal-{{ $story['id'] }}" tabindex="-1">
                    <div class="modal-dialog modal-xl modal-dialog-centered">
                        <div class="modal-content bg-black p-0" style="border: none;">
                            <div class="modal-body p-0 text-center">

                                @if($story['type'] === 'image')
                                    <img src="{{ $story['media_url'] }}" class="img-fluid w-100"
                                        style="max-height: 90vh; object-fit: contain;">
                                @else
                                    <video id="video-{{ $story['id'] }}" controls autoplay class="w-100"
                                        style="max-height: 90vh; object-fit: contain;">
                                        <source src="{{ $story['media_url'] }}" type="video/mp4">
                                    </video>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <tr>
                    <td colspan="4" class="text-center text-muted">No stories found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="d-flex justify-content-end mt-3">
        <nav>
            <ul class="pagination">
                @foreach ($links as $link)
                    <li class="page-item {{ $link['active'] ? 'active' : '' }} {{ $link['url'] ? '' : 'disabled' }}">
                        <a class="page-link" role="button" wire:click.prevent="gotoPage('{{ $link['url'] }}')">
                            {!! $link['label'] !!}
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modals = document.querySelectorAll('.modal');

            modals.forEach(modal => {
                modal.addEventListener('hidden.bs.modal', function () {

                    const video = modal.querySelector('video');

                    if (video) {
                        video.pause();
                        video.currentTime = 0;
                    }
                });
            });
        });
    </script>

</div>
