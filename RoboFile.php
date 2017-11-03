<?php

use Globalis\Robo\Core\Command;
use Globalis\Robo\Core\SemanticVersion;

class RoboFile extends \Globalis\Robo\Tasks
{

    private $fileProperties        = __DIR__ . '/.robo/properties.php';
    private $fileVars              = __DIR__ . '/config/vars.php';
    private $fileApplication       = __DIR__ . '/config/application.php';
    private $fileConfigLocal       = __DIR__ . '/config/local.php';
    private $fileConfigLocalSample = __DIR__ . '/config/local.sample.php';
    private $fileConfigSaltKeys    = __DIR__ . '/config/salt-keys.php';
    private $dirHtaccessParts      = __DIR__ . '/config/htaccess/';
    private $fileHtaccess          = __DIR__ . '/web/.htaccess';
    private $fileBinWPCli          = __DIR__ . '/vendor/bin/wp';
    private $saltKeysUrl           = 'https://api.wordpress.org/secret-key/1.1/salt/';

    public function config($opts = ['only-missing' => false])
    {
        $force = true !== $opts['only-missing'];
        $this->configVariables = $this->taskConfiguration()
            ->initConfig($this->getProperties('config'))
            ->initLocal($this->getProperties('local'))
            ->initSettings($this->getProperties('settings'))
            ->configFilePath($this->fileVars)
            ->force($force)
            ->run()
            ->getData();
    }

    public function install()
    {
        $this->loadConfig();
        $this->installPackages();
        $this->installTheme();
        $this->createConfigLocal();
        $this->wpGenerateSaltKeys();
        $this->build();
    }

    private function createConfigLocal()
    {
        if (!file_exists($this->fileConfigLocal)) {
            copy($this->fileConfigLocalSample, $this->fileConfigLocal);
        }
    }

    public function installPackages()
    {
        $this->taskComposerInstall()
            ->preferDist()
            ->run();
    }

    public function installTheme()
    {
        // Write your own task, according to your theme architecture
    }

    public function build()
    {
        $this->buildHtaccess();
        $this->buildTheme();
    }

    public function buildHtaccess($env = null, $filePath = null)
    {
        if (!isset($env)) {
            $env = $this->getConfig('ENVIRONEMENT');
        }
        if (!isset($filePath)) {
            $filePath = $this->fileHtaccess;
        }

        $this->processHtaccessParts($env, $filePath, [
            $this->dirHtaccessParts . '/htaccess-general',
            $this->dirHtaccessParts . '/htaccess-performances',
            $this->dirHtaccessParts . '/htaccess-redirect',
            $this->dirHtaccessParts . '/htaccess-security',
            $this->dirHtaccessParts . '/htaccess-urls',
            $this->dirHtaccessParts . '/htaccess-wp-permalinks',
        ]);
    }

    public function buildTheme()
    {
        // Write your own task, according to your theme architecture
    }

    public function clean()
    {
        $this->cleanGit();
        $this->cleanFiles();
    }

    public function cleanGit()
    {
        $this->taskGitStack($this->getConfig('GIT_PATH'))
         ->stopOnFail()
         ->exec('fetch --all --prune')
         ->run();
    }

    public function cleanFiles()
    {
        $this->taskCleanWaste(__DIR__)->run();
    }

    /**
     * @todo use http://robo.li/tasks/File/#write ?
     */
    public function wpGenerateSaltKeys()
    {
        if (!file_exists($this->fileConfigSaltKeys)) {
            $response = \Requests::request($this->saltKeysUrl, [], [], 'GET', ['timeout' => 10]);
            if (200 === $response->status_code) {
                $salt_keys = $response->body;
            } else {
                throw new Exception(sprintf('Couldn\'t fetch response from %s (HTTP code %s)', $this->saltKeysUrl, $response->status_code));
            }
            $content = '<?php' . PHP_EOL;
            $content .= PHP_EOL;
            $content .= '// SALT KEYS' . PHP_EOL;
            $content .= '// https://api.wordpress.org/secret-key/1.1/salt/' . PHP_EOL;
            $content .= PHP_EOL;
            $content .= $salt_keys;
            $this->writeFile($this->fileConfigSaltKeys, $content);
        }
    }

    public function wpInit()
    {
        $this->wpInitConfig();
        $this->wpDbCreate();
        $this->wpCoreInstall();
        $this->wpClean();
        $this->wpActivatePlugins();
    }

    private function wpInitConfig($startPlaceholder = '<##', $endPlaceholder = '##>')
    {
        $settings                     = [];
        $settings['DB_PREFIX']        = $this->io()->ask('Database prefix', 'cubi_');
        $settings['WP_DEFAULT_THEME'] = $this->io()->ask('Default theme slug (you can change it later in ./config/application.php)', 'my-theme');
        $this->taskReplacePlaceholders($this->fileApplication)
         ->from(array_keys($settings))
         ->to($settings)
         ->startDelimiter($startPlaceholder)
         ->endDelimiter($endPlaceholder)
         ->run();
    }

    public function wpDbCreate()
    {
        if ($this->checkMysql()) {
            $cmd = new Command($this->fileBinWPCli);
            $cmd->arg('db')
                ->arg('create')
                ->execute();
        } else {
            $this->io()->confirm('Could not find `mysql` binary. Please create database `' . $this->getConfig('DB_NAME') . '` manually then press ENTER');
        }
    }

    public function wpCoreInstall()
    {
        $title    = $this->io()->ask('Site title');
        $username = $this->io()->ask('Admin username');
        $password = $this->io()->askHidden('Admin password');
        $email    = $this->io()->ask('Admin email', $this->getConfig('DEV_MAIL'));
        $url      = $this->getConfig('WEB_SCHEME') . '://' . $this->getConfig('WEB_DOMAIN') . $this->getConfig('WEB_PATH') . '/wp';

        $cmd = new Command($this->fileBinWPCli);
        $cmd->arg('core')
            ->arg('install')
            ->option('title', $title, '=')
            ->option('admin_user', $username, '=')
            ->option('admin_password', $password, '=')
            ->option('admin_email', $email, '=')
            ->option('url', $url, '=')
            ->option('skip-email')
            ->execute();

        echo 'Access new site admin at ' . $url . '/wp-admin' . PHP_EOL;
    }

    private function wpClean()
    {
        $cmd = new Command($this->fileBinWPCli);
        $cmd->arg('option')
            ->arg('update')
            ->arg('blogdescription')
            ->execute();
        $cmd = new Command($this->fileBinWPCli);
        $cmd->arg('post')
            ->arg('delete')
            ->arg('1')
            ->option('force')
            ->execute();
        $cmd = new Command($this->fileBinWPCli);
        $cmd->arg('post')
            ->arg('delete')
            ->arg('2')
            ->option('force')
            ->execute();
    }

    private function wpActivatePlugins()
    {
        $cmd = new Command($this->fileBinWPCli);
        $cmd->arg('plugin')
            ->arg('activate')
            ->option('all')
            ->execute();
        $cmd = new Command($this->fileBinWPCli);
        $cmd->arg('cap')
            ->arg('add')
            ->arg('administrator')
            ->arg('view_query_monitor')
            ->execute();
    }

    /**
     * Start a new feature
     *
     * @param  string $name The feature name
     */
    public function featureStart($name)
    {
        return $this->taskFeatureStart($name, $this->getConfig('GIT_PATH'))->run();
    }

    /**
     * Finish a feature
     *
     * @param  string $name The feature name
     */
    public function featureFinish($name)
    {
        return $this->taskFeatureFinish($name, $this->getConfig('GIT_PATH'))->run();
    }

    /**
     * Start a new hotfix
     *
     * @option string $semversion Version number
     * @option string $type    Hotfix type (path, minor)
     */
    public function hotfixStart($opts = ['semversion' => null, 'type' => 'patch'])
    {
        if (empty($opts['semversion'])) {
            $version = $this->getVersion()
                ->increment($opts['type']);
        } else {
            $version = $opts['semversion'];
        }
        return $this->taskHotfixStart((string)$version, $this->getConfig('GIT_PATH'))->run();
    }

    /**
     * Finish a hotfix
     *
     * @option string $semversion Version number
     * @option string $type    Hotfix type (path, minor)
     */
    public function hotfixFinish($opts = ['semversion' => null, 'type' => 'patch'])
    {
        if (empty($opts['semversion'])) {
            $version = $this->getVersion()
                ->increment($opts['type']);
        } else {
            $version = $opts['semversion'];
        }
        return $this->taskHotfixFinish((string)$version, $this->getConfig('GIT_PATH'))->run();
    }

    /**
     * Start a new release
     *
     * @option string $semversion Version number
     * @option string $type    Relase type (minor, major)
     */
    public function releaseStart($opts = ['semversion' => null, 'type' => 'minor'])
    {
        if (empty($opts['semversion'])) {
            $version = $this->getVersion()
                ->increment($opts['type']);
        } else {
            $version = $opts['semversion'];
        }
        return $this->taskReleaseStart((string)$version, $this->getConfig('GIT_PATH'))->run();
    }

    /**
     * Finish a release
     *
     * @option string $semversion Version number
     * @option string $type    Relase type (minor, major)
     */
    public function releaseFinish($opts = ['semversion' => null, 'type' => 'minor'])
    {
        if (empty($opts['semversion'])) {
            $version = $this->getVersion()
                ->increment($opts['type']);
        } else {
            $version = $opts['semversion'];
        }
        return $this->taskReleaseFinish((string)$version, $this->getConfig('GIT_PATH'))->run();
    }

    private function getProperties($type)
    {
        if (!isset($this->properties)) {
            $this->properties = include $this->fileProperties;
        }
        if (isset($this->properties[$type])) {
            return $this->properties[$type];
        } else {
            return [];
        }
    }

    private function loadConfig()
    {
        static $loaded;
        if ($loaded) {
            return;
        } else {
            $this->config(['only-missing' => true]);
            foreach ($this->configVariables as $key => $value) {
                $this->configVariables[$key . '_PQ'] = preg_quote($value);
            }
            $loaded = true;
        }
    }

    private function getConfig($key = null)
    {
        $this->loadConfig();
        if (isset($key)) {
            return $this->configVariables[$key];
        } else {
            return $this->configVariables;
        }
    }

    private function processHtaccessParts($env, $filePath, $parts, $startPlaceholder = '<##', $endPlaceholder = '##>')
    {
        foreach ($parts as $key => $part) {
            $partEnv = $part . '-' . $env;
            if (file_exists($partEnv)) {
                $parts[$key] = $partEnv;
            }
        }

        $this->taskConcat($parts)
        ->to($filePath)
        ->run();

        $config = $this->getConfig();

        $this->taskReplacePlaceholders($filePath)
         ->from(array_keys($config))
         ->to($config)
         ->startDelimiter($startPlaceholder)
         ->endDelimiter($endPlaceholder)
         ->run();
    }

    /**
     * Return current version
     *
     * @return SemanticVersion
     */
    private function getVersion()
    {
        // Get version from tag
        $cmd = new Command($this->getConfig('GIT_PATH'));
        $cmd = $cmd->arg('tag')
            ->execute();
        $output = explode(PHP_EOL, trim($cmd->getOutput()));
        $currentVersion = '0.0.0';
        foreach ($output as $tag) {
            if (preg_match(SemanticVersion::REGEX, $tag)) {
                if (version_compare($currentVersion, $tag, '<')) {
                    $currentVersion = $tag;
                }
            }
        }
        return new SemanticVersion($currentVersion);
    }

    /**
     * @todo move into parent class AND/OR use http://robo.li/tasks/File/#write ?
     */
    private function writeFile($filePath, $content)
    {
        if (!$this->canWrite($filePath)) {
            throw new TaskException($this, 'Cannot write in file "' . $filePath  . '"');
        } else {
            file_put_contents($filePath, $content);
        }
    }

    /**
     * @todo move into parent class AND/OR use http://robo.li/tasks/File/#write ?
     */
    private function canWrite($filePath)
    {
        return is_writable($filePath) || (!file_exists($filePath) && is_writable(dirname($filePath)) === true);
    }

    private function checkMysql()
    {
        $cmd = new Command('mysql');
        return $cmd->option('--version')
        ->executeWithoutException()
        ->isSuccessful();
    }
}
