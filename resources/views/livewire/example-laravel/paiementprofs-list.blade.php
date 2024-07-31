
<table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <!-- <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Image</th> -->
                                        <!-- <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">NNI</th> -->
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nom & Prénom</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Portable</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">WhatsApp</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Programme</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Formation</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">types de contrats </th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Montant à Payer</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Montant Payé</th>
                                        <!-- <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Reste à Payer</th> -->
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Mode de Paiement</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date de Paiement</th>
                                        <th class="text-secondary opacity-7">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($paiementprofs as $paiement)
    <tr>
        <td>{{ $paiement->professeur->nomprenom ?? 'N/A' }}</td>
        <td>{{ $paiement->professeur->phone ?? 'N/A' }}</td>
        <td>{{ $paiement->professeur->wtsp ?? 'N/A' }}</td>
        <td>{{ $paiement->session->formation->nom ?? 'N/A' }}</td>
        <td>{{ $paiement->session->nom ?? 'N/A' }}</td>
        <td>{{ $paiement->type->type }}</td>
        <td>{{ $paiement->montant_a_paye }}</td>
        <td>{{ $paiement->montant_paye }}</td>
        <td>{{ $paiement->mode->nom }}</td>
        <td>{{ $paiement->date_paiement }}</td>
        <td><a href="{{ route('generateProfReceipt', ['sessionId' => $paiement->session_id, 'profId' => $paiement->prof_id]) }}" class="btn btn-info" data-toggle="tooltip" title="Imprimer le reçu">
                <i class="material-icons opacity-10">download</i></a></td>
        </tr>
    </tr>
@endforeach

                                </tbody>
                            </table>
                            {{ $paiementprofs->links() }}

                        







