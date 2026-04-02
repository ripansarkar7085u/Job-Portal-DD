# Messaging Feature - Quick Start Guide

## 🚀 Getting Started in 3 Steps

### Step 1: Run the Migration (One-time setup)
Open your browser and navigate to:
```
http://localhost/job-portal/api/messages_migration_unified.php
```

You should see a success message like:
```json
{
  "success": true,
  "message": "Migration completed successfully! Total messages: X",
  "steps": [...]
}
```

### Step 2: Test as a User
1. Login as a user (job seeker)
2. Click "Messages" in the left sidebar
3. You'll see a list of companies (if you've messaged any before)
4. Click a company to view the conversation
5. Type a message and press Enter or click Send
6. Your message appears instantly!

### Step 3: Test as a Company
1. Login as a company (employer)
2. Click "Messages" in the left sidebar  
3. You'll see a list of users who've messaged you
4. Click any user to open the chat modal
5. Type a reply and send
6. The user will see it when they refresh or after 3 seconds

---

## 🎯 Key Features You'll Notice

### For Users:
- 🔴 **Unread Badge**: Red circle shows number of unread messages
- ⚡ **Auto-refresh**: Messages update every 3 seconds automatically
- 📱 **Mobile Friendly**: On mobile, you see list OR chat (with back button)
- 💬 **Easy Sending**: Press Enter to send, or click the send button
- ✅ **Read Receipts**: Messages auto-mark as read when you view them

### For Companies:
- 🔍 **User Search**: Search bar to find any user
- 🪟 **Modal Chat**: Clean popup window for each conversation
- ⚡ **Real-time**: Same 3-second auto-refresh
- 📊 **Conversation List**: See all users you've chatted with

---

## 📋 What to Check

### ✅ Checklist
- [ ] Migration ran successfully
- [ ] Can see Messages in sidebar
- [ ] Can send a message
- [ ] Message appears in conversation
- [ ] Other party receives the message
- [ ] Unread badge appears (red circle)
- [ ] Badge count decreases when reading
- [ ] Mobile view works (responsive)
- [ ] No JavaScript errors in console

---

## 🐛 Troubleshooting

### Messages not showing?
- Clear browser cache (Ctrl+Shift+Del)
- Check browser console for errors (F12)
- Verify you ran the migration script
- Check database connection in `config/database.php`

### Can't send messages?
- Make sure you're logged in
- Check if company/user exists in database
- Look for red error messages
- Check browser Network tab (F12) for API errors

### Unread badge not updating?
- Wait 10 seconds (auto-update interval)
- Refresh the page
- Check if JavaScript is enabled
- Open browser console to see any errors

---

## 💡 Tips & Tricks

### For Users:
- Press Enter to send (faster than clicking)
- Messages auto-scroll to bottom
- Unread count updates every 10 seconds
- No need to refresh page manually

### For Companies:
- Use search bar to find users quickly
- Click anywhere outside modal to close
- Messages save automatically
- Can message multiple users simultaneously

---

## 📞 Support

If you encounter issues:

1. **Check Documentation:**
   - See `MESSAGING_DOCUMENTATION.md` for details
   - See `IMPLEMENTATION_COMPLETE.md` for summary

2. **Common Issues:**
   - 404 errors → Check file paths
   - Blank page → Check PHP errors
   - No updates → Check JavaScript console
   - Database errors → Run migration again

3. **Developer Info:**
   - All APIs in `api/` folder
   - User interface in `user/messages.php`
   - Company interface in `company/messages.php`
   - CSS styles in `user/user.css`

---

## 🎉 You're All Set!

The messaging system is now fully operational. Enjoy seamless communication between job seekers and employers!

**Questions?** See the full documentation in `MESSAGING_DOCUMENTATION.md`.
