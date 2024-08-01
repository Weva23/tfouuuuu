<!-- resources/views/livewire/example-laravel/sessions-list.blade.php -->

@if ($sessions->count() > 0)
    <table class="table align-items-center mb-0">
        <thead>
            <tr>
                <th>Nom & Prénom</th>
                <th>Phone</th>
                <th>WhatsApp</th>
                <th>Note du Teste</th>
                <th>Prix Programme</th>
                <th>Prix Réel</th>
                <th>Montant Payé</th>
                <th>Reste à Payer</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sessions as $session)
                @foreach ($session->etudiants as $etudiant)
                    @php
                        $resteAPayer = $etudiant->pivot->prix_reel - $etudiant->pivot->montant_paye;
                    @endphp
                    <tr>
                        <td>{{ $etudiant->nomprenom }}</td>
                        <td>{{ $etudiant->phone }}</td>
                        <td>{{ $etudiant->wtsp }}</td>
                        <td>{{ $etudiant->note_test }}</td>
                        <td>{{ $session->formation->prix }}</td>
                        <td>{{ $etudiant->pivot->prix_reel }}</td>
                        <td>{{ $etudiant->pivot->montant_paye }}</td>
                        <td>{{ $resteAPayer }}</td>
                        <td>
                            <button class="btn btn-dark" onclick="openAddPaymentModal({{ $etudiant->id }}, {{ $session->id }})" data-toggle="tooltip" title="Ajouter un paiement">
                                <i class="material-icons opacity-10">payment</i>
                            </button>
                            <button class="btn btn-danger" onclick="deleteStudentFromSession({{ $etudiant->id }}, {{ $session->id }})" data-toggle="tooltip" title="Retirer">
                                <i class="material-icons opacity-10">delete_forever</i>
                            </button>
                            <a href="/sessions/{{ $session->id }}/generateReceipt/{{ $etudiant->id }}" class="btn btn-info" data-toggle="tooltip" title="Imprimer le reçu">
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
        <p>Aucun étudiant trouvé pour cette session.</p>
    </div>
@endif