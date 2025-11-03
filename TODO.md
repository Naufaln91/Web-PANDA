# TODO: Change Login from Phone Number to Email

## Overview
Change the login system from phone number (nomor_hp) to email while keeping OTP functionality.

## Tasks

### 1. Database Changes
- [ ] Create migration to rename `nomor_hp` to `email` in users table
- [ ] Create migration to rename `nomor_hp` to `email` in whitelists table
- [ ] Create migration to rename `nomor_hp` to `email` in otp_codes table

### 2. Model Updates
- [ ] Update User model: change fillable, validation rules
- [ ] Update Whitelist model: change fillable, methods
- [ ] Update OtpCode model: change fillable, methods

### 3. Controller Updates
- [ ] Update LoginController: validation rules, methods
- [ ] Update AdminController: validation rules, methods

### 4. View Updates
- [x] Update login.blade.php: change input field to email
- [x] Update Admin/Whitelist/index.blade.php: change labels and placeholders
- [x] Update Admin/Akun/index.blade.php: change table headers and labels

### 5. Test Updates
- [ ] Update LoginControllerTest: change test data and assertions
- [ ] Update AdminControllerTest: change test data and assertions

### 6. Route and Config Updates
- [ ] Check and update any hardcoded references
- [ ] Update validation messages

### 7. Testing
- [ ] Run migrations
- [ ] Test login functionality
- [ ] Test admin whitelist management
- [ ] Test admin account management
