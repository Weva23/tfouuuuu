<table class="table align-items-center mb-0">
    <thead>
        <tr>
            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Code</th>
            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Nom</th>
            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($programmes as $programme)
            <tr>
                <td>{{ $programme->code }}</td>
                <td><a href="javascript:void(0)" id="show-programme" data-id="{{ $programme->id }}" >{{ $programme->nom }}</a></td>
                
                <td>
                    <a href="javascript:void(0)" id="edit-programme" data-id="{{ $programme->id }}" class="btn btn-info"><i class="material-icons opacity-10">border_color</i></a>
                    <a href="javascript:void(0)" id="delete-programme" data-id="{{ $programme->id }}" class="btn btn-danger"><i class="material-icons opacity-10">delete</i></a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
{{ $programmes->links() }}