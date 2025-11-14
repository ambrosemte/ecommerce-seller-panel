<div class="container">
     <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold mb-0">Stores List</h3>
    </div>
    
    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Followers</th>
                <th scope="col">Handle</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($stores as $store)
                <tr>
                    <th scope="row">1</th>
                    <td>{{$store['name']}}</td>
                    <td>{{$store['followers_count']}}</td>
                    <td>
                        <button class="btn btn-primary" wire:click="viewStore('{{$store['id']}}')">View</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
