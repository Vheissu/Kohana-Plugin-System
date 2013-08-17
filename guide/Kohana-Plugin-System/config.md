# Config

**dir** - The relative directory of where plugins can be stored.

## Manager

We tried to keep the process of managing your plugin as simple as possible,
check the [Plugin_Manager]'s documentation for more information.

**loader** - The manager driver you want to use (either Config or DB)

### Config
When using the config driver all you have to do is define a **dir**, in which the required config files will be stored. The dir you define will be stored in APPPATH/config/*{dir}*, make sure this directory is writeable, because we'll writing 2 Config files there to manage the plugins' state.

### DB
If you'd rather use a database table to manage your plugins' state, you first have to run *plugins.sql* that's bundled with this module.

In the config file you can define the **table**'s name (defaults to plugins) and which database **connection** you'd want it to use (defaults to *default*).