#VPN Setup

##Setup the server on BWH(Bandwagonhost)
There's a **OpenVPN Server** menu on the KiwiVM console. Just one click should work for Centos 6.x.

##For Android
Use package de.blinkt.openvpn, which can be downloaded from Google Play.

Change the .ovpn config file, mostly the path of files.

```
client
remote xxx.xxx.xxx.xxx 1194
dev tun
comp-lzo
ca /sdcard/vpn-config/ca.crt
cert /sdcard/vpn-config/client1.crt
key /sdcard/vpn-config/client1.key
route-delay 2
route-method exe
redirect-gateway def1
dhcp-option DNS 8.8.8.8
dhcp-option DNS 8.8.4.4
dhcp-option DNS 4.2.2.1
dhcp-option DNS 4.2.2.2
verb 3
```

Then import the **.ovpn** file within the APP.

##For Windows

Download openvpn and extract the key files to the config folder of
openvpn. Following shows the contents of my config folder.

```
C:\Program Files\OpenVPN\config>ls -l
total 26
-rw-r--r-- 1 user.name 1049089  340 Apr 25 13:57 README.txt
-rw-r--r-- 1 user.name 1049089 1818 Apr 25 04:18 ca.crt
-rw-r--r-- 1 user.name 1049089 1708 Apr 25 04:18 ca.key
-rw-r--r-- 1 user.name 1049089 5517 Apr 25 04:19 client1.crt
-rw-r--r-- 1 user.name 1049089 1106 Apr 25 04:19 client1.csr
-rw-r--r-- 1 user.name 1049089 1704 Apr 25 04:19 client1.key
-rw-r--r-- 1 user.name 1049089  248 Apr 25 04:19 host.localdomain.ovpn
```

Then connect. It should be OK now if everything works fine.

##For ubuntu
```
sudo apt-get install openvpn
```

## Missing the install Shadowsocks button on KiwiVM control panel?
1. Login KiwiVM
2. For Shadowsocks, click [here](https://kiwivm.64clouds.com/main-exec.php?mode=extras_shadowsocks)
3. For ShadowsocksR, click [here](https://kiwivm.64clouds.com/main-exec.php?mode=extras_shadowsocksr)

## Connection refused after installing shadowsocks?
To install shadowsocks server on Ubuntu:
```
sudo apt-get update
sudo apt-get install python-pip
sudo pip install shadowsocks
```

Create the configuration file, e.g., one may put it in /etc/shadowsocks.json, the content of which should be like:
```
{
    "server":"::",
    "server_port":9988,
    "local_address":"127.0.0.1",
    "local_port":1080,
    "password":"mypassword",
    "timeout":300,
    "method":"aes-256-cfb"
}
```

Note that the server is "::", otherwise, if one use "your.domain.com" as the "server" field, one may get the following output after SS daemon started:
```
$ netstat -ntlp
Active Internet connections (only servers)
Proto Recv-Q Send-Q Local Address           Foreign Address         State       PID/Program name
tcp        0      0 127.0.1.1:9988          0.0.0.0:*               LISTEN      24472/python
```
where the "127.0.1.1:9988" may refuse the client connection from outside the server. But with "::" as server, the output should look like:
```
$ netstat -ntlp
Active Internet connections (only servers)
Proto Recv-Q Send-Q Local Address           Foreign Address         State       PID/Program name
tcp6       0      0 ::::9988                0.0.0.0:*               LISTEN      24472/python
```
which accepts connection from outside.

One may test the connection with wget:
```
wget your.host.com:9988
```

If one gets error when starting the SS server with
```
ssserver -c /etc/shadowsocks.json -d start
```
then try update with the newest version with:
```
sudo pip install -U git+https://github.com/shadowsocks/shadowsocks.git@master
```

Make it auto start after system boot, add the following line to the end of /etc/rc.local:
```
/usr/local/bin/sserver -c /etc/shadowsocks..json -d start
```


## Test IP on BWH is in blacklist or not
### Option 1
1. Login KiwiVM
2. Go to URL [https://kiwivm.64clouds.com/main-exec.php?mode=blacklistcheck](https://kiwivm.64clouds.com/main-exec.php?mode=blacklistcheck)

### Option 2
Use [ping.pe](http://ping.pe) or [chinaz](http://ping.chinaz.com)
