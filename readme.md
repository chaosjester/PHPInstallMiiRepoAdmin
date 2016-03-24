# PHPInstallMiiRepoAdmin

This is a PHP admin tool to compile several files required by the InstallMii 3DS Homebrew app.

You can get InstallMii here - https://gbatemp.net/threads/wip-installmii-graphical-repository-downloader.406097/

This tool will create your repo.list, package.list and scrape information from .smdh files to create the packages.json.

While it is preferable to have an SMDH file available for scraping, this tool will still add an entry if none exists.

Packages can be modified once imported.

The index has a download link to the repo.list, the package.list and packages.json files are all for the backend.

# Requirements:

!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

Homebrew apps MUST be in a folder named 3ds under the repo root directory
For example, if your repo is http://repo.example.com/ apps must be in
http://repo.example.com/3ds or if in http://example.com/repo the 3ds
directory must be in http://example.com/repo/3ds

!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

Apache

PHP >= 5.5 - This is due to password mangement, there is no exceptions to this

PHP MUST run as your user, not a service account unless that account has permissions to the folders

MySQL 

Directories must be writable

An smdh file should be present in the homebrew application folder or some manual configuration is required

# Instructions:

Download latest release

You might need to create SQL database on your server, along with a user that has access to create tables and modify tables, though the installer may create them for you

Upload to webhost

Go to http://yourrepo.com/, you will be directed to the install page

On the install page, follow the directions to create the database and user

Head back to http://yourrepo.com/admin and ensure you can log in

Create additional admin accounts if required, otherwise it is advised to delete the /admin/install directory

Once in, the interface is pretty straight forward.


