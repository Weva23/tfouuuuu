<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Crud in Laravel </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body>
    

    <div class="container">

        <div class="row">

            <div class="col s12">
                <br>
                <h1>Modifier un Etudiant</h1>
                <hr>

                @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status')}}
                </div>

                @endif

                <ul>
                    @foreach ($errors->all() as $error)
                    <il id="statusAlert" class="alert alert-danger">{{ $error }}</il>
                    @endforeach
                </ul>

                <form action="/update/traitement" method="POST" class="form-group">
                    @csrf

                    <input type="text" name="id" style="display: none;" value="{{ $etudiants->id }}">
                    <div class="form-group">
                        <label for="Nom">Nom</label>
                        <input type="text" class="form-control" id="Nom" name="nom" value="{{ $etudiants->nom }}">
                        
                    </div>
                    <div class="form-group">
                        <label for="Prenom">Prenom</label>
                        <input type="text" class="form-control" id="Prenom" name="prenom" value="{{ $etudiants->prenom }}">
                        
                    </div>
                    <div class="form-group">
                        <label for="Classe" >Classe</label>
                        <input type="text" class="form-control" id="Classe" name="classe"  value="{{ $etudiants->classe }}">
                        
                    </div>
                    <br>
                    <button type="submit" class="btn btn-primary">Modifier un Etudiant</button>
                    
                    <a href="/etudiant" class="btn btn-danger">retourn</a>
                </form>

                
            </div>
            
        </div>
        </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>

  <script>
    // Sélectionnez l'élément d'alerte par son ID
    var statusAlert = document.getElementById('statusAlert');

    // Masquez l'élément d'alerte après 2 secondes
    setTimeout(function() {
        statusAlert.classList.add('hidden');
    }, 2000);
</script>


</html>