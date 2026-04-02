# Job Portal - Frontend

A job portal application built with HTML, CSS, and JavaScript.

## 🆕 New Feature: Messaging System

Complete end-to-end messaging system for communication between job seekers and employers!

**Quick Start:** See [QUICK_START.md](QUICK_START.md)  
**Full Documentation:** See [MESSAGING_DOCUMENTATION.md](MESSAGING_DOCUMENTATION.md)  
**Implementation Summary:** See [IMPLEMENTATION_COMPLETE.md](IMPLEMENTATION_COMPLETE.md)

### Messaging Features:
- ✅ Real-time messaging (3-second auto-refresh)
- ✅ Unread message badges
- ✅ Full conversation history
- ✅ Mobile responsive design
- ✅ Secure and performant

**To activate:** Run `http://localhost/job-portal/api/messages_migration_unified.php` once

---

##  Folder Structure

```
Job-Portal-DD/
├── index.html              # Main navigation hub
├── README.md               # This file
│
├── admin/                  #  SUPER ADMIN PANEL 
│   ├── index.html          # Admin dashboard
│   ├── css/
│   │   └── admin.css       # Admin-specific styles
│   └── js/
│       └── admin.js        # Admin functionality
│
├── user/                   #  USER/CANDIDATE PAGES 
│   ├── index.html          # User dashboard (to be created)
│   ├── css/
│   │   └── user.css        # User-specific styles
│   └── js/
│       └── user.js         # User functionality
│
└── assets/                 #  SHARED ASSETS (Common resources)
    ├── css/
    │   └── common.css      # Shared styles (variables, utilities)
    ├── js/
    │   └── common.js       # Shared utilities
    └── images/             # Shared images/icons
```

##  Development Guidelines

### To Avoid Merge Conflicts:

1. **Admin Panel Development** → Work only in `/admin/` folder
2. **User/Candidate Pages** → Work only in `/user/` folder  
3. **Shared Resources** → Coordinate before modifying `/assets/`

### Naming Conventions:
- Use lowercase filenames with hyphens: `job-listing.html`
- CSS classes: BEM methodology preferred
- JavaScript: camelCase for variables/functions

##  Admin Dashboard Features

- **Dashboard Overview**: Stats for users, companies, jobs, blocked accounts
- **User Management**: View, search, filter, block/unblock users
- **Company Management**: View, search, filter, block/unblock companies
- **Job Management**: View, search, filter, delete jobs
- **Responsive Design**: Works on desktop and mobile

## 💬 Messaging System Features

- **User to Company Messaging**: Job seekers can message employers
- **Company to User Replies**: Employers can respond to candidates
- **Real-time Updates**: Messages auto-refresh every 3 seconds
- **Unread Notifications**: Badge counters in sidebar
- **Conversation History**: Full message history preserved
- **Mobile Responsive**: Works perfectly on all devices
- **Secure**: XSS protection, SQL injection prevention, authentication required


## 🛠️ How to Run

Simply open `index.html` in a browser, or use Live Server extension in VS Code.

## 👥 Team
Joy , Ripan
