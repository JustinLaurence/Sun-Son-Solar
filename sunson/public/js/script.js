document.addEventListener("DOMContentLoaded", function() {
    const loginForm = document.querySelector("#authForm");
    
    if(loginForm) {
        const usernameInput = document.querySelector('input[name="username"]');
        const passwordInput = document.querySelector('input[name="password"]');

        loginForm.addEventListener("submit", function(event) {
            if (usernameInput.value.trim() === "" || passwordInput.value.trim() === "") {
                event.preventDefault(); 
                alert("Please fill in both your username and password.");
            }
        });
    }
});