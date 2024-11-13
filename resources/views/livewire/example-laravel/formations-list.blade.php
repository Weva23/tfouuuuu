<table class="table align-items-center mb-0">
    <thead>
        <tr>
            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Code</th>
            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Nom</th>
            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Programme</th>
            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Durée</th>
            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Prix</th>
            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($formations as $formation)
            <tr>
                <td>{{ $formation->code }}</td>
                <td><a href="javascript:void(0)" id="show-formation" data-id="{{ $formation->id }}" >{{ $formation->nom }}</a></td>
                <td>{{ $formation->programme ? $formation->programme->nom : 'N/A' }}</td> <!-- Affiche le nom du programme -->
                <td>{{ $formation->duree }}</td>
                <td>{{ $formation->prix }}</td>
                <td>
                    <button class="btn btn-primary" onclick="showContents({{ $formation->id }})" data-toggle="tooltip" title="Liste des contenus de la programme"><i class="material-icons opacity-10">chat</i></button>
                    <a href="javascript:void(0)" id="edit-formation" data-id="{{ $formation->id }}" class="btn btn-info"><i class="material-icons opacity-10">border_color</i></a>
                    <a href="javascript:void(0)" id="delete-formation" data-id="{{ $formation->id }}" class="btn btn-danger"><i class="material-icons opacity-10">delete</i></a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
{{ $formations->links() }}
