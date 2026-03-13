function openSection(sectionId){

let sections = document.querySelectorAll('.section');

sections.forEach(function(section){
section.style.display="none";
});

document.getElementById(sectionId).style.display="block";

}

function logout(){

alert("You have been logged out");

}



    /* REGISTER USER */
    function registerUser() {

        let email = document.querySelector("#registerForm input[type='email']").value.trim();
        let password = document.querySelector("#registerForm input[type='password']").value.trim();

        if (email === "" || password === "") {
            alert("Please fill all fields");
            return;
        }

        // Email validation
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            alert("Enter valid email");
            return;
        }

        // Password validation
        const passwordRegex = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d@$!%*?&]{6,}$/;
        if (!passwordRegex.test(password)) {
            alert("Password must be at least 6 characters with number");
            return;
        }

        let user = {
            email: email,
            password: password
        };

        localStorage.setItem("careerhuntUser", JSON.stringify(user));

        alert("Registration Successful! Please login.");

        showLogin();
    }


    /* LOGIN USER */
    function loginUser() {

        let email = document.querySelector("#loginForm input[type='text']").value.trim();
        let password = document.querySelector("#loginForm input[type='password']").value.trim();

        let savedUser = JSON.parse(localStorage.getItem("careerhuntUser"));

        if (!savedUser) {
            alert("No account found. Please register.");
            return;
        }

        if (email === savedUser.email && password === savedUser.password) {

            alert("Login Successful");

            window.location.href = "user/candidate-dashboard.html";

        } else {

            alert("Invalid Email or Password");

        }
    }


    /* SWITCH REGISTER */
    function showRegister() {
        document.getElementById("loginForm").style.display = "none";
        document.getElementById("registerForm").style.display = "block";
        document.getElementById("authTitle").innerText = "Create a CareerHunt Account";
    }

    /* SWITCH LOGIN */
    function showLogin() {
        document.getElementById("registerForm").style.display = "none";
        document.getElementById("loginForm").style.display = "block";
        document.getElementById("authTitle").innerText = "Login to CareerHunt";
    }


    /* USER TYPE BUTTON */
    function selectType(btn) {
        document.querySelectorAll(".type-btn").forEach(b => {
            b.classList.remove("active");
        });
        btn.classList.add("active");
    }
