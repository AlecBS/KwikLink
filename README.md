# KwikLink
Similar to LinkTree, this is a build-in-public project built using Wizard's Toolkit.

Videos were created showing exactly how this was built:

https://www.youtube.com/playlist?list=PL-ggE-fPIdy76ND6bL9bxUJtS_QOAp_--

## Docker Setup

First step is to download the Wizard's Toolkit Docker environment at:

If you have Docker then you can create the entire website, PHP, and MySQL database
by simply running a few .sh scripts.  All the instructions are listed here:

https://hub.docker.com/r/proglabs/wizards-toolkit

## GIT Hub

If you do not use Docker, you can retrieve Wizard's Toolkit from GitHub at:

https://github.com/AlecBS/WizardsToolkit

## Wizard's Toolkit

Once downloaded and installed, you can find additional information here:

<p align="center">
  <a href="https://wizardstoolkit.com/docs/">Documentation</a>
  <a href="https://wizardstoolkit.com/tutorials.htm">Tutorials</a>
  <a href="https://wizardstoolkit.com/wtk.php">Live Demo</a>
</p>

**After Database Created**

The final step for Wizard's Toolkit is to create the database and insert initial data.

```bash
./SETUP_MYSQL.sh
```

After that is completed, open up your phpMyAdmin at:

```
http://127.0.0.1:8080/

Server:     wtk_db_mysql
Username:   root
Password:   LowCodeViaWTK
```

Then in the `wiztools` database, run the SQL scripts in the \KwikFiles\customTables.sql
