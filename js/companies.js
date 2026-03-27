document.addEventListener('DOMContentLoaded', function () {
    const companiesGrid = document.getElementById('companiesGrid');
    const searchInput = document.getElementById('searchInput');
    const locationFilter = document.getElementById('locationFilter');
    const industryFilter = document.getElementById('industryFilter');
    const sortBy = document.getElementById('sortBy');
    const sizeFilterOptions = document.getElementById('sizeFilterOptions');
    const industryFilterOptions = document.getElementById('industryFilterOptions');
    const foundedFilterOptions = document.getElementById('foundedFilterOptions');
    let sizeCheckboxes = [];
    let industryCheckboxes = [];
    let foundedCheckboxes = [];

    let allCompanies = [];
    let filteredCompanies = [];
    let currentPage = 1;
    const perPage = 6;

    function buildSelectOptions(select, values, defaultLabel) {
        const unique = Array.from(new Set(values.filter(Boolean)));
        select.innerHTML = `<option value="">${defaultLabel}</option>` + unique.map(v => `<option value="${v}">${v}</option>`).join('');
    }

    function buildCheckboxOptions(container, values, name) {
        const unique = Array.from(new Set(values.filter(Boolean)));
        container.innerHTML = unique.map(v => `
            <label class="filter-option">
                <input type="checkbox" value="${v}"> ${v}
            </label>
        `).join('');
        return Array.from(container.querySelectorAll('input[type="checkbox"]'));
    }

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

            // Build dynamic filter options
            buildSelectOptions(locationFilter, allCompanies.map(c => c.location), 'All Locations');
            buildSelectOptions(industryFilter, allCompanies.map(c => c.industry), 'All Industries');
            sizeCheckboxes = buildCheckboxOptions(sizeFilterOptions, allCompanies.map(c => c.company_size), 'size');
            industryCheckboxes = buildCheckboxOptions(industryFilterOptions, allCompanies.map(c => c.industry), 'industry');
            foundedCheckboxes = buildCheckboxOptions(foundedFilterOptions, allCompanies.map(c => c.founded), 'founded');

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

        filteredCompanies = sorted;
        currentPage = 1;
        showPage();
    }

    function showPage() {
        const start = (currentPage - 1) * perPage;
        const end = start + perPage;
        const paginatedCompanies = filteredCompanies.slice(start, end);
        
        renderCompanies(paginatedCompanies);
        
        const resultsCountSpan = document.querySelector('.results-count span');
        if (resultsCountSpan) {
            const startNum = filteredCompanies.length === 0 ? 0 : start + 1;
            const endNum = Math.min(start + perPage, filteredCompanies.length);
            resultsCountSpan.innerHTML = `Showing <strong>${startNum}-${endNum}</strong> of <strong>${filteredCompanies.length}</strong> companies`;
        }

        renderPagination();
    }

    function renderPagination() {
        const paginationList = document.querySelector('.pagination');
        if (!paginationList) return;
        
        paginationList.innerHTML = '';
        let totalPages = Math.ceil(filteredCompanies.length / perPage);
        if (totalPages < 1) totalPages = 1;

        const prevLi = document.createElement('li');
        prevLi.className = `page-item ${currentPage === 1 ? 'disabled' : ''}`;
        prevLi.innerHTML = `<a class="page-link" href="#"><i class="bi bi-chevron-left"></i></a>`;
        if (currentPage > 1) {
            prevLi.onclick = (e) => { e.preventDefault(); currentPage--; showPage(); };
        } else {
            prevLi.onclick = (e) => e.preventDefault();
        }
        paginationList.appendChild(prevLi);

        let startPage = Math.max(1, currentPage - 2);
        let endPage = Math.min(totalPages, startPage + 4);
        if (endPage - startPage < 4) {
            startPage = Math.max(1, endPage - 4);
        }

        for (let i = startPage; i <= endPage; i++) {
            const li = document.createElement('li');
            li.className = `page-item ${i === currentPage ? 'active' : ''}`;
            li.innerHTML = `<a class="page-link" href="#">${i}</a>`;
            li.onclick = (e) => { e.preventDefault(); currentPage = i; showPage(); };
            paginationList.appendChild(li);
        }

        const nextLi = document.createElement('li');
        nextLi.className = `page-item ${currentPage === totalPages ? 'disabled' : ''}`;
        nextLi.innerHTML = `<a class="page-link" href="#"><i class="bi bi-chevron-right"></i></a>`;
        if (currentPage < totalPages) {
            nextLi.onclick = (e) => { e.preventDefault(); currentPage++; showPage(); };
        } else {
            nextLi.onclick = (e) => e.preventDefault();
        }
        paginationList.appendChild(nextLi);
    }

    searchInput.addEventListener('keyup', filterCompanies);
    locationFilter.addEventListener('change', filterCompanies);
    industryFilter.addEventListener('change', filterCompanies);
    sortBy.addEventListener('change', filterCompanies);

    // Add event listeners after dynamic build
    function addDynamicCheckboxListeners() {
        sizeCheckboxes.forEach(cb => cb.addEventListener('change', filterCompanies));
        industryCheckboxes.forEach(cb => cb.addEventListener('change', filterCompanies));
        foundedCheckboxes.forEach(cb => cb.addEventListener('change', filterCompanies));
    }

    document.getElementById('searchBtn').addEventListener('click', filterCompanies);

    // Re-add listeners after each load
    const origLoadCompanies = loadCompanies;
    loadCompanies = async function() {
        await origLoadCompanies();
        addDynamicCheckboxListeners();
    };

    loadCompanies();
});