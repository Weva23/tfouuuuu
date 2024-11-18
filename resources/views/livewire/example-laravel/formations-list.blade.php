<table class="table align-items-center mb-0">
    <thead>
        <tr>
            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Code</th>
            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Nom</th>
            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Programme</th>
            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Durée</th>
            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Prix</th>
            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Créé par</th>
            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($formations as $formation)
            <tr>
                <!-- Display Formation Details -->
                <td>{{ $formation->code }}</td>
                <td>{{ $formation->nom }}</td>
                <td>{{ $formation->programme ? $formation->programme->nom : 'N/A' }}</td>
                <td class="text-center">{{ $formation->duree }}</td>
                <td class="text-center">{{ $formation->prix }}</td>
                <td>{{ $formation->createdBy ? $formation->createdBy->name ?? $formation->createdBy->email : 'Non défini' }}</td>

                <!-- Action Buttons -->
                <td class="text-center">
                    <a href="javascript:void(0)" class="btn btn-info" id="edit-formation" data-id="{{ $formation->id }}" data-toggle="tooltip" title="Modifier">
                        <i class="material-icons opacity-10">border_color</i>
                    </a>
                    <a href="javascript:void(0)" class="btn btn-danger" id="delete-formation" data-id="{{ $formation->id }}" data-toggle="tooltip" title="Supprimer">
                        <i class="material-icons opacity-10">delete</i>
                    </a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<!-- Pagination -->
{{ $formations->links() }}
