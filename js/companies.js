// Filter

document.getElementById("searchBtn").addEventListener("click", filterCompanies);
document.getElementById("searchInput").addEventListener("keyup", filterCompanies);

function filterCompanies() {
    let search = document.getElementById("searchInput").value.toLowerCase();
    let location = document.getElementById("locationFilter").value;
    let industry = document.getElementById("industryFilter").value;

    let sizeFilters = getCheckedValues("Company Size");
    let industryCheckboxFilters = getCheckedValues("Industry");
    let foundedFilters = getCheckedValues("Founded");

    let cards = document.querySelectorAll(".company-card-v2");

    cards.forEach(card => {
        let name = card.dataset.name;
        let cardLocation = card.dataset.location;
        let cardIndustry = card.dataset.industry;
        let cardSize = card.dataset.size;
        let cardFounded = card.dataset.founded;

        let match = true;

        // Search filter
        if (search && !name.includes(search)) {
            match = false;
        }

        // Location filter
        if (location && cardLocation !== location) {
            match = false;
        }

        // Dropdown industry filter
        if (industry && cardIndustry !== industry) {
            match = false;
        }

        // Sidebar industry filter
        if (industryCheckboxFilters.length > 0 && !industryCheckboxFilters.includes(cardIndustry)) {
            match = false;
        }

        // Size filter
        if (sizeFilters.length > 0 && !sizeFilters.includes(cardSize)) {
            match = false;
        }

        // Founded filter
        if (foundedFilters.length > 0 && !foundedFilters.includes(cardFounded)) {
            match = false;
        }

        card.style.display = match ? "block" : "none";
    });
}

// Get checked checkbox values by section title
function getCheckedValues(sectionTitle) {
    let values = [];
    document.querySelectorAll(".filter-widget").forEach(widget => {
        if (widget.querySelector(".filter-title").innerText === sectionTitle) {
            widget.querySelectorAll("input:checked").forEach(input => {
                values.push(input.value);
            });
        }
    });
    return values;
}

// Auto filter when checkbox changes
document.querySelectorAll(".filter-sidebar input").forEach(input => {
    input.addEventListener("change", filterCompanies);
});Filter