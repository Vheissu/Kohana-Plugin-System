# Adding an installer

Sometimes you need an installer for moving asset files or running DB migrations.

This can be done by defining a protected ```_install``` method in your plugin class. This method should always return a boolean or throw an exception.

This method only gets called once (the first time your plugin is activated).