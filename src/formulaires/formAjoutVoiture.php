<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="../bootstrap/css/baseForm.css">
    <title>Ajout d'une voiture</title>
</head>
<body>
    <section class="container">
        <h3 class="text-center mb-4">Ajout d'une voiture</h3>
        <form action="../traitement.cacher.php" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="code">Code</label>
                        <input type="text" id="code" name="code" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="marque">Marque</label>
                        <select id="marque" name="marque" class="form-control" required onchange="updateModeles()">
                            <option value="">Sélectionnez une marque</option>
                            <option value="Audi">Audi</option>
                            <option value="BMW">BMW</option>
                            <option value="Citroën">Citroën</option>
                            <option value="Dacia">Dacia</option>
                            <option value="Ford">Ford</option>
                            <option value="Honda">Honda</option>
                            <option value="Hyundai">Hyundai</option>
                            <option value="Kia">Kia</option>
                            <option value="Mercedes">Mercedes</option>
                            <option value="Nissan">Nissan</option>
                            <option value="Opel">Opel</option>
                            <option value="Peugeot">Peugeot</option>
                            <option value="Renault">Renault</option>
                            <option value="Seat">Seat</option>
                            <option value="Skoda">Skoda</option>
                            <option value="Toyota">Toyota</option>
                            <option value="Volkswagen">Volkswagen</option>
                            <option value="Volvo">Volvo</option>
                            <option value="autre">Autre marque...</option>
                        </select>
                    </div>
                    <div class="form-group" id="autre-marque-container" style="display: none;">
                        <label for="autre-marque">Précisez la marque</label>
                        <input type="text" id="autre-marque" name="autre-marque" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="modele">Modèle</label>
                        <select id="modele" name="modele" class="form-control" required onchange="checkAutreModele()">
                            <option value="">Sélectionnez d'abord une marque</option>
                        </select>
                    </div>
                    <div class="form-group" id="autre-modele-container" style="display: none;">
                        <label for="autre-modele">Précisez le modèle</label>
                        <input type="text" id="autre-modele" name="autre-modele" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="tarif_reserv">Tarif de réservation</label>
                        <input type="number" id="tarif_reserv" name="tarif_reserv" class="form-control" min="10000" required>
                        <label for="">(min 10 000 FCFA)</label>
                    </div>
                    <div class="form-group">
                        <label for="qt_initial">Quantité initiale</label>
                        <input type="number" id="qt_initial" name="qt_initial" class="form-control" min="1" required>
                    </div>
                    <div class="form-group">
                        <label for="qt_disponible">Quantité disponible</label>
                        <input type="number" id="qt_disponible" name="qt_disponible" class="form-control" min="1" required>
                    </div>
                </div>
                <div>
                    <label>Photos de voiture (max 5 fichiers, max 100 Ko chacun):</label>
                    <input type="file" name="photos[]" id="photos" class="clique" accept="image/*" multiple required>
                    <input type="hidden" name="MAX_FILE_SIZE" value="100000">
                </div>
                <div class="col-md-12 text-center mt-3">
                    <button type="submit" name="ajouter" class="btn btn-primary" value="ajouter">Ajouter</button>
                </div>
                <div class="col-md-12">
                    <?php
                    if(isset($_GET["error"]) && $_GET["error"] == "exist") {
                        echo "<p class='error-message mt-3 text-center'>La voiture existe déjà</p>";
                    }
                    ?>
                </div>
            </div>
        </form>
    </section>

    <script src="../bootstrap/js/jquery.slim.min.js"></script>
    <script src="../bootstrap/js/popper.min.js"></script>
    <script src="../bootstrap/js/bootstrap.min.js"></script>
    <script>
        function updateModeles() {
            const marque = document.getElementById('marque').value;
            const modeleSelect = document.getElementById('modele');
            const autreMarqueContainer = document.getElementById('autre-marque-container');
            const autreModeleContainer = document.getElementById('autre-modele-container');
            
            if (marque === 'autre') {
                autreMarqueContainer.style.display = 'block';
                document.getElementById('autre-marque').required = true;
            } else {
                autreMarqueContainer.style.display = 'none';
                document.getElementById('autre-marque').required = false;
            }
            
            modeleSelect.innerHTML = '';
            
            if (!marque || marque === 'autre') {
                const option = document.createElement('option');
                option.value = '';
                option.textContent = 'Sélectionnez d\'abord une marque';
                modeleSelect.appendChild(option);
                
                if (marque === 'autre') {
                    const autreOption = document.createElement('option');
                    autreOption.value = 'autre';
                    autreOption.textContent = 'Précisez le modèle...';
                    modeleSelect.appendChild(autreOption);
                }
                
                autreModeleContainer.style.display = 'none';
                document.getElementById('autre-modele').required = false;
                return;
            }
            
            const modeles = {
                'Audi': ['A1', 'A3', 'A4', 'A5', 'A6', 'A7', 'A8', 'Q2', 'Q3', 'Q5', 'Q7', 'Q8', 'TT'],
                'BMW': ['Série 1', 'Série 2', 'Série 3', 'Série 4', 'Série 5', 'Série 6', 'Série 7', 'X1', 'X2', 'X3', 'X4', 'X5', 'X6', 'X7'],
                'Citroën': ['C1', 'C3', 'C4', 'C5', 'Berlingo', 'C3 Aircross', 'C4 Cactus', 'SpaceTourer'],
                'Dacia': ['Sandero', 'Duster', 'Logan', 'Spring', 'Jogger'],
                'Ford': ['Fiesta', 'Focus', 'Kuga', 'Puma', 'Mondeo', 'Mustang', 'Ranger', 'Transit'],
                'Honda': ['Civic', 'Jazz', 'CR-V', 'HR-V', 'e'],
                'Hyundai': ['i10', 'i20', 'i30', 'Kona', 'Tucson', 'Santa Fe', 'IONIQ'],
                'Kia': ['Picanto', 'Rio', 'Ceed', 'Sportage', 'Niro', 'Sorento', 'EV6'],
                'Mercedes': ['Classe A', 'Classe B', 'Classe C', 'Classe E', 'Classe S', 'GLA', 'GLC', 'GLE', 'GLS'],
                'Nissan': ['Micra', 'Juke', 'Qashqai', 'X-Trail', 'Leaf'],
                'Opel': ['Corsa', 'Astra', 'Crossland', 'Grandland', 'Mokka', 'Insignia'],
                'Peugeot': ['108', '208', '308', '508', '2008', '3008', '5008', 'Rifter'],
                'Renault': ['Clio', 'Captur', 'Mégane', 'Arkana', 'Scénic', 'Talisman', 'Kadjar', 'Koleos', 'ZOE'],
                'Seat': ['Ibiza', 'Leon', 'Arona', 'Ateca', 'Tarraco'],
                'Skoda': ['Fabia', 'Octavia', 'Kamiq', 'Karoq', 'Kodiaq', 'Superb'],
                'Toyota': ['Aygo', 'Yaris', 'Corolla', 'C-HR', 'RAV4', 'Prius', 'Camry', 'Land Cruiser'],
                'Volkswagen': ['Polo', 'Golf', 'T-Cross', 'T-Roc', 'Tiguan', 'Touran', 'Passat', 'Arteon', 'ID.3', 'ID.4'],
                'Volvo': ['XC40', 'XC60', 'XC90', 'S60', 'S90', 'V60', 'V90']
            };
            
            const defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.textContent = 'Sélectionnez un modèle';
            modeleSelect.appendChild(defaultOption);
            
            if (modeles[marque]) {
                modeles[marque].forEach(modele => {
                    const option = document.createElement('option');
                    option.value = modele;
                    option.textContent = modele;
                    modeleSelect.appendChild(option);
                });
            }
            
            const autreOption = document.createElement('option');
            autreOption.value = 'autre';
            autreOption.textContent = 'Autre modèle...';
            modeleSelect.appendChild(autreOption);
        }

        function checkAutreModele() {
            const modele = document.getElementById('modele').value;
            const autreModeleContainer = document.getElementById('autre-modele-container');
            
            if (modele === 'autre') {
                autreModeleContainer.style.display = 'block';
                document.getElementById('autre-modele').required = true;
            } else {
                autreModeleContainer.style.display = 'none';
                document.getElementById('autre-modele').required = false;
            }
        }

function validateForm() {
    const qtInitial = parseInt(document.getElementById('qt_initial').value);
    const qtDisponible = parseInt(document.getElementById('qt_disponible').value);
    const marqueSelect = document.getElementById('marque');
    const autreMarque = document.getElementById('autre-marque').value;
    const modeleSelect = document.getElementById('modele');
    const autreModele = document.getElementById('autre-modele').value;

    if (qtDisponible > qtInitial) {
        alert("La quantité disponible ne peut pas être supérieure à la quantité initiale.");
        return false;
    }

    if (marqueSelect.value === 'autre' && !autreMarque.trim()) {
        alert("Veuillez préciser la marque.");
        return false;
    }

    if (modeleSelect.value === 'autre' && !autreModele.trim()) {
        alert("Veuillez préciser le modèle.");
        return false;
    }

    return true;
}
    </script>
</body>
</html>