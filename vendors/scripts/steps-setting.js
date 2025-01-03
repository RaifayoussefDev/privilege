let valid = true;

$(".tab-wizard").steps({
  headerTag: "h5",
  bodyTag: "section",
  transitionEffect: "fade",
  titleTemplate: '<span class="step">#index#</span> #title#',
  labels: {
    finish: "Valider",
    next: "Suivant",
    previous: "Précédent",
  },
  enablePagination: true,
  onStepChanging: function (event, currentIndex, newIndex) {
    // Always allow navigation to previous steps
    if (newIndex < currentIndex) {
      return true;
    }

    // Validate Step 1: Information Personnel
    if (currentIndex === 0) {
      // Step 1
      let valid = true; // Initialize the valid variable

      // Validate the CIN field
      if (!$("#cin").val().trim()) {
        $("#cin").addClass("is-invalid");
        valid = false;
      } else {
        $("#cin").removeClass("is-invalid");
      }

      // Validate the Nom field
      if (!$("#nom").val().trim()) {
        $("#nom").addClass("is-invalid");
        valid = false;
      } else {
        $("#nom").removeClass("is-invalid");
      }

      // Validate the Prénom field
      if (!$("#prenom").val().trim()) {
        $("#prenom").addClass("is-invalid");
        valid = false;
      } else {
        $("#prenom").removeClass("is-invalid");
      }

      // Validate the Email field
      // if (!$("#email").val().trim()) {
      //   $("#email").addClass("is-invalid");
      //   valid = false;
      // } else {
      //   $("#email").removeClass("is-invalid");
      // }

      // Validate the Phone field
      if (!$("#phone").val().trim()) {
        $("#phone").addClass("is-invalid");
        valid = false;
      } else {
        $("#phone").removeClass("is-invalid");
      }

      // If validation is successful, send the data via AJAX
      if (valid) {
        $.ajax({
          url: "save_step1.php", // Script PHP pour enregistrer les données
          type: "POST",
          data: {
            cin: $("#cin").val(),
            nom: $("#nom").val(),
            prenom: $("#prenom").val(),
            email: $("#email").val(),
            phone: $("#phone").val(),
            date_naissance: $("#date_naissance").val(), // Add date of birth field
            genre: $("#genre").val(), // Add gender field
            password: $("#password").val(), // Add password field if needed
            photo: $("#photo").val(), // Add photo field if needed
            commercial: $("#commercial").val(),
            note: $("#note").val(),
          },
          success: function (response) {
            // Gérer la réponse (optionnel)
            console.log("Données enregistrées : ", response);
          },
          error: function (error) {
            console.log(
              "Erreur lors de l'enregistrement des données : ",
              error
            );
          },
        });
      }
    }

    // Validate Step 2: Abonnement
    if (currentIndex === 1) {
      // Step 2
      if (!$("#type_abonnement").val().trim()) {
        $("#type_abonnement").addClass("is-invalid");
        valid = false;
      } else {
        $("#type_abonnement").removeClass("is-invalid");
        valid = true;
      }
    }

    if (currentIndex === 2) {
      // Change to 2 if this is the third step
      // Get values of total and reste
      const total = parseFloat(document.getElementById("total").value) || 0;
      const reste = parseFloat(document.getElementById("reste").value) || 0;
      valid = true;
      // console.log(total);
      // console.log(reste);

      // // Check if reste is at least 25% of total
      // // Check if reste is at most 25% of total
      // if (reste == 0) {
      //   valid = true; // Validation passes
      // } else {
      //   valid = false; // Validation fails
      //   alert("Le montant payé est incomplet ."); // Alert message for user
      // }
    }

    return valid;
  },
  onStepChanged: function (event, currentIndex, priorIndex) {
    // Always add the 'disabled' class to previous steps
    $(".steps .current").prevAll().addClass("disabled");
  },
onFinished: function (event, currentIndex) {
  // Désactive le bouton "Valider" pour éviter la double soumission
  const $finishButton = $(".actions a[href='#finish']"); // Sélection du bouton "Valider"
  $finishButton.prop("disabled", true); // Désactiver le bouton
  
  // Soumettez le formulaire
  $("#example-form").submit();
},
});

function validateField(input) {
  const field = input.name; // Get the name of the field
  const value = input.value; // Get the value of the field
  const errorMessage = document.getElementById(`${field}-error`);

  return fetch(`validate_field.php?field=${field}&value=${value}`)
    .then((response) => response.json())
    .then((data) => {
      if (data.exists) {
        // Show the error message and change the text to "already exists"
        errorMessage.style.display = "block";
        errorMessage.textContent = `${field.toUpperCase()} existe déjà !`;
        valid = false;
        return false;
      }
      // Hide the error message if the value is valid
      errorMessage.style.display = "none";
      valid = true;
      return true;
    })
    .catch((error) => console.error("Error:", error));
}

$(".tab-wizardProce").steps({
  headerTag: "h5",
  bodyTag: "section",
  transitionEffect: "fade",
  titleTemplate: '<span class="step">#index#</span> #title#',
  labels: {
    finish: "Valider",
    next: "Suivant",
    previous: "Précédent",
  },
  enablePagination: true,
  onStepChanging: function (event, currentIndex, newIndex) {
    // Always allow navigation to previous steps
    if (newIndex < currentIndex) {
      return true;
    }

    // Validate Step 1: Information Personnel
    if (currentIndex === 0) {
      // Step 1
      let valid = true; // Initialize the valid variable

      // Validate the CIN field
      if (!$("#cin").val().trim()) {
        $("#cin").addClass("is-invalid");
        valid = false;
      } else {
        $("#cin").removeClass("is-invalid");
      }

      // Validate the Nom field
      if (!$("#nom").val().trim()) {
        $("#nom").addClass("is-invalid");
        valid = false;
      } else {
        $("#nom").removeClass("is-invalid");
      }

      // Validate the Prénom field
      if (!$("#prenom").val().trim()) {
        $("#prenom").addClass("is-invalid");
        valid = false;
      } else {
        $("#prenom").removeClass("is-invalid");
      }

      // Validate the Email field
      if (!$("#email").val().trim()) {
        $("#email").addClass("is-invalid");
        valid = false;
      } else {
        $("#email").removeClass("is-invalid");
      }

      // Validate the Phone field
      if (!$("#phone").val().trim()) {
        $("#phone").addClass("is-invalid");
        valid = false;
      } else {
        $("#phone").removeClass("is-invalid");
      }
    }

    // Validate Step 2: Abonnement
    if (currentIndex === 1) {
      // Step 2: Validate type_abonnement
      if (!$("#type_abonnement").val().trim()) {
        $("#type_abonnement").addClass("is-invalid");
        valid = false;
      } else {
        $("#type_abonnement").removeClass("is-invalid");
        valid = true;
      }

      // Check for total_activites and reste_activites
      const totalActivitesInput = $("#total_activites");
      const resteActivitesInput = $("#reste_activites");

      let totalActivites = 0;
      let resteActivites = 0;

      if (
        totalActivitesInput.length &&
        totalActivitesInput.val().trim() !== ""
      ) {
        totalActivites = parseFloat(totalActivitesInput.val()) || 0;
      }

      if (
        resteActivitesInput.length &&
        resteActivitesInput.val().trim() !== ""
      ) {
        resteActivites = parseFloat(resteActivitesInput.val()) || 0;
      }

      // Get current total and reste values
      const total = parseFloat($("#total").val()) || 0;
      const reste = parseFloat($("#reste").val()) || 0;

      // Update total and reste with activites values if they exist
      const updatedTotal = total + totalActivites;
      const updatedReste = reste + resteActivites;

      // Set updated values back to inputs
      $("#total").val(updatedTotal.toFixed(2));
      $("#reste").val(updatedReste.toFixed(2));
    }

    if (currentIndex === 2) {
      // Change to 2 if this is the third step
      // Get values of total and reste
      const total = parseFloat(document.getElementById("total").value) || 0;
      const reste = parseFloat(document.getElementById("reste").value) || 0;
      valid = true;
      // console.log(total);
      // console.log(reste);

      // // Check if reste is at least 25% of total
      // // Check if reste is at most 25% of total
      // if (reste == 0) {
      //   valid = true; // Validation passes
      // } else {
      //   valid = false; // Validation fails
      //   alert("Le montant payé est incomplet ."); // Alert message for user
      // }
    }

    return valid;
  },
  onStepChanged: function (event, currentIndex, priorIndex) {
    // Always add the 'disabled' class to previous steps
    $(".steps .current").prevAll().addClass("disabled");
  },
onFinished: function (event, currentIndex) {
  // Désactive le bouton "Valider" pour éviter la double soumission
  const $finishButton = $(".actions a[href='#finish']"); // Sélection du bouton "Valider"
  $finishButton.prop("disabled", true); // Désactiver le bouton
  
  // Soumettez le formulaire
  $("#example-form").submit();
},
});
