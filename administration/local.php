<?php
require "../inc/app.php";
require "../inc/conn_db.php";

// Fetch locations
$locations_sql = "SELECT id, name, nomber_place FROM locations ";
$locations_result = $conn->query($locations_sql);

$locations = [];
if ($locations_result->num_rows > 0) {
    while ($row = $locations_result->fetch_assoc()) {
        $locations[] = $row;
    }
}

$conn->close();
?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        // Function to handle the fade-in and fade-out animations
        function animateAlert(alertId) {
            var alert = $('#' + alertId);
            alert.addClass('fade-in-right');
            setTimeout(function() {
                alert.addClass('fade-out-left');
                setTimeout(function() {
                    alert.alert('close');
                }, 1000); // Time for fade-out animation
            }, 5000); // Display time before starting fade-out
        }

        // Apply the animations to the alerts
        if ($('#alert-success').length) {
            animateAlert('alert-success');
        } else if ($('#alert-error').length) {
            animateAlert('alert-error');
        }
    });
</script>

<style>
    @keyframes fadeInRight {
        from {
            opacity: 0;
            transform: translateX(50px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes fadeOutLeft {
        from {
            opacity: 1;
            transform: translateX(0);
        }
        to {
            opacity: 0;
            transform: translateX(-50px);
        }
    }

    .fade-in-right {
        animation: fadeInRight 1s ease-in-out;
    }

    .fade-out-left {
        animation: fadeOutLeft 1s ease-in-out;
    }
</style>

<div class="page-inner">
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Locations</h3>
        </div>
        <button class="btn btn-dark btn-round ms-auto" data-bs-toggle="modal" data-bs-target="#addLocationModal">
            <i class="fa fa-plus"></i> Ajouter Location
        </button>
    </div>
    <?php if (isset($_GET['msg']) && $_GET['msg'] == 'success') : ?>
        <div id="alert-success" class="alert alert-success alert-dismissible fade show" role="alert">
            Opération réussie!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
    <?php elseif (isset($_GET['msg']) && $_GET['msg'] == 'error') : ?>
        <div id="alert-error" class="alert alert-danger alert-dismissible fade show" role="alert">
            Une erreur s'est produite. Veuillez réessayer.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
    <?php endif; ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="locationTable" class="display table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Nombre de places</th> <!-- New column for number of places -->
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($locations) > 0) : ?>
                                    <?php foreach ($locations as $location) : ?>
                                        <tr>
                                            <td style="width: 40%;"><?php echo htmlspecialchars($location['name']); ?></td>
                                            <td style="width: 40%;"><?php echo htmlspecialchars($location['nomber_place']); ?></td> <!-- Display number of places -->
                                            <td>
                                                <button class="btn btn-warning btn-edit" data-id="<?php echo $location['id']; ?>" data-name="<?php echo htmlspecialchars($location['name']); ?>" data-nomber-place="<?php echo htmlspecialchars($location['nomber_place']); ?>" data-bs-toggle="modal" data-bs-target="#editLocationModal">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-danger btn-delete" data-id="<?php echo $location['id']; ?>" data-bs-toggle="modal" data-bs-target="#deleteLocationModal">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="3">Aucune location disponible</td> <!-- Updated colspan -->
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Location Modal -->
    <div class="modal fade" id="addLocationModal" tabindex="-1" aria-labelledby="addLocationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addLocationModalLabel">Ajouter Location</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <form action="actionsLocations/add_location.php" method="post">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="locationName">Nom de la location</label>
                            <input type="text" class="form-control" id="locationName" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="numberPlaces">Nombre de places</label>
                            <input type="number" class="form-control" id="numberPlaces" name="nomber_place" required> <!-- New field for number of places -->
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-primary">Ajouter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Location Modal -->
    <div class="modal fade" id="editLocationModal" tabindex="-1" aria-labelledby="editLocationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editLocationModalLabel">Modifier Location</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <form action="actionsLocations/edit_location.php" method="post">
                    <div class="modal-body">
                        <input type="hidden" id="editLocationId" name="id">
                        <div class="form-group">
                            <label for="editLocationName">Nom de la location</label>
                            <input type="text" class="form-control" id="editLocationName" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="editNumberPlaces">Nombre de places</label>
                            <input type="number" class="form-control" id="editNumberPlaces" name="nomber_place" required> <!-- New field for number of places -->
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Location Modal -->
    <div class="modal fade" id="deleteLocationModal" tabindex="-1" aria-labelledby="deleteLocationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteLocationModalLabel">Supprimer Location</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <form action="actionsLocations/delete_location.php" method="post">
                    <div class="modal-body">
                        <input type="hidden" id="deleteLocationId" name="id">
                        <p>Êtes-vous sûr de vouloir supprimer cette location ?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-danger">Supprimer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).on('click', '.btn-edit', function() {
        var id = $(this).data('id');
        var name = $(this).data('name');
        var nomberPlace = $(this).data('nomber-place');

        $('#editLocationId').val(id);
        $('#editLocationName').val(name);
        $('#editNumberPlaces').val(nomberPlace);
    });

    $(document).on('click', '.btn-delete', function() {
        var id = $(this).data('id');
        $('#deleteLocationId').val(id);
    });
</script>
<?php require("../inc/footer.php");?>