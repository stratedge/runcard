<?php

$packages = [
    'git' => null
];

foreach ($packages as $package => $callback) {
    ensure_package($package, $callback);
}

echo "--------------------------------------------------\n";
echo "# composer\n";
echo "--------------------------------------------------\n";
echo "  Checking for composer\n";

exec("command -v composer > /dev/null", $output = [], $code);

if ($code != 0) {
    echo "  Installing composer\n";
    exec('curl -sS https://getcomposer.org/installer | php');
    exec('mv composer.phar /usr/local/bin/composer');
} else {
    echo "  composer already installed\n";
}

echo '  Running composer update';
exec('composer update --working-dir /var/www 2> /dev/null');

function ensure_package($package, $callback)
{
    echo "--------------------------------------------------\n";
    echo "# $package\n";
    echo "--------------------------------------------------\n";
    echo "  Checking for $package\n";
    
    exec("dpkg -l $package > /dev/null 2>&1", $output = [], $code);

    if ($code != 0) {
        echo "  Installing $package\n";
        exec("apt-get install -y -qq $package 2> /dev/null");
    } else {
        echo "  $package already installed\n";
    }

    if (!is_null($callback)) {
        $callback();
    }
}

function replace_in_file($source, $find, $replace) {
    $contents = file_get_contents($source);
    $contents = str_replace($find, $replace, $contents);
    file_put_contents($source, $contents);
}

function query($sql) {
    exec(sprintf('mysql -e "%s"', $sql), $output);
    return $output;
}