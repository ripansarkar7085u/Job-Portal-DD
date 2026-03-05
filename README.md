# Job Portal - Frontend

A job portal application built with HTML, CSS, and JavaScript.

## 📁 Folder Structure

```
Job-Portal-DD/
├── index.html              # Main navigation hub
├── README.md               # This file
│
├── admin/                  # 🔒 SUPER ADMIN PANEL (Joy's work)
│   ├── index.html          # Admin dashboard
│   ├── css/
│   │   └── admin.css       # Admin-specific styles
│   └── js/
│       └── admin.js        # Admin functionality
│
├── user/                   # 👤 USER/CANDIDATE PAGES (Team member's work)
│   ├── index.html          # User dashboard (to be created)
│   ├── css/
│   │   └── user.css        # User-specific styles
│   └── js/
│       └── user.js         # User functionality
│
└── assets/                 # 🎨 SHARED ASSETS (Common resources)
    ├── css/
    │   └── common.css      # Shared styles (variables, utilities)
    ├── js/
    │   └── common.js       # Shared utilities
    └── images/             # Shared images/icons
```

## 🚀 Development Guidelines

### To Avoid Merge Conflicts:

1. **Admin Panel Development** → Work only in `/admin/` folder
2. **User/Candidate Pages** → Work only in `/user/` folder  
3. **Shared Resources** → Coordinate before modifying `/assets/`

### Naming Conventions:
- Use lowercase filenames with hyphens: `job-listing.html`
- CSS classes: BEM methodology preferred
- JavaScript: camelCase for variables/functions

## ✅ Admin Dashboard Features

- **Dashboard Overview**: Stats for users, companies, jobs, blocked accounts
- **User Management**: View, search, filter, block/unblock users
- **Company Management**: View, search, filter, block/unblock companies
- **Job Management**: View, search, filter, delete jobs
- **Responsive Design**: Works on desktop and mobile

## 🔜 To Do (User Portal)

- [ ] User registration/login pages
- [ ] Job listing page
- [ ] Job detail page
- [ ] User profile page
- [ ] Application tracking

## 📦 External Dependencies

- **Google Fonts**: Inter
- **Font Awesome**: 6.4.0 (CDN)

## 🛠️ How to Run

Simply open `index.html` in a browser, or use Live Server extension in VS Code.

## 👥 Team

- **Admin Panel**: Joy
- **User Portal**: [Team Member Name]
