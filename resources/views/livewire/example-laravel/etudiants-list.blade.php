<table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Image</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">NNI</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nom & Prénom</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Nationalité</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Diplôme</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Genre</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Lieu de naissance</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Addresse</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Date de naissance</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">EMAIL</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Portable</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">WhatsApp</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                                        <th class="text-secondary opacity-7"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($etudiants as $etudiant)
                                    <tr>
                                        <td>{{ $etudiant->id }}</td>
                                        <!-- <td><img src="{{ asset('images/'.$etudiant->image)}}" alt="" width="60px"></td> -->
                                        <td>
                                            @if ($etudiant->image)
                                                <img src="{{ asset('images/' . $etudiant->image) }}" alt="Image de l'étudiant" width="60px">
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>{{ $etudiant->nni ?? 'N/A' }}</td>
                                        <td>{{ $etudiant->nomprenom ?? 'N/A' }}</td>
                                        <td data-country-id="{{ $etudiant->country_id }}">{{ $etudiant->country->name ?? 'N/A' }}</td>
                                        <td>{{ $etudiant->diplome ?? 'N/A' }}</td>
                                        <td>{{ $etudiant->genre ?? 'N/A' }}</td>
                                        <td>{{ $etudiant->lieunaissance ?? 'N/A' }}</td>
                                        <td>{{ $etudiant->adress ?? 'N/A' }}</td>
                                        <td>{{ $etudiant->datenaissance ?? 'N/A' }}</td>
                                        <td>{{ $etudiant->email ?? 'N/A' }}</td>
                                        <td>{{ $etudiant->phone ?? 'N/A' }}</td>
                                        <td>{{ $etudiant->wtsp ?? 'N/A' }}</td>
                                        <td>
                                            <a href="javascript:void(0)" id="edit-etudiant" data-id="{{ $etudiant->id }}" class="btn btn-info"><i class="material-icons opacity-10">border_color</i></a>
                                            <a href="javascript:void(0)" id="delete-etudiant" data-id="{{ $etudiant->id }}" class="btn btn-danger"><i class="material-icons opacity-10">delete</i></a>
                                            <a href="javascript:void(0)" class="btn btn-info detail-etudiant" data-id="{{ $etudiant->id }}"  data-toggle="tooltip" title="Détails de l'Étudiant"><i class="material-icons opacity-10">info</i></a> 
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $etudiants->links() }}