# TODO: Implement Hashing for OTP Codes

## Steps to Complete
- [x] Create new database migration to hash existing unused OTP codes
- [x] Update OtpCode model to hash codes in generateOtp() and use Hash::check() in verifyOtp()
- [x] Update OtpCodeFactory to hash codes in definition()
- [x] Update LoginControllerTest to accommodate hashed codes in assertions
- [x] Run migration to hash existing data
- [x] Run tests to ensure functionality works
- [x] Manual verification if needed
