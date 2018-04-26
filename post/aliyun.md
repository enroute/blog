
# debian 9
## Issues
### apt-get install not generating configure files after reinstall
use dpkg -P to purge the package:
```
apt-get remove xx
dpkg -P xx
apt-get install xx
```

#Nginx
##Issues
### Starting nginx: nginx: [emerg] socket() [::]:80 failed (97: Address family not supported by protocol)
comment out the following line in /etc/nginx/sites-enabled/default:
```
listen       [::]:80 default_server;
```

### Set route to index.php
Try the following in nginx conf, which tries $uri, then $uri/, then
fallback to /index.php which can use $_SERVER['REQUEST_URI'] to
identify the request URI.
```
location / {
    try_files $uri $uri/ /index.php;
}
```

# Database
## 3306 port
```
netstat -nlp
tcp        0      0 127.0.0.1:3306            0.0.0.0:*               LISTEN      16181/mysqld

find /etc/mysql | xargs grep 127.0.0.1
/etc/mysql/mariadb.conf.d/50-server.cnf:bind-address           = 127.0.0.1

#Change it to
bind-address           = 0.0.0.0
```

## poem

CREATE TABLE IF NOT EXISTS `poem_title` (
`id` int(10) NOT NULL auto_increment,
`title` varchar(128),
PRIMARY KEY( `id` )
);

CREATE TABLE IF NOT EXISTS `poem_type` (
`id` int(10) NOT NULL auto_increment,
`parent_id` int(10) DEFAULT -1,
`name` varchar(128),
`desc` varchar(255),
PRIMARY KEY( `id` )
);


CREATE TABLE IF NOT EXISTS `poem_author` (
`id` int(10) NOT NULL auto_increment,
`first_name` varchar(16),
`last_name` varchar(16),
`dynasty` varchar(16),
`gender` smallint(2),
`desc` text,
PRIMARY KEY( `id` )
);

# php
** laravel
*** install
sudo apt-get install composer
sudo apt-get install php7.0-zip
composer global require "laravel/install"

sudo apt-get install php7.0-mbstring php-fdomdocument
composer create-project --prefer-dist laravel/laravel blog

* vim
** indent with space
/etc/vim/vimrc:

syntax enable
set shiftwidth=4
set ts=4
set tabstop=4
set expandtab
set autoindent
set smartindent


* java
** sprint boot
*** test failure

com.xx.xxApplicationTests > contextLoads FAILED
    java.lang.IllegalStateException
        Caused by: org.springframework.beans.factory.BeanCreationException
            Caused by: org.springframework.beans.BeanInstantiationException
                Caused by: java.lang.IllegalStateException

1 test completed, 1 failed
:test FAILED


build/reports/tests/test/classes/xxApplicationTests.html:
 Cannot load driver class: com.mysql.jdbc.Driver


Solution: add dependency in build.gradle:
    compile group: 'mysql', name: 'mysql-connector-java', version: '6.0.6'

* email
ISP blocks port 25 is almost an "industry standard". Don't expect to much.

https://www.tecmint.com/install-postfix-mail-server-with-webmail-in-debian/

1. Setup DNS
A   pop3 - xx.xx.xx.xx
A   smtp - xx.xx.xx.xx
A   mail - xx.xx.xx.xx
MX  @    - xx.xx.xx.xx
TXT @    - v=spf1 mx -all

Verify with "dig mx ztfun.com":

; <<>> DiG 9.10.3-P4-Ubuntu <<>> mx ztfun.com
;; global options: +cmd
;; Got answer:
;; ->>HEADER<<- opcode: QUERY, status: NOERROR, id: 49363
;; flags: qr rd ra; QUERY: 1, ANSWER: 1, AUTHORITY: 0, ADDITIONAL: 2

;; OPT PSEUDOSECTION:
; EDNS: version: 0, flags:; udp: 4000
;; QUESTION SECTION:
;ztfun.com.			IN	MX

;; ANSWER SECTION:
ztfun.com.		900	IN	MX	10 mail.ztfun.com.          <---- HERE

;; ADDITIONAL SECTION:
mail.ztfun.com.		900	IN	A	120.79.213.55               <---- AND HERE

;; Query time: 105 msec
;; SERVER: 10.128.161.52#53(10.128.161.52)
;; WHEN: Wed Mar 21 16:38:10 CST 2018
;; MSG SIZE  rcvd: 75


2. Install postfix with "apt-get install postfix"
Choose "Internet Site"
Setup mail name

3. Configure /etc/postfix/main.cf, as shown:
# See /usr/share/postfix/main.cf.dist for a commented, more complete version
smtpd_banner = $myhostname ESMTP
biff = no
# appending .domain is the MUA's job.
append_dot_mydomain = no
readme_directory = no
# See http://www.postfix.org/COMPATIBILITY_README.html -- default to 2 on
# fresh installs.
compatibility_level = 2
# TLS parameters
smtpd_tls_cert_file=/etc/ssl/certs/ssl-cert-snakeoil.pem
smtpd_tls_key_file=/etc/ssl/private/ssl-cert-snakeoil.key
smtpd_use_tls=yes
smtpd_tls_session_cache_database = btree:${data_directory}/smtpd_scache
smtp_tls_session_cache_database = btree:${data_directory}/smtp_scache
# See /usr/share/doc/postfix/TLS_README.gz in the postfix-doc package for
# information on enabling SSL in the smtp client.
smtpd_relay_restrictions = permit_mynetworks permit_sasl_authenticated defer_unauth_destination
myhostname = mail.debian.lan
mydomain = debian.lan
alias_maps = hash:/etc/aliases
alias_database = hash:/etc/aliases
#myorigin = /etc/mailname
myorigin = $mydomain
mydestination = $myhostname, $mydomain, localhost.$mydomain, localhost
relayhost = 
mynetworks = 127.0.0.0/8, 192.168.1.0/24
mailbox_size_limit = 0
recipient_delimiter = +
inet_interfaces = all
#inet_protocols = all
inet_protocols = ipv4
home_mailbox = Maildir/
# SMTP-Auth settings
smtpd_sasl_type = dovecot
smtpd_sasl_path = private/auth
smtpd_sasl_auth_enable = yes
smtpd_sasl_security_options = noanonymous
smtpd_sasl_local_domain = $myhostname
smtpd_recipient_restrictions = permit_mynetworks,permit_auth_destination,permit_sasl_authenticated,reject


4. Verify with "postconf -n" to dump postfix main configurations

5. Configure "master.cf" to setup tls. Uncomment the following lines:
submission inet n       -       y       -       -       smtpd
  -o syslog_name=postfix/submission
  -o smtpd_tls_security_level=encrypt
  -o smtpd_sasl_auth_enable=yes
  -o smtpd_reject_unlisted_recipient=no
  -o smtpd_client_restrictions=$mua_client_restrictions
  -o smtpd_helo_restrictions=$mua_helo_restrictions
  -o smtpd_sender_restrictions=$mua_sender_restrictions
  -o smtpd_recipient_restrictions=
  -o smtpd_relay_restrictions=permit_sasl_authenticated,reject
  -o milter_macro_daemon_name=ORIGINATING
smtps     inet  n       -       y       -       -       smtpd
  -o syslog_name=postfix/smtps
  -o smtpd_tls_wrappermode=yes
  -o smtpd_sasl_auth_enable=yes
  -o smtpd_reject_unlisted_recipient=no
  -o smtpd_client_restrictions=$mua_client_restrictions
  -o smtpd_helo_restrictions=$mua_helo_restrictions
  -o smtpd_sender_restrictions=$mua_sender_restrictions
  -o smtpd_recipient_restrictions=
  -o smtpd_relay_restrictions=permit_sasl_authenticated,reject
  -o milter_macro_daemon_name=ORIGINATING


6. Verify with "systemctl restart postfix & netstat -nlpt" to check 465 and 25 port
root@iZwz9ff8xmq3aavr4nukuhZ:/etc/postfix# netstat -ntlp
Active Internet connections (only servers)
Proto Recv-Q Send-Q Local Address           Foreign Address         State       PID/Program name
tcp        0      0 0.0.0.0:465             0.0.0.0:*               LISTEN      22446/master
tcp        0      0 0.0.0.0:25              0.0.0.0:*               LISTEN      22446/master

7. Test mails
# apt-get install mailutils
# echo "mail body"| mail -s "test mail" root
# mailq
# mail
# ls Maildir/
# ls Maildir/new/
# cat Maildir/new/[TAB]

8. Verify whatever happens with
# tailf /var/log/mail.log
# tailf /var/log/mail.err

9. Install dovecot IMAP
# apt install dovecot-core dovecot-imapd

Set "listen = *" in /etc/dovecot/dovecot.conf if unknown address family. Default is

listen = *, ::

10. /etc/dovecot/conf.d/10-auth.conf
disable_plaintext_auth = no
auth_mechanisms = plain login

11. /etc/dovecot/conf.d/10-mail.conf: to use Maildir location instead
    of Mbox format to store emails.
mail_location = maildir:~/Maildir

12. /etc/dovecot/conf.d/10-master.conf to setup smtp-auth block
# Postfix smtp-auth
unix_listener /var/spool/postfix/private/auth {
    mode = 0666
    user = postfix
    group = postfix
}

13. restart dovecot
# systemctl restart dovecot.service 
# systemctl status dovecot.service 
# netstat -tlpn

14. Setup client:
sever type: IMAP
account: test   <--- WITHOUT the @ztfun.com suffix
password: *****

IMAP server: mail.ztfun.com
SSL: CHECK
PORT: 993

SMTP server: mail.ztfun.com
SSL: CHECK
PORT: 465

15. DON'T EXPECT IT CAN SEND EMAIL OUTSIDE OF THE SERVER, SINCE
    OUTBOUND ON PORT 25 IS BLOCKED. But emails should be received
    without any issues.
