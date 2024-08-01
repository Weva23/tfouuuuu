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
        @forelse($sessions as $session)
            @foreach($session->etudiants as $etudiant)
                <tr>
                    <td>{{ $etudiant->nomprenom ?? 'N/A' }}</td>
                    <td>{{ $etudiant->phone ?? 'N/A' }}</td>
                    <td>{{ $etudiant->wtsp ?? 'N/A' }}</td>
                    <td>{{ $etudiant->paiements->first()->note_test ?? 'N/A' }}</td>
                    <td>{{ $session->formation->prix ?? 'N/A' }}</td>
                    <td>{{ $etudiant->paiements->sum('prix_reel') ?? 'N/A' }}</td>
                    <td>{{ $etudiant->paiements->sum('montant_paye') ?? 'N/A' }}</td>
                    <td>{{ $session->formation->prix - $etudiant->paiements->sum('montant_paye') ?? 'N/A' }}</td>
                    <td>
                        <a href="{{ route('generateReceipt', ['sessionId' => $session->id, 'etudiantId' => $etudiant->id]) }}" class="btn btn-info" data-toggle="tooltip" title="Imprimer le reçu">
                            <i class="material-icons opacity-10">download</i>
                        </a>
                    </td>
                </tr>
            @endforeach
        @empty
            <tr>
                <td colspan="9" class="text-center">Aucun étudiant trouvé</td>
            </tr>
        @endforelse
    </tbody>
</table>
{{ $sessions->links() }}
