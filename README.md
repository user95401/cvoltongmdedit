
# cvoltongmdedit
GMDprivateServer is an emulator of the Geometry Dash server, for which I have added some upgrades 😘
such as
 - **THE BEST DASHBOARD**? (improved chart, level/user/song info get, all   
   created reupload/mod/stats/cleanup/browse/account scripts ported and 
   created more, some animations, AND MANY MORE💥 :)
 - Auto cron job:    autoban (+ defaults in config), fixCPs, fixnames,
   deleteUnnecessary    (worong songs and users), levels count (not
   configurable),    friendsLeaderboard.
 - New configurations: GDPSname, helpLink,    projectIcon, levelToGD
   (tool toggle), levelReupload (tool toggle),    songAdd
   songAdd_customName songAdd_customAuthor (tool toggle),   
   dashboardCardAlignCenter, dashboardImprovedChart,   
   MultipleAccountsWithSameIP, deleteRatedLevels, cron parts toggle and 
   values, preventAddingNewData, maintenanceMode and more?
 - delete command own check
 - downloads and likes DEFAULT '0';
 - AdminTools for roles
 - save ip in accounts table
 - config file generator (simple config 😑)
 - auto daily and weekly adding per rate
 - something else

Supported version of Geometry Dash: 1.0 - 2.11. But I check only 2.1/

(See [the backwards compatibility section of this article](https://github.com/Cvolton/GMDprivateServer/wiki/Deliberate-differences-from-real-GD) for more information)

Required version of PHP: 7+ (tested on 7.3.27 (native))

### Setup
1) Upload the files on a webserver
2) Import database.sql into a MySQL/MariaDB database
4) Configurate server on config/index.php (web config feature)
5) Edit the links in GeometryDash.exe (some are base64 encoded since 2.1, remember that)

#### Updating the server on org GMDprivateServer
1) Upload the files on a webserver
2) Import database.sql (from this) into a MySQL/MariaDB database
3) Re-configurate server on config/index.php (web config feature)

### Credits
Base for account settings and the private messaging system by someguy28

Using this for XOR encryption - https://github.com/sathoro/php-xor-cipher - (incl/lib/XORCipher.php)

Using this for cloud save encryption - https://github.com/defuse/php-encryption - (incl/lib/defuse-crypto.phar)

Most of the stuff in generateHash.php has been figured out by pavlukivan and Italian APK Downloader, so credits to them

DASHBOARD CREATORS: Cvolton, user666, donalex1
donalex1 - some functions in mainLib (isbaned check etc)

All oher changes by user666 xd
