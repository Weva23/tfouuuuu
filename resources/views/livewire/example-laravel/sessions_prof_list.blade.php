<!-- resources/views/livewire/example-laravel/sessions_prof_list.blade.php -->

@if ($sessions->count() > 0)
    <table class="table align-items-center mb-0">
        <thead>
            <tr>
                <th>Nom & Prénom</th>
                <th>Phone</th>
                <th>WhatsApp</th>
                <th>Montant</th>
                <th>Montant à Payer</th>
                <th>Montant Payé</th>
                <th>Reste à Payer</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sessions as $session)
                @foreach ($session->professeurs as $professeur)
                    @php
                        $paiementProf = $professeur->paiementprofs->where('session_id', $session->id)->first();
                        $resteAPayer = $paiementProf ? $paiementProf->montant_a_paye - $paiementProf->montant_paye : 0;
                    @endphp
                    <tr>
                        <td>{{ $professeur->nomprenom }}</td>
                        <td>{{ $professeur->phone }}</td>
                        <td>{{ $professeur->wtsp }}</td>
                        <td>{{ $paiementProf ? $paiementProf->montant : '' }}</td>
                        <td>{{ $paiementProf ? $paiementProf->montant_a_paye : '' }}</td>
                        <td>{{ $paiementProf ? $paiementProf->montant_paye : '' }}</td>
                        <td>{{ $resteAPayer }}</td>
                        <td>
                            <button class="btn btn-dark" onclick="openAddProfPaymentModal({{ $professeur->id }}, {{ $session->id }})" data-toggle="tooltip" title="Ajouter un paiement">
                                <i class="material-icons opacity-10">payment</i>
                            </button>
                            <button class="btn btn-danger" onclick="deleteProfFromSession({{ $professeur->id }}, {{ $session->id }})" data-toggle="tooltip" title="Retirer">
                                <i class="material-icons opacity-10">delete_forever</i>
                            </button>
                            <a href="/sessions/{{ $session->id }}/generateProfReceipt/{{ $professeur->id }}" class="btn btn-info" data-toggle="tooltip" title="Imprimer le reçu">
                                <i class="material-icons opacity-10">download</i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
@else
    <div class="text-center">
        <p>Aucun professeur trouvé pour cette session.</p>
    </div>
@endif
