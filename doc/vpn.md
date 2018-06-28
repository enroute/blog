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


