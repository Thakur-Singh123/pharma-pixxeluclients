document.querySelectorAll(".toggle-password").forEach(function(toggle) {
    toggle.addEventListener("click", function() {
        const input = document.querySelector(this.getAttribute("toggle"));
        const icon = this.querySelector("i");
        if (input.type === "password") {
        input.type = "text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
        } else {
        input.type = "password";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
        }
    });
});
