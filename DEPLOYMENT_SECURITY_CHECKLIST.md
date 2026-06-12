# Production Deployment Security Checklist

Use this checklist to ensure your production deployment is secure.

## Pre-Deployment

- [ ] Run `composer audit --locked` and resolve all vulnerabilities
- [ ] Run `php artisan test` and verify all tests pass
- [ ] Run `vendor/bin/phpstan analyse app --level=max` for static analysis
- [ ] Review all environment variables in `.env`
- [ ] Ensure `.env` file is in `.gitignore` and not committed
- [ ] Generate strong `APP_KEY`: `php artisan key:generate`
- [ ] Set `APP_DEBUG=false` in production
- [ ] Set `APP_ENV=production`

## Environment Configuration

- [ ] Configure database credentials with strong passwords
- [ ] Configure OAuth provider credentials securely
- [ ] Set session encryption: `SESSION_ENCRYPT=true`
- [ ] Enable secure cookies: `SESSION_SECURE_COOKIES=true`
- [ ] Set HTTP-only cookies: `SESSION_HTTP_ONLY=true`
- [ ] Set same-site policy: `SESSION_SAME_SITE=lax`
- [ ] Configure logging to appropriate level (not debug)
- [ ] Set up log rotation for disk usage management

## Server Configuration

### HTTPS/SSL

- [ ] Obtain SSL/TLS certificate (Let's Encrypt recommended)
- [ ] Configure web server for HTTPS
- [ ] Force HTTP to HTTPS redirect
- [ ] Set HSTS header (configured by SecurityHeadersMiddleware)
- [ ] Use modern TLS versions (1.2+)
- [ ] Disable weak cipher suites

### Web Server (Nginx/Apache)

```nginx
# Nginx example
server {
    listen 443 ssl http2;
    server_name yourdomain.com;
    
    ssl_certificate /path/to/certificate.crt;
    ssl_certificate_key /path/to/private.key;
    
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;
    ssl_prefer_server_ciphers on;
    
    # Redirect HTTP to HTTPS
    if ($scheme != "https") {
        return 301 https://$server_name$request_uri;
    }
    
    root /var/www/shortlink/public;
    index index.php;
    
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

### PHP Configuration

- [ ] Enable opcode caching (OPcache)
- [ ] Disable dangerous functions in `php.ini` (if applicable):
  ```ini
  disable_functions = exec,passthru,shell_exec,system,proc_open,popen,curl_exec,curl_multi_exec,parse_ini_file,show_source
  ```
- [ ] Set appropriate `max_execution_time`
- [ ] Set appropriate memory limits
- [ ] Enable error logging to file (not display)

## Database Security

- [ ] Create database user with minimal required permissions
- [ ] Use strong, randomly generated passwords
- [ ] Enable SSL for database connections if remote
- [ ] Restrict database user to specific host(s)
- [ ] Backup database regularly (at least daily)
- [ ] Store backups securely (separate location)
- [ ] Test backup restoration procedures
- [ ] Monitor disk usage and set up alerts

Database user permissions:
```sql
GRANT SELECT, INSERT, UPDATE, DELETE ON shortlink.* TO 'app_user'@'localhost';
GRANT EXECUTE ON shortlink.* TO 'app_user'@'localhost';
```

## Application Security

- [ ] Run migrations: `php artisan migrate --force`
- [ ] Clear configuration cache: `php artisan config:clear`
- [ ] Cache configuration for performance: `php artisan config:cache`
- [ ] Cache routes for performance: `php artisan route:cache`
- [ ] Cache events for performance: `php artisan event:cache`
- [ ] Set up log rotation (daily or size-based)
- [ ] Configure monitoring and alerts for errors
- [ ] Set up security event logging monitoring

## File & Directory Permissions

```bash
# Application root
chmod 755 /var/www/shortlink

# Storage (writable by web server)
chmod 775 /var/www/shortlink/storage
chmod 775 /var/www/shortlink/storage/logs

# Bootstrap cache
chmod 775 /var/www/shortlink/bootstrap/cache

# Database (if using SQLite)
chmod 664 /var/www/shortlink/database/database.sqlite

# Web root
chmod 755 /var/www/shortlink/public

# Set correct owner
chown -R www-data:www-data /var/www/shortlink
```

## Firewall & Network Security

- [ ] Configure firewall to block unnecessary ports
- [ ] Allow only required ports:
  - Port 80 (HTTP - for redirect to HTTPS)
  - Port 443 (HTTPS)
  - Port 3306 (MySQL - internal only)
- [ ] Block direct database access from external networks
- [ ] Use security groups/ACLs to restrict access
- [ ] Monitor for suspicious network activity

## Monitoring & Logging

- [ ] Monitor application logs: `tail -f storage/logs/laravel.log`
- [ ] Monitor security logs: `tail -f storage/logs/security.log`
- [ ] Set up log aggregation (e.g., ELK Stack, Splunk)
- [ ] Configure alerts for:
  - Authentication failures
  - Rate limit violations
  - Database errors
  - Application errors
- [ ] Monitor server resources (CPU, memory, disk)
- [ ] Set up performance monitoring

## Backup & Disaster Recovery

- [ ] Automated daily database backups
- [ ] Encrypted backup storage
- [ ] Off-site backup replication
- [ ] Tested backup restoration procedure
- [ ] Disaster recovery plan documented
- [ ] Recovery Time Objective (RTO) defined
- [ ] Recovery Point Objective (RPO) defined

## Updates & Maintenance

- [ ] Subscribe to Laravel security advisories
- [ ] Subscribe to dependency security alerts
- [ ] Plan regular security updates
- [ ] Test updates in staging before production
- [ ] Schedule maintenance windows for updates
- [ ] Keep PHP and OS updated
- [ ] Review security advisories monthly

## Access Control

- [ ] SSH key-based authentication only (no passwords)
- [ ] Disable root login
- [ ] Use non-root user for deployment
- [ ] Limit SSH access with firewall/IP whitelist
- [ ] Review user permissions regularly
- [ ] Remove unused user accounts
- [ ] Implement sudo for privilege escalation
- [ ] Log all administrative actions

## Secrets Management

- [ ] Use environment variables for all secrets
- [ ] Never commit secrets to version control
- [ ] Rotate API keys and credentials regularly
- [ ] Use secrets management tools (e.g., HashiCorp Vault)
- [ ] Audit secrets access regularly

## Compliance

- [ ] Review applicable regulations (GDPR, etc.)
- [ ] Implement data protection measures
- [ ] Document data handling procedures
- [ ] Obtain necessary consent for data collection
- [ ] Implement privacy policy
- [ ] Handle data requests appropriately

## Testing

- [ ] Run security scanning in CI/CD
- [ ] Test all authentication flows
- [ ] Test rate limiting effectiveness
- [ ] Test authorization policies
- [ ] Verify security headers are present
- [ ] Test HTTPS enforcement
- [ ] Penetration testing (recommended)
- [ ] Regular security audits

## Documentation

- [ ] Document deployment procedure
- [ ] Document security measures in place
- [ ] Document incident response plan
- [ ] Document backup procedures
- [ ] Document update procedures
- [ ] Maintain security audit logs
- [ ] Keep changelog of security updates

## Post-Deployment

- [ ] Verify application functionality
- [ ] Verify security headers in responses
- [ ] Verify HTTPS enforcement
- [ ] Verify rate limiting is working
- [ ] Verify logging is operational
- [ ] Monitor for errors or issues
- [ ] Test backup procedures
- [ ] Document deployment in change log

## Ongoing Maintenance

### Weekly
- [ ] Monitor security logs for suspicious activity
- [ ] Review application error logs
- [ ] Monitor server resources

### Monthly
- [ ] Review security updates available
- [ ] Audit access logs
- [ ] Test backup restoration

### Quarterly
- [ ] Security audit
- [ ] Dependency vulnerability review
- [ ] Access control review

### Annually
- [ ] Full security assessment
- [ ] Penetration testing
- [ ] Disaster recovery testing
- [ ] Compliance review

## Emergency Procedures

### If Compromised

1. Isolate affected system from network
2. Preserve logs for investigation
3. Notify users and stakeholders
4. Restore from known-good backup
5. Investigate root cause
6. Apply patches and updates
7. Restore system to production
8. Monitor for further issues
9. Document incident

### Incident Response Plan

- [ ] Incident response team identified
- [ ] Escalation procedures documented
- [ ] Communication plan established
- [ ] Legal/compliance notifications planned
- [ ] Recovery procedures defined

---

## Additional Resources

- [OWASP Top 10 Proactive Controls](https://owasp.org/www-project-proactive-controls/)
- [OWASP Secure Deployment Checklist](https://cheatsheetseries.owasp.org/)
- [Laravel Security](https://laravel.com/docs/security)
- [PHP Security](https://www.php.net/manual/en/security.php)

---

**Last Updated**: June 2024
