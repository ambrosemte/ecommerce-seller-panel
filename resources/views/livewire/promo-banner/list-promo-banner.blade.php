<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold mb-0">Promo Banners List</h3>
    </div>

    <h4 class="text-secondary mb-3">Summary Stats</h4>
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-bg-primary text-center">
                <div class="card-body">
                    <h5 class="card-title">Total Banners</h5>
                    <h3 class="fw-bold">{{ $totalBanners }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- Promo Banners Table --}}
    <h4 class="mb-3">Promo Banners Table</h4>

    <table class="table table-bordered table-hover table-striped align-middle">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Subtitle</th>
                <th>Active</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($banners as $index => $banner)
                <tr>
                    <td>{{  $index + 1 }}</td>
                    <td>{{ $banner['title'] }}</td>
                    <td>{{ $banner['subtitle'] }}</td>
                    <td>{{ $banner['is_active'] ? '✅' : '❌' }}</td>
                    <td>
                        <a href="{{ route('promo.banner.edit', ['id' => $banner['id']]) }}" class="btn btn-primary">
                            Edit
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center text-muted">No promo banners found.</td>
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

</div>
