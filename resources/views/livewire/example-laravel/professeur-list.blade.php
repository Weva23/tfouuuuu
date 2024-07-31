
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Image</th>
                                        <!-- <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">NNI</th> -->
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nom & Prénom</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">types de contrats </th>
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
                                    @foreach($profs as $prof)
                                    <tr>
                                        <td>{{ $prof->id ?? 'N/A' }}</td>
                                        <!-- <td><img src="{{ asset('images/'.$prof->image)}}" alt="" width="60px"></td> -->
                                        <td>
                                            @if ($prof->image)
                                                <img src="{{ asset('images/' . $prof->image) }}" alt="Image de l'étudiant" width="60px">
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>{{ $prof->nomprenom ?? 'N/A' }}</td>
                                        <td data-type-id="{{ $prof->type_id }}">{{ $prof->type->type ?? 'N/A' }}</td>
                                        <td data-country-id="{{ $prof->country_id }}">{{ $prof->country->name ?? 'N/A' }}</td>
                                        <td>{{ $prof->diplome ?? 'N/A' }}</td>
                                        <td>{{ $prof->genre ?? 'N/A' }}</td>
                                        <td>{{ $prof->lieunaissance ?? 'N/A' }}</td>
                                        <td>{{ $prof->adress ?? 'N/A' }}</td>
                                        <td>{{ $prof->datenaissance ?? 'N/A' }}</td>
                                        <td>{{ $prof->email ?? 'N/A' }}</td>
                                        <td>{{ $prof->phone ?? 'N/A' }}</td>
                                        <td>{{ $prof->wtsp ?? 'N/A' }}</td>

                                        <td>
                                            <a href="javascript:void(0)" id="edit-prof" data-id="{{ $prof->id }}" class="btn btn-info"><i class="material-icons opacity-10">border_color</i></a>
                                            <a href="javascript:void(0)" id="delete-prof" data-id="{{ $prof->id }}" class="btn btn-danger"><i class="material-icons opacity-10">delete</i></a>
                                            <a href="javascript:void(0)" class="btn btn-info detail-prof" data-id="{{ $prof->id }}" data-toggle="tooltip" title="Détails du Professeur"><i class="material-icons opacity-10">info</i></a> 

                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $profs->links() }}
                        