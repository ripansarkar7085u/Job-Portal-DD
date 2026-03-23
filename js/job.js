
document.addEventListener("DOMContentLoaded", function () {
	const jobContainer = document.getElementById("jobContainer");
	const pagination = document.getElementById("pagination");
	const jobCount = document.getElementById("jobCount");

	const searchInput = document.getElementById("searchInput");
	const locationFilter = document.getElementById("locationFilter");
	const categoryFilter = document.getElementById("categoryFilter");
	const noJobsMessage = document.getElementById("noJobsMessage");

	const typeFilters = document.querySelectorAll(".typeFilter");
	const expFilters = document.querySelectorAll(".expFilter");
	const salaryFilters = document.querySelectorAll(".salaryFilter");

	let allJobs = [];
	let userSkills = [];
	let filteredJobs = [];
	let requestedCategory = "";
	let currentPage = 1;
	const perPage = 6;

	async function resolveRequestedCategory() {
		const params = new URLSearchParams(window.location.search);
		const explicitCategory = (params.get("category") || "").trim();
		if (explicitCategory) {
			return explicitCategory;
		}

		const categoryId = (params.get("category_id") || "").trim();
		if (!categoryId) {
			return "";
		}

		try {
			const response = await fetch("api/job_categories.php?limit=100", {
				method: "GET",
				credentials: "include",
			});

			if (!response.ok) {
				return "";
			}

			const payload = await response.json();
			const categories = Array.isArray(payload.categories) ? payload.categories : [];
			const match = categories.find((item) => String(item.id) === categoryId);
			return match ? String(match.category || "") : "";
		} catch {
			return "";
		}
	}

	function parseDate(value) {
		if (!value) return 0;
		const ts = Date.parse(value);
		return Number.isFinite(ts) ? ts : 0;
	}

	function normalizeSkills(skills) {
		if (!Array.isArray(skills)) {
			return [];
		}
		const map = new Map();
		skills.forEach((skill) => {
			const normalized = String(skill || "").trim().toLowerCase();
			if (normalized.length >= 2) {
				map.set(normalized, true);
			}
		});
		return Array.from(map.keys());
	}

	function scoreJobBySkills(job, skills) {
		if (!skills.length) {
			return 0;
		}

		const haystack = [
			job.title,
			job.category,
			job.description,
			job.requirements,
		]
			.map((value) => String(value || "").toLowerCase())
			.join(" ");

		let score = 0;
		skills.forEach((skill) => {
			if (haystack.includes(skill)) {
				score += 1;
			}
		});

		return score;
	}

	function sortJobsBySkills(jobs, skills) {
		const normalizedSkills = normalizeSkills(skills);
		if (!normalizedSkills.length) {
			return [...jobs].sort((a, b) => parseDate(b.created_at) - parseDate(a.created_at));
		}

		return [...jobs].sort((a, b) => {
			const scoreA = scoreJobBySkills(a, normalizedSkills);
			const scoreB = scoreJobBySkills(b, normalizedSkills);
			if (scoreA !== scoreB) {
				return scoreB - scoreA;
			}
			return parseDate(b.created_at) - parseDate(a.created_at);
		});
	}

	function escapeHtml(value) {
		return String(value || "")
			.replace(/&/g, "&amp;")
			.replace(/</g, "&lt;")
			.replace(/>/g, "&gt;")
			.replace(/\"/g, "&quot;")
			.replace(/'/g, "&#039;");
	}

	function employmentTypeLabel(value) {
		const map = {
			"full-time": "Full Time",
			"part-time": "Part Time",
			contract: "Contract",
			freelance: "Freelance",
			internship: "Internship",
		};

		return map[value] || "Not specified";
	}

	function salaryLpaValue(job) {
		if (job.salary_min === null && job.salary_max === null) {
			return null;
		}

		const max = job.salary_max !== null ? Number(job.salary_max) : Number(job.salary_min);
		if (!Number.isFinite(max)) {
			return null;
		}

		if (String(job.currency || "USD").toUpperCase() === "INR") {
			return max / 100000;
		}

		return max / 10000;
	}

	function salaryLabel(job) {
		if (!job.salary_visible || (job.salary_min === null && job.salary_max === null)) {
			return "Salary not disclosed";
		}

		const symbolMap = {
			USD: "$",
			EUR: "EUR ",
			GBP: "GBP ",
			CAD: "CAD ",
			AUD: "AUD ",
			INR: "INR ",
		};

		const periodMap = {
			year: "Year",
			month: "Month",
			hour: "Hour",
		};

		const symbol = symbolMap[String(job.currency || "").toUpperCase()] || "";
		const period = periodMap[job.salary_period] || "Year";

		if (job.salary_min !== null && job.salary_max !== null) {
			return `${symbol}${job.salary_min} - ${symbol}${job.salary_max} / ${period}`;
		}

		const value = job.salary_max !== null ? job.salary_max : job.salary_min;
		return `${symbol}${value} / ${period}`;
	}

	function cardHtml(job) {
		const lpa = salaryLpaValue(job);
		const title = escapeHtml(job.title);
		const company = escapeHtml(job.company_name || "Company");
		const location = escapeHtml(job.location || "Not specified");
		const category = escapeHtml(job.category || "other");
		const salaryText = escapeHtml(salaryLabel(job));
		const description = escapeHtml(job.description || "No description available.");
		const typeText = escapeHtml(employmentTypeLabel(job.employment_type));
		const salaryData = lpa !== null ? String(lpa) : "";

		return `
			<div class="col-md-6 job-item"
				data-title="${title}"
				data-location="${location}"
				data-category="${category}"
				data-type="${escapeHtml(job.employment_type || "")}" 
				data-exp="${escapeHtml(job.experience_level || "")}" 
				data-salary="${escapeHtml(salaryData)}"
				data-desc="${description}">

				<div class="job-card p-3 shadow-sm rounded h-100">
					<h5>${title}</h5>
					<small>${company}</small>
					<p>📍 ${location}</p>
					<p>💰 ${salaryText}</p>

					<div class="d-flex justify-content-between gap-2 flex-wrap align-items-center">
						<span class="badge bg-success">${typeText}</span>
						<button type="button" class="btn btn-sm btn-outline-primary viewBtn">Quick View</button>
						<a href="job-details.php?id=${encodeURIComponent(job.id)}">Get Details</a>
					</div>
				</div>
			</div>
		`;
	}

	function updateJobCount(visibleCount, totalCount) {
		if (!jobCount) {
			return;
		}

		jobCount.textContent = `Showing ${visibleCount} of ${totalCount} jobs`;
	}

	function buildSelectOptions(selectElement, values, allLabel) {
		if (!selectElement) {
			return;
		}

		const uniqueValues = Array.from(new Set(values.filter(Boolean))).sort((a, b) => a.localeCompare(b));
		selectElement.innerHTML = `<option value="">${allLabel}</option>`;

		uniqueValues.forEach((value) => {
			const option = document.createElement("option");
			option.value = value;
			option.textContent = value;
			selectElement.appendChild(option);
		});
	}

	function getRenderedJobs() {
		return Array.from(document.querySelectorAll(".job-item"));
	}

	function filterJobs() {
		const jobs = getRenderedJobs();

		const keyword = searchInput.value.trim().toLowerCase();
		const location = locationFilter.value;
		const category = categoryFilter.value;

		const types = [];
		const exps = [];
		const salaries = [];

		typeFilters.forEach((cb) => cb.checked && types.push(cb.value));
		expFilters.forEach((cb) => cb.checked && exps.push(cb.value));
		salaryFilters.forEach((cb) => cb.checked && salaries.push(cb.value));

		filteredJobs = [];

		jobs.forEach((job) => {
			const title = (job.dataset.title || "").toLowerCase();
			const jobLocation = job.dataset.location || "";
			const jobCategory = job.dataset.category || "";
			const jobType = job.dataset.type || "";
			const jobExp = job.dataset.exp || "";
			const salaryData = job.dataset.salary || "";
			const jobSalary = salaryData === "" ? null : parseFloat(salaryData);

			let show = true;

			if (keyword && !title.includes(keyword)) {
				show = false;
			}

			if (location && jobLocation !== location) {
				show = false;
			}

			if (category && jobCategory !== category) {
				show = false;
			}

			if (types.length && !types.includes(jobType)) {
				show = false;
			}

			if (exps.length && jobExp && !exps.includes(jobExp)) {
				show = false;
			}

			if (salaries.length) {
				let salaryMatch = false;

				salaries.forEach((range) => {
					if (jobSalary === null) {
						return;
					}

					if (range === "0-3" && jobSalary <= 3) {
						salaryMatch = true;
					}
					if (range === "3-6" && jobSalary > 3 && jobSalary <= 6) {
						salaryMatch = true;
					}
					if (range === "6+" && jobSalary > 6) {
						salaryMatch = true;
					}
				});

				if (!salaryMatch) {
					show = false;
				}
			}

			job.style.display = "none";

			if (show) {
				filteredJobs.push(job);
			}
		});

		currentPage = 1;
		showPage();
	}

	function showPage() {
		const jobs = getRenderedJobs();
		jobs.forEach((job) => {
			job.style.display = "none";
		});

		const start = (currentPage - 1) * perPage;
		const end = start + perPage;

		filteredJobs.slice(start, end).forEach((job) => {
			job.style.display = "block";
		});

		if (noJobsMessage) {
			noJobsMessage.style.display = filteredJobs.length === 0 ? "block" : "none";
		}

		updateJobCount(filteredJobs.length, jobs.length);
		renderPagination();
	}

	function renderPagination() {
		pagination.innerHTML = "";
		const totalPages = Math.ceil(filteredJobs.length / perPage);

		if (totalPages <= 1) {
			return;
		}

		for (let i = 1; i <= totalPages; i++) {
			const li = document.createElement("li");
			li.className = "page-item " + (i === currentPage ? "active" : "");

			li.innerHTML = `<a class="page-link">${i}</a>`;
			li.onclick = () => {
				currentPage = i;
				showPage();
			};

			pagination.appendChild(li);
		}
	}

	function renderJobs(jobs) {
		if (!Array.isArray(jobs) || jobs.length === 0) {
			jobContainer.innerHTML = '<div class="col-12"><div class="alert alert-info mb-0">No jobs found.</div></div>';
			filteredJobs = [];
			if (noJobsMessage) {
				noJobsMessage.style.display = "none";
			}
			updateJobCount(0, 0);
			pagination.innerHTML = "";
			return;
		}

		const html = jobs.map((job) => cardHtml(job)).join("");
		jobContainer.innerHTML = html;
		filteredJobs = getRenderedJobs();
		showPage();
	}

	async function loadJobs() {
		   try {
			   requestedCategory = await resolveRequestedCategory();

			   const [jobsResponse, skillsResponse] = await Promise.all([
				   fetch("api/featured_jobs.php?limit=200", {
					   method: "GET",
					   credentials: "include",
				   }),
				   fetch("api/user_profile_skills.php", {
					   method: "GET",
					   credentials: "include",
				   }),
			   ]);

			   if (!jobsResponse.ok) {
				   console.error("Jobs API error:", jobsResponse.status, jobsResponse.statusText);
				   throw new Error("Unable to fetch jobs.");
			   }

			   let payload;
			   try {
				   payload = await jobsResponse.json();
			   } catch (jsonErr) {
				   console.error("Jobs API JSON error:", jsonErr);
				   throw new Error("Invalid jobs API response.");
			   }
			   allJobs = Array.isArray(payload.jobs) ? payload.jobs : [];

			   if (skillsResponse.ok) {
				   try {
					   const skillsPayload = await skillsResponse.json();
					   userSkills = Array.isArray(skillsPayload.skills) ? skillsPayload.skills : [];
				   } catch (jsonErr) {
					   console.error("Skills API JSON error:", jsonErr);
					   userSkills = [];
				   }
			   } else {
				   console.error("Skills API error:", skillsResponse.status, skillsResponse.statusText);
			   }

			   allJobs = sortJobsBySkills(allJobs, userSkills);

			   buildSelectOptions(locationFilter, allJobs.map((job) => job.location || ""), "All Locations");
			   buildSelectOptions(categoryFilter, allJobs.map((job) => job.category || ""), "All Categories");

			   renderJobs(allJobs);

			   if (requestedCategory) {
				   const options = Array.from(categoryFilter.options);
				   const matchingOption = options.find((option) => option.value.toLowerCase() === requestedCategory.toLowerCase());
				   if (matchingOption) {
					   categoryFilter.value = matchingOption.value;
				   }
				   filterJobs();
			   }
		   } catch (error) {
			   console.error("Job loading error:", error);
			   jobContainer.innerHTML = '<div class="col-12"><div class="alert alert-danger mb-0">Unable to load jobs right now. Please try again later.</div></div>';
			   filteredJobs = [];
			   if (noJobsMessage) {
				   noJobsMessage.style.display = "none";
			   }
			   updateJobCount(0, 0);
			   pagination.innerHTML = "";
		   }
	}

	document.addEventListener("click", function (event) {
		const button = event.target.closest(".viewBtn");
		if (!button) {
			return;
		}

		const job = button.closest(".job-item");
		if (!job) {
			return;
		}

		const titleElement = job.querySelector("h5");
		const companyElement = job.querySelector("small");

		document.getElementById("modalTitle").innerText = job.dataset.title || (titleElement ? titleElement.innerText : "Job");
		document.getElementById("modalCompany").innerText = "Company: " + (companyElement ? companyElement.innerText : "Company");
		document.getElementById("modalLocation").innerText = "Location: " + (job.dataset.location || "Not specified");
		document.getElementById("modalSalary").innerText = "Salary: " + (job.querySelector("p:nth-of-type(2)") ? job.querySelector("p:nth-of-type(2)").innerText.replace("💰 ", "") : "Not disclosed");
		document.getElementById("modalDesc").innerText = job.dataset.desc || "No description available.";

		new bootstrap.Modal(document.getElementById("jobModal")).show();
	});

	searchInput.addEventListener("keyup", filterJobs);
	locationFilter.addEventListener("change", filterJobs);
	categoryFilter.addEventListener("change", filterJobs);

	typeFilters.forEach((cb) => cb.addEventListener("change", filterJobs));
	expFilters.forEach((cb) => cb.addEventListener("change", filterJobs));
	salaryFilters.forEach((cb) => cb.addEventListener("change", filterJobs));

	loadJobs();
});
