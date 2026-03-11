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

