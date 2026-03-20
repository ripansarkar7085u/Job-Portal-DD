
document.addEventListener("DOMContentLoaded", function () {

const jobs = document.querySelectorAll(".job-item");
const pagination = document.getElementById("pagination");

const searchInput = document.getElementById("searchInput");
const locationFilter = document.getElementById("locationFilter");
const categoryFilter = document.getElementById("categoryFilter");

const typeFilters = document.querySelectorAll(".typeFilter");
const expFilters = document.querySelectorAll(".expFilter");
const salaryFilters = document.querySelectorAll(".salaryFilter");

let filteredJobs = [];
let currentPage = 1;
const perPage = 4;

// FILTER FUNCTION
function filterJobs() {

let keyword = searchInput.value.toLowerCase();
let location = locationFilter.value;
let category = categoryFilter.value;

let types = [], exps = [], salaries = [];

typeFilters.forEach(cb => cb.checked && types.push(cb.value));
expFilters.forEach(cb => cb.checked && exps.push(cb.value));
salaryFilters.forEach(cb => cb.checked && salaries.push(cb.value));

filteredJobs = [];

jobs.forEach(job => {

let title = job.dataset.title.toLowerCase();
let jobLocation = job.dataset.location;
let jobCategory = job.dataset.category;
let jobType = job.dataset.type;
let jobExp = job.dataset.exp;
let jobSalary = parseInt(job.dataset.salary);

let show = true;

if (keyword && !title.includes(keyword)) show = false;
if (location && jobLocation !== location) show = false;
if (category && jobCategory !== category) show = false;
if (types.length && !types.includes(jobType)) show = false;
if (exps.length && !exps.includes(jobExp)) show = false;

if (salaries.length) {
let salaryMatch = false;
salaries.forEach(range => {
if (range==="0-3" && jobSalary<=3) salaryMatch=true;
if (range==="3-6" && jobSalary>3 && jobSalary<=6) salaryMatch=true;
if (range==="6+" && jobSalary>6) salaryMatch=true;
});
if (!salaryMatch) show=false;
}

job.style.display = "none";

if (show) filteredJobs.push(job);

});

currentPage = 1;
showPage();
}

// PAGINATION
function showPage() {

jobs.forEach(j => j.style.display = "none");

let start = (currentPage-1)*perPage;
let end = start + perPage;

filteredJobs.slice(start,end).forEach(j => j.style.display="block");

renderPagination();
}

// RENDER PAGINATION
function renderPagination() {

pagination.innerHTML="";
let totalPages = Math.ceil(filteredJobs.length / perPage);

for(let i=1;i<=totalPages;i++){
let li = document.createElement("li");
li.className = "page-item " + (i===currentPage ? "active":"");

li.innerHTML = `<a class="page-link">${i}</a>`;
li.onclick = ()=>{ currentPage=i; showPage(); };

pagination.appendChild(li);
}

}

// MODAL (GET DETAILS)
document.addEventListener("click", function(e){

if(e.target.classList.contains("viewBtn")){

let job = e.target.closest(".job-item");

document.getElementById("modalTitle").innerText = job.dataset.title;
document.getElementById("modalCompany").innerText = "Company: " + job.querySelector("small").innerText;
document.getElementById("modalLocation").innerText = "Location: " + job.dataset.location;
document.getElementById("modalSalary").innerText = "Salary: ₹" + job.dataset.salary + " LPA";
document.getElementById("modalDesc").innerText = job.dataset.desc;

new bootstrap.Modal(document.getElementById("jobModal")).show();

}

});

// EVENTS
searchInput.addEventListener("keyup", filterJobs);
locationFilter.addEventListener("change", filterJobs);
categoryFilter.addEventListener("change", filterJobs);

typeFilters.forEach(cb => cb.addEventListener("change", filterJobs));
expFilters.forEach(cb => cb.addEventListener("change", filterJobs));
salaryFilters.forEach(cb => cb.addEventListener("change", filterJobs));

// INIT
filteredJobs = Array.from(jobs);
showPage();

});
