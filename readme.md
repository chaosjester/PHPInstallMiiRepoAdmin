# PHPInstallMiiRepoAdmi

This is a PHP admin tool to compile several files required by the InstallMii 3DS Homebrew app.

You can get InstallMii here - https://gbatemp.net/threads/wip-installmii-graphical-repository-downloader.406097/

This tool will create your repo.list, package.list and scrape information from .smdh files to create the packages.json.

While it is preferable to have an SMDH file available for scraping, this tool will still add an entry if none exists.

Packages can be modified once imported.

The index has a download link to the repo.list, the package.list and packages.json files are all for the backend.

Requirements:

!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
!!!!!! Homebrew apps MUST be in a folder named 3ds under the repo root directory !!!!!!
!!!!!! For example, if your repo is http://repo.example.com/ apps must be in     !!!!!!
!!!!!! http://repo.example.com/3ds or if in http://example.com/repo the 3ds      !!!!!!
!!!!!! directory must be in http://example.com/repo/3ds                          !!!!!!
!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

Apache

PHP >= 5.5 - This is due to password mangement, there is no exceptions to this

Directories must be writable

An smdh file should be present in the homebrew application folder or some manual configuration is required

Instructions:

Download latest release

Create SQL database on your server, along with a user that has access to create tables and modify tables

Unzip, edit /reposettings.php and /admin/includes/connection.php

Upload to webhost

Go to http://yourrepo.com/admin/register.php and click the "Create Tables" button to create the database tables

On the register page, create your admin account

Head back to http://yourrepo.com/admin and ensure you can log in

Create additional admin accounts if required, otherwise it is advised to delete the register.php file

Once in, the interface is pretty straight forward.


