function openSection(section) {
    document.querySelectorAll(".section").forEach((sec) => {
        sec.style.display = "none";
    });

    document.getElementById(section).style.display = "block";
}

function logout() {
    alert("Logged out successfully");
    window.location.href = "./index.php";
}
