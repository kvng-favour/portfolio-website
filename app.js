(function () {
  document.querySelectorAll(".control").forEach((button) => {
    button.addEventListener("click", function () {
      document.querySelector(".active-btn").classList.remove("active-btn");
      this.classList.add("active-btn");
      document.querySelector(".active").classList.remove("active");
      document.getElementById(button.dataset.id).classList.add("active");
    });
  });
  document.querySelector(".theme-btn").addEventListener("click", () => {
    document.body.classList.toggle("light-mode");
  });

  document.querySelectorAll(".about-item").forEach((text) => {
    text.addEventListener("mouseover", () => {
      text.classList.add("hovered");
    });

    text.addEventListener("mouseout", () => {
      text.classList.remove("hovered");
    });
  });
})();

document.querySelector(".contact-form").addEventListener("submit", (e) => { 
  const name = document.getElementById("name").value.trim();
  const email = document.getElementById("email").value.trim();
  const subject = document.getElementById("subject").value.trim();
  const message = document.getElementById("message").value.trim();
  const hiddenInput = document.getElementById("hidden-input");

  // Check if all fields are filled out
  if(!name || !email || !subject || !message) {
    e.preventDefault();
    alert("Please fill out all fields");
    return;
  }

  hiddenInput.value = subject;

});



