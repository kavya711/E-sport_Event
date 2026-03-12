document.addEventListener("DOMContentLoaded", function () {
  const navToggle = document.querySelector(".nav-toggle");
  const navMenu = document.querySelector("#nav-menu");
  if (navToggle && navMenu) {
    navToggle.addEventListener("click", () => {
      const isOpen = navMenu.classList.toggle("is-open");
      navToggle.setAttribute("aria-expanded", isOpen ? "true" : "false");
    });
  }

  const params = new URLSearchParams(window.location.search);
  const statusContainer = document.querySelector(".status-messages");

  if (statusContainer) {
    const status = params.get("status");
    const error = params.get("error");
    const registered = params.get("registered");
    const needLogin = params.get("need_login");

    if (window.location.pathname.endsWith("index.html")) {
      if (status === "success") {
        statusContainer.innerHTML =
          '<div class="form-message success" role="status"><strong>Registration received.</strong> We will email you with bracket and lobby details soon.</div>';
      } else if (status === "error") {
        statusContainer.innerHTML =
          '<div class="form-message error" role="alert"><strong>Something went wrong.</strong> Please check the form and try again.</div>';
      }
    }

    if (window.location.pathname.endsWith("signup.html")) {
      if (error) {
        statusContainer.innerHTML =
          '<div class="form-message error" role="alert"><strong>Check details.</strong> Please fix highlighted fields and try again.</div>';
      }
    }

    if (window.location.pathname.endsWith("login.html")) {
      if (registered === "1") {
        statusContainer.innerHTML =
          '<div class="form-message success" role="status"><strong>Account created.</strong> You can now log in.</div>';
      } else if (needLogin === "1") {
        statusContainer.innerHTML =
          '<div class="form-message error" role="alert"><strong>Login required.</strong> Please log in to book a slot for this game.</div>';
      } else if (error) {
        statusContainer.innerHTML =
          '<div class="form-message error" role="alert"><strong>Invalid credentials.</strong> Please check your email and password.</div>';
      }
    }

    if (
      window.location.pathname.endsWith("bgmi.html") ||
      window.location.pathname.endsWith("freefire.html") ||
      window.location.pathname.endsWith("codm.html") ||
      window.location.pathname.endsWith("valorant.html")
    ) {
      if (status === "success") {
        statusContainer.innerHTML =
          '<div class="form-message success" role="status"><strong>Booking confirmed.</strong> Check your email for lobby details.</div>';
      } else if (status === "error") {
        statusContainer.innerHTML =
          '<div class="form-message error" role="alert"><strong>Booking failed.</strong> Please verify all fields and try again.</div>';
      }
    }
  }
});
