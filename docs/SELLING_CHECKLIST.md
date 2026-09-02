# Pre-Sale Checklist

Work through this list before putting DonateBazaar up for sale. It exists because a repo with stray debug scripts, real credentials, or unseeded demo data will cost you far more than the hour it takes to clean up.

---

## Code Cleanup

- [x] Remove all debug PHP files (`check_*.php`, `debug_*.php`, `tmp_*.php`)
- [x] Remove SQL dump files (*.sql)
- [x] Remove temporary reports (`salary_report.md`, `test-results.txt`)
- [x] Update `.gitignore` to exclude all temporary files
- [x] Remove `__pycache__` directory

## Security

- [ ] Remove actual API keys from `.env` (use placeholders)
- [ ] Remove actual OAuth secrets from `.env`
- [ ] Verify no hardcoded passwords in codebase
- [ ] Rotate any exposed keys (Google OAuth, Razorpay)
- [ ] Remove or anonymize any real user data in seeders

## Documentation

- [x] Create comprehensive README.md
- [x] Create INSTALLATION.md with step-by-step setup
- [x] Create ARCHITECTURE.md with system design
- [x] Create API.md with endpoint documentation
- [ ] Add inline code comments for complex logic
- [ ] Document environment variables
- [ ] Create CHANGELOG.md

## Code Quality

- [ ] Run `php artisan test` — all tests pass
- [ ] Run `npm run build` — assets compile
- [ ] Fix any PHP syntax errors
- [ ] Remove unused imports
- [ ] Format code with Laravel Pint: `vendor/bin/pint`

## Demo Preparation

- [ ] Deploy to staging server
- [ ] Seed demo data (campaigns, users, donations)
- [ ] Create demo admin account
- [ ] Create demo user account
- [ ] Record walkthrough video
- [ ] Take screenshots of key features

## Legal

- [ ] Verify ownership of all code
- [ ] Check license compatibility of dependencies
- [ ] Add LICENSE file (MIT recommended)
- [ ] Remove any proprietary third-party code
- [ ] Document third-party licenses

---

## Quick Commands

### Clean Debug Files

```bash
Remove-Item -Path "check_*.php","debug_*.php","tmp_*.php","extract_*.php","inspect_*.php","schema_*.php","schema_*.json","test_*.php","final_check.php" -Force
```

### Clean SQL Dumps

```bash
Remove-Item -Path "*.sql" -Force
```

### Format Code

```bash
vendor/bin/pint
```

### Run Tests

```bash
php artisan test
```

### Build Assets

```bash
npm run build
```

---

## Demo Credentials Template

After seeding, hand these to buyers:

| Role | Email | Password |
|---|---|---|
| Admin | admin@donatebazaar.com | password |
| User | user@donatebazaar.com | password |

---

## Selling Points to Highlight

1. **Production-Grade Financial Architecture**
   - Double-entry wallet system
   - Settlement state machine with retry logic
   - Razorpay integration with webhook verification
   - Idempotency handling

2. **Comprehensive Admin Panel**
   - 23 management modules
   - Full CRUD operations
   - Role-based access control

3. **Modern Development Practices**
   - Docker containerization
   - Queue-based background processing
   - Event-driven architecture
   - Redis caching

4. **Security**
   - CSP with nonce
   - Encrypted sensitive fields
   - Rate limiting
   - Security headers

5. **Testing**
   - 89 test files
   - Playwright E2E tests
   - Form validation tests