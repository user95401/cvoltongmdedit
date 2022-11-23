<?php
//database connection
$dbservername = "localhost"; //localhost?
$dbport = 3306;
$dbusername = "root";
$dbpassword = "123456g";
$dbname = "gdps";

$GDPSname = "cvoltongmdedit"; //project title
$helpLink = "https://github.com/user95401"; //help page, group, discord server and other
$projectIcon = "https://cdn.discordapp.com/emojis/998355573118615612.webp"; //project icon

$levelToGD = "true"; //set false if you dont allow users upload levels to another servers
$levelReupload = "true"; //set false if you dont allow users upload levels FROM another servers
$songAdd = "true"; //set false if you do not allow non-NG songs system
$songAdd_customName = "true"; //set false if you do not allow set a custom song name for non-NG songs system
$songAdd_customAuthor = "true"; //set false if you do not allow set a custom author name for non-NG songs system
$dashboardImprovedChart = "true"; //gooodLuck
$MultipleAccountsWithSameIP = "true"; //set false if you do not allow register accounts with the same IP
$deleteRatedLevels = "true"; //set true to allow level owners to delete them anyway
$autoWeeklyAndDaily = "true"; //randomly automatically adds Weekly and Daily levels when the moderator rate it
$autoWeeklyAndDailyChance = 20; // ^
$redirectTopArtist = "true"; //Indicates wether the server should ask the main GD servers for the top artists list or not.
$commandPrefix = "!";

//cron
$ABstars = 190; //default value (for autoban counting)
$ABcoins = 66; //default value (for autoban counting)
$ABpc = 0; //user coins default value (for autoban counting)
$ABdemons = 3; //default values (for autoban counting)
$autoban = "true";
$fixCPs = "true";
$fixnames = "true";
$friendsLeaderboardCron = "true";
$deleteUnnecessary = "true"; //delete worong songs and users / Fix levels with invalid passwords

//DANGER ZONE! I DONT RECOMMEND TOUCHING THIS
$preventAddingNewData = "false"; //set true to prevent adding new data (this will block much of the features, such as: unloading levels, comments, posts, updating user stats, adding a song, reloading levels, etc.)
$maintenanceMode = "true"; //set false to to prevent contact with the database

//adminLogin
$admenusername = "";
$admenpassword = "";
