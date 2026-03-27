document.addEventListener('DOMContentLoaded', function () {
    const companiesGrid = document.getElementById('companiesGrid');
    const searchInput = document.getElementById('searchInput');
    const locationFilter = document.getElementById('locationFilter');
    const industryFilter = document.getElementById('industryFilter');
    const sortBy = document.getElementById('sortBy');
    const sizeCheckboxes = Array.from(document.querySelectorAll('.filter-widget .filter-title'))
        .find(w => w.innerText === 'Company Size')?.parentElement.querySelectorAll('input[type="checkbox"]') || [];
    const industryCheckboxes = Array.from(document.querySelectorAll('.filter-widget .filter-title'))
        .find(w => w.innerText === 'Industry')?.parentElement.querySelectorAll('input[type="checkbox"]') || [];
    const foundedCheckboxes = Array.from(document.querySelectorAll('.filter-widget .filter-title'))
        .find(w => w.innerText === 'Founded')?.parentElement.querySelectorAll('input[type="checkbox"]') || [];

    let allCompanies = [];
    let filteredCompanies = [];

    async function loadCompanies() {
        companiesGrid.innerHTML = '<div class="text-center w-100 py-4" id="companiesLoading">Loading companies...</div>';
        try {
            const res = await fetch('api/homepage_companies.php');
            const data = await res.json();
            if (!data.success || !Array.isArray(data.companies)) {
                companiesGrid.innerHTML = '<div class="alert alert-danger">Failed to load companies.</div>';
                allCompanies = [];
                filteredCompanies = [];
                return;
            }
            allCompanies = data.companies;
            renderCompanies(allCompanies);
            filterCompanies();
        } catch (err) {
            companiesGrid.innerHTML = '<div class="alert alert-danger">Failed to load companies.</div>';
            allCompanies = [];
            filteredCompanies = [];
        }
    }

    function renderCompanies(companies) {
        companiesGrid.innerHTML = '';
        if (!companies.length) {
            companiesGrid.innerHTML = '<div class="alert alert-info">No companies found.</div>';
            return;
        }
        companies.forEach(company => {
            const card = document.createElement('div');
            card.className = 'company-card-v2';
            card.setAttribute('data-name', (company.name || '').toLowerCase());
            card.setAttribute('data-location', company.location || '');
            card.setAttribute('data-industry', company.industry || '');
            card.setAttribute('data-size', company.company_size || '');
            card.setAttribute('data-founded', company.founded || '');
            card.innerHTML = `
                <div class="company-card-header">
                    <button class="bookmark-btn"><i class="bi bi-bookmark"></i></button>
                </div>
                <div class="company-card-body">
                    <div class="company-logo-wrapper">
                        <img src="${company.logo}" alt="${company.name}">
                    </div>
                    <h4 class="company-name">${company.name}</h4>
                    <span class="company-industry"><i class="bi bi-buildings"></i> ${company.industry || ''}</span>
                    <div class="company-meta">
                        <span><i class="bi bi-geo-alt"></i> ${company.location || ''}</span>
                        <span><i class="bi bi-people"></i> ${company.company_size || ''} employees</span>
                    </div>
                </div>
                <div class="company-card-footer">
                    <span class="open-jobs">${company.jobs_count} Open Positions</span>
                    <a href="company-detail.php?id=${company.id}" class="view-btn-link">View Company <i class="bi bi-arrow-right"></i></a>
                </div>
            `;
            companiesGrid.appendChild(card);
        });
    }

    function getCheckedValues(checkboxes) {
        return Array.from(checkboxes).filter(cb => cb.checked).map(cb => cb.value);
    }

    function filterCompanies() {
        let search = (searchInput.value || '').toLowerCase();
        let location = locationFilter.value;
        let industry = industryFilter.value;
        let sizeFilters = getCheckedValues(sizeCheckboxes);
        let industryCheckboxFilters = getCheckedValues(industryCheckboxes);
        let foundedFilters = getCheckedValues(foundedCheckboxes);

        filteredCompanies = allCompanies.filter(company => {
            let match = true;
            if (search && !(company.name || '').toLowerCase().includes(search)) match = false;
            if (location && company.location !== location) match = false;
            if (industry && company.industry !== industry) match = false;
            if (industryCheckboxFilters.length && !industryCheckboxFilters.includes(company.industry)) match = false;
            if (sizeFilters.length && !sizeFilters.includes(company.company_size)) match = false;
            if (foundedFilters.length && !foundedFilters.includes(company.founded)) match = false;
            return match;
        });

        // Sort
        let sorted = [...filteredCompanies];
        if (sortBy.value === 'name-asc') sorted.sort((a, b) => a.name.localeCompare(b.name));
        else if (sortBy.value === 'name-desc') sorted.sort((a, b) => b.name.localeCompare(a.name));
        else if (sortBy.value === 'jobs') sorted.sort((a, b) => b.jobs_count - a.jobs_count);
        else sorted.sort((a, b) => b.id - a.id); // Newest first

        renderCompanies(sorted);
    }

    searchInput.addEventListener('keyup', filterCompanies);
    locationFilter.addEventListener('change', filterCompanies);
    industryFilter.addEventListener('change', filterCompanies);
    sortBy.addEventListener('change', filterCompanies);
    sizeCheckboxes.forEach(cb => cb.addEventListener('change', filterCompanies));
    industryCheckboxes.forEach(cb => cb.addEventListener('change', filterCompanies));
    foundedCheckboxes.forEach(cb => cb.addEventListener('change', filterCompanies));

    document.getElementById('searchBtn').addEventListener('click', filterCompanies);

    loadCompanies();
});