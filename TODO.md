# TODO: Create Unit Tests for Controllers

## Tasks
- [x] Create tests/Feature/DashboardControllerTest.php
  - [x] Test admin() method: assert view 'admin.dashboard' with correct data
  - [x] Test guru() method: assert view 'guru.dashboard' with correct data
  - [x] Test waliMurid() method: assert view 'wali-murid.dashboard' with correct data

- [x] Create tests/Feature/MateriControllerTest.php
  - [x] Test index() method: assert view 'materi.index' with materis data
  - [x] Test alfabet() method: assert view 'materi.alfabet'
  - [x] Test warna() method: assert view 'materi.warna'
  - [x] Test hewan() method: assert view 'materi.hewan'

- [x] Create tests/Feature/PermainanControllerTest.php
  - [x] Test index() method: assert view 'permainan.index' with permainans data
  - [x] Test puzzle() method: assert view 'permainan.puzzle'

- [x] Create tests/Feature/Auth/LoginControllerTest.php
  - [x] Test showLoginForm() method: assert view 'auth.login'
  - [x] Test loginAdmin() method: success and failure cases
  - [x] Test requestOtp() method: success and validation failures
  - [x] Test verifyOtp() method: success, invalid OTP, new user flow
  - [x] Test completeProfile() method: success and validation
  - [x] Test logout() method: logout and redirect

## Notes
- Use RefreshDatabase trait for all tests
- Create test users with different roles (admin, guru, wali_murid)
- For LoginController, may need to create Whitelist and OtpCode test data
- Run tests after creation to ensure they pass
