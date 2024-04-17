document.addEventListener("DOMContentLoaded", function () {
  const homeTeamSelect = document.getElementById("homeTeam");
  const awayTeamSelect = document.getElementById("awayTeam");
  const errorMessage = "Home and Away teams cannot be the same.";

  // Event listener for form submit
  document.querySelector("form").addEventListener("submit", function (event) {
    const selectedHomeTeam = homeTeamSelect.value;
    const selectedAwayTeam = awayTeamSelect.value;

    // Check if the selected teams are the same
    if (selectedHomeTeam === selectedAwayTeam) {
      // Show error message and add the is-invalid class
      document.querySelector(".home-error").textContent = errorMessage;
      document.querySelector(".away-error").textContent = errorMessage;
      homeTeamSelect.classList.add("is-invalid");
      awayTeamSelect.classList.add("is-invalid");
      homeTeamSelect.classList.remove("is-valid"); // Remove is-valid class
      awayTeamSelect.classList.remove("is-valid"); // Remove is-valid class
      homeTeamSelect.classList.remove("form-green-border");
      awayTeamSelect.classList.remove("form-green-border");
      document.querySelector("form").classList.add("was-validated"); // Add 'was-validated' class to form
      event.preventDefault();
    } else {
      document.querySelector(".home-error").textContent = ""; // Clear the error message
      document.querySelector(".away-error").textContent = ""; // Clear the error message
      homeTeamSelect.classList.remove("is-invalid");
      awayTeamSelect.classList.remove("is-invalid");
      homeTeamSelect.classList.add("is-valid"); // Add is-valid class
      awayTeamSelect.classList.add("is-valid"); // Add is-valid class
      document.querySelector("form").classList.add("was-validated"); // Add 'was-validated' class to form
      homeTeamSelect.classList.add("form-green-border");
      awayTeamSelect.classList.add("form-green-border");
    }
  });

  // Event listener for home team select
  homeTeamSelect.addEventListener("change", function () {
    const selectedHomeTeam = homeTeamSelect.value;
    const selectedAwayTeam = awayTeamSelect.value;

    // Enable all options in the away team select
    const awayTeamOptions = awayTeamSelect.options;
    for (let i = 0; i < awayTeamOptions.length; i++) {
      awayTeamOptions[i].disabled = false;
    }

    // Check if the selected teams are the same
    if (selectedHomeTeam === selectedAwayTeam) {
      // Show error message and add the is-invalid class
      document.querySelector(".home-error").textContent = errorMessage;
      document.querySelector(".away-error").textContent = errorMessage;
      homeTeamSelect.classList.add("is-invalid");
      awayTeamSelect.classList.add("is-invalid");
      homeTeamSelect.classList.remove("is-valid"); // Remove is-valid class
      awayTeamSelect.classList.remove("is-valid"); // Remove is-valid class
      homeTeamSelect.classList.remove("form-green-border");
      awayTeamSelect.classList.remove("form-green-border");
      document.querySelector("form").classList.add("was-validated"); // Add 'was-validated' class to form
    } else {
      document.querySelector(".home-error").textContent = ""; // Clear the error message
      document.querySelector(".away-error").textContent = ""; // Clear the error message
      homeTeamSelect.classList.remove("is-invalid");
      awayTeamSelect.classList.remove("is-invalid");
      homeTeamSelect.classList.add("is-valid"); // Add is-valid class
      awayTeamSelect.classList.add("is-valid"); // Add is-valid class
      document.querySelector("form").classList.add("was-validated"); // Add 'was-validated' class to form
      homeTeamSelect.classList.add("form-green-border");
      awayTeamSelect.classList.add("form-green-border");
    }
  });

  // Event listener for away team select
  awayTeamSelect.addEventListener("change", function () {
    const selectedHomeTeam = homeTeamSelect.value;
    const selectedAwayTeam = awayTeamSelect.value;

    // Enable all options in the home team select
    const homeTeamOptions = homeTeamSelect.options;
    for (let i = 0; i < homeTeamOptions.length; i++) {
      homeTeamOptions[i].disabled = false;
    }

    // Check if the selected teams are the same
    if (selectedHomeTeam === selectedAwayTeam) {
      // Show error message and add the is-invalid class
      document.querySelector(".home-error").textContent = errorMessage;
      document.querySelector(".away-error").textContent = errorMessage;
      homeTeamSelect.classList.add("is-invalid");
      awayTeamSelect.classList.add("is-invalid");
      homeTeamSelect.classList.remove("is-valid"); // Remove is-valid class
      awayTeamSelect.classList.remove("is-valid"); // Remove is-valid class
      awayTeamSelect.classList.remove("form-green-border");
      homeTeamSelect.classList.remove("form-green-border");
      document.querySelector("form").classList.add("was-validated"); // Add 'was-validated' class to form
    } else {
      document.querySelector(".home-error").textContent = ""; // Clear the error message
      document.querySelector(".away-error").textContent = ""; // Clear the error message
      homeTeamSelect.classList.remove("is-invalid");
      awayTeamSelect.classList.remove("is-invalid");
      homeTeamSelect.classList.add("is-valid"); // Add is-valid class
      awayTeamSelect.classList.add("is-valid"); // Add is-valid class
      document.querySelector("form").classList.add("was-validated"); // Add 'was-validated' class to form
      awayTeamSelect.classList.add("form-green-border");
      homeTeamSelect.classList.add("form-green-border");
    }
  });
});


