# TODO: Recreate Feature Tests for All Controllers

## Overview
Recreate all feature tests for controllers due to recent changes in the codebase. Ensure tests cover all new methods, fields, and logic updates.

## Controllers and Tests to Update

### 1. DashboardControllerTest
- [ ] Update admin dashboard test to include `totalKuis` in view data
- [ ] Ensure guru dashboard test matches `myKuis` and `publishedKuis`
- [ ] Ensure wali_murid dashboard test matches `publishedKuis` and `user`
- [ ] Verify authentication and role-based access tests

### 2. KuisControllerTest
- [ ] Add `penunjukan_jawaban` field to store and update tests
- [ ] Add tests for new methods:
  - [ ] `histori` - access histori page
  - [ ] `getDetailHistori` - API get detail histori
  - [ ] `destroyHistori` - delete histori entry
  - [ ] `reorderSoal` - reorder soal
  - [ ] `getSingleSoal` - get single soal for editing
- [ ] Update soal management tests (store, update, destroy)
- [ ] Add tests for histori access control (admin/guru only)
- [ ] Ensure proper authorization checks for all methods

### 3. MateriControllerTest
- [ ] Verify all materi page access tests (alfabet, warna, hewan, angka, buah, transportasi)
- [ ] Ensure view assertions are correct
- [ ] Test unauthenticated access redirects

### 4. PermainanControllerTest
- [ ] Verify all permainan page access tests (puzzle, hitung, cocokkan_pasangan, urutkan_angka, menyusun_kata, labirin)
- [ ] Ensure view assertions are correct
- [ ] Test unauthenticated access redirects

### 5. Admin/AdminControllerTest
- [ ] Update whitelist management tests (index, store, destroy)
- [ ] Update akun management tests (index, destroy)
- [ ] Ensure proper admin-only access
- [ ] Test destroy admin user prevention

### 6. Auth/LoginControllerTest
- [ ] Add tests for `resendOtp` method
- [ ] Add tests for rate limiting on admin login
- [ ] Add tests for `last_login` field updates
- [ ] Update OTP verification tests
- [ ] Ensure complete profile tests cover new fields
- [ ] Test logout functionality

## General Test Requirements
- [ ] Use RefreshDatabase trait
- [ ] Disable CSRF middleware where needed
- [ ] Use proper factories for test data
- [ ] Test both success and failure scenarios
- [ ] Test authorization and authentication
- [ ] Assert correct view data and JSON responses
- [ ] Test redirects and status codes

## Final Steps
- [ ] Run all tests to ensure they pass
- [ ] Fix any failing tests
- [ ] Verify test coverage is comprehensive
