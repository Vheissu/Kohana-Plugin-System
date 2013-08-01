<?php

// Include Markdown
include_once "markdown.php";

// When message text is parsed, call kmarkdown
Plugins::add_action("parse.message", "kmarkdown", 10);

// Plugin install hook
Plugins::add_action("install_kmarkdown", "install_plugin", 10);

function install_plugin()
{
    return true;
}

function kmarkdown($text)
{
    $markdown_value = Markdown($text);
    return $markdown_value;
}
