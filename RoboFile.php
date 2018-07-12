<?php

use Globalis\Robo\Core\Command;
use Globalis\Robo\Core\SemanticVersion;

class RoboFile extends \Globalis\Robo\Tasks
{
    const DEFAULT_WP_LANG          = 'en_US';

    private $fileProperties        = __DIR__ . '/.robo/properties.php';
    private $filePropertiesRemote  = __DIR__ . '/.robo/properties.remote.php';
    private $fileVars              = __DIR__ . '/config/vars.php';
    private $fileVarsRemote        = __DIR__ . '/config/vars.%s.php';
    private $fileApplication       = __DIR__ . '/config/application.php';
    private $fileConfigLocal       = __DIR__ . '/config/local.php';
    private $fileConfigLocalSample = __DIR__ . '/config/local.sample.php';
    private $fileConfigSaltKeys    = __DIR__ . '/config/salt-keys.php';
    private $dirHtaccessParts      = __DIR__ . '/config/htaccess/';
    private $fileHtaccess          = __DIR__ . '/web/.htaccess';
    private $fileBinWPCli          = __DIR__ . '/vendor/bin/wp';
    private $pathMedia             = '/web/media';
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

    private function configRemote($opts = ['only-missing' => false])
    {
        $force = true !== $opts['only-missing'];
        $this->configVariablesRemote = $this->taskConfiguration()
            ->initConfig($this->getPropertiesRemote())
            ->configFilePath($this->fileVarsRemote)
            ->force($force)
            ->run()
            ->getData();
    }

    public function install()
    {
        if (!file_exists($this->fileVars)) {
            $this->io()->section('To get started, answer a few questions to setup project variables');
            $this->io()->text(sprintf('Global variables will be saved at %s', '~/.robo_config'));
            $this->io()->newLine();
            $this->io()->text(sprintf('Project variables will be saved at %s', $this->fileVars));
            $this->io()->newLine();
            $this->io()->text('You can change those variables at any time by editing those files, or by running `./vendor/bin/robo config`');
        }
        $this->loadConfig();
        $this->installPackages();
        $this->createConfigLocal();
        $this->wpGenerateSaltKeys();
        $this->build();
        $this->gitInit();
    }

    private function gitInit()
    {
        if (!is_dir(__DIR__ . '/.git/') && $this->io()->confirm('Initialize a git repository ?', true)) {

            $this->taskGitStack($this->getConfig('GIT_PATH'))
             ->stopOnFail()
             ->exec('init')
             ->run();

            $commitMessage = $this->io()->ask('Initial commit message', 'Initial commit');

            $this->taskGitStack()
             ->stopOnFail()
             ->add('-A')
             ->commit($commitMessage)
             ->run();
        }
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

    public function installPackagesRemote($dir)
    {
        $this->taskComposerInstall()
            ->workingDir($dir)
            ->optimizeAutoloader()
            ->noDev()
            ->preferDist()
            ->run();
    }

    public function build()
    {
        $this->buildHtaccess();
    }

    private function buildRemote($dir)
    {
        $fileVarsRemote = $this->remoteWorkPath($dir, $this->fileVarsRemote);
        $fileVarsRemote = str_replace('.' . $this->envRemote, '', $fileVarsRemote);
        copy($this->fileVarsRemote, $fileVarsRemote);
        $this->installPackagesRemote($dir);
        $this->buildHtaccess($this->envRemote, $this->remoteWorkPath($dir, $this->fileHtaccess));
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
        $url = $this->getConfig('WEB_SCHEME') . '://' . $this->getConfig('WEB_DOMAIN') . $this->getConfig('WEB_PATH') . '/wp';

        $this->wpInitConfig();
        $this->wpDbCreate();
        $this->wpCoreInstall($url);
        $this->wpUpdateLanguages();
        $this->wpUpdateTimezone();
        $this->wpClean();
        $this->wpActivatePlugins();

        echo 'Access new site admin at ' . $url . '/wp-admin' . PHP_EOL;
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

    public function wpCoreInstall($url)
    {
        $title    = $this->io()->ask('Site title');
        $username = $this->io()->ask('Admin username');
        $password = $this->io()->askHidden('Admin password');
        $email    = $this->io()->ask('Admin email', $this->getConfig('DEV_MAIL'));

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
    }

    public function wpUpdateLanguages()
    {
        $lang = $this->io()->ask('WordPress language', self::DEFAULT_WP_LANG);

        if (self::DEFAULT_WP_LANG !== $lang) {
            $cmd = new Command($this->fileBinWPCli);
            $cmd->arg('language')
                ->arg('core')
                ->arg('install')
                ->arg($lang)
                ->execute();

            $cmd = new Command($this->fileBinWPCli);
            $cmd->arg('language')
                ->arg('core')
                ->arg('activate')
                ->arg($lang)
                ->execute();

            $cmd = new Command($this->fileBinWPCli);
            $cmd->arg('language')
                ->arg('core')
                ->arg('update')
                ->execute();
        }
    }

    public function wpUpdateTimezone()
    {
        $timezones = self::getTimeZones();

        $group     = $this->io()->choice('Wordpress Timezone (1/2)', array_keys($timezones));

        $timezone  = $this->io()->choice('Wordpress Timezone (2/2)', array_keys($timezones[$group]));

        $value     = $timezones[$group][$timezone];

        $cmd = new Command($this->fileBinWPCli);
        $cmd->arg('option')
            ->arg('update')
            ->arg('timezone_string')
            ->arg($value)
            ->execute();
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

    /**
     * Deploy Application
     *
     * @param  string $env         Target environment
     * @param  string $gitRevision The git revision to deploy
     */
    public function deploy($env, $gitRevision)
    {
        $this->io()->title('Deploy version ' . $gitRevision . ' to ' . $env);
        $this->io()->text('You must answer a few questions about the remote environment:');

        $this->envRemote = $env;
        $this->loadConfigRemote(false);

        $config                = $this->getConfigRemote();
        $config['REMOTE_PATH'] = self::trailingslashit($config['REMOTE_PATH']);

        $collection = $this->collectionBuilder();
        $workDir    = self::trailingslashit($collection->tmpDir());

        // Create archive files
        $cmd = new Command($this->getConfig('GIT_PATH'));
        $cmd = $cmd->arg('archive')
            ->option('--format=tar')
            ->option('--prefix=' . basename($workDir) . '/')
            ->arg($gitRevision)
            ->pipe('(cd')
            ->arg(dirname($workDir))
            ->getCommand();

        $cmd .= ' && tar xf -)';

        $this->taskExec($cmd)
            ->run();

        $this->buildRemote($workDir);

        $this->deployWriteState($workDir . 'deploy', $gitRevision);

        // 1. Dry Run
        $this->rsync($workDir, $config['REMOTE_USERNAME'], $config['REMOTE_HOSTNAME'], $config['REMOTE_PORT'], $config['REMOTE_PATH'], true);

        if ($this->io()->confirm('Do you want to run ?', false)) {
            // 2. Run
            $this->rsync($workDir, $config['REMOTE_USERNAME'], $config['REMOTE_HOSTNAME'], $config['REMOTE_PORT'], $config['REMOTE_PATH'], false);
        }

        $this->taskDeleteDir($workDir)->run();
    }

    private function deployWriteState($directory, $gitRevision)
    {
        $gitCommit = $this->gitCommit($gitRevision);

        switch ($this->gitRevisionType($gitRevision)) {
            case 'branch':
                if (false !== strpos($gitRevision, 'release_')) {
                    $gitTag = str_replace('release_', '', $gitRevision);
                } elseif (false !== strpos($gitRevision, 'hotfix_')) {
                    $gitTag = str_replace('hotfix_', '', $gitRevision);
                } else {
                    $gitTag = false;
                }
                break;

            case 'tag':
                $gitTag = $gitRevision;
                break;

            default:
                $gitTag = false;
                break;
        }

        $this->taskWriteToFile($directory . '/git_commit')
             ->line($gitCommit)
             ->run();

        if (false !== $gitTag) {
            $this->taskWriteToFile($directory . '/git_tag')
                 ->line($gitTag)
                 ->run();
        }

        $this->taskWriteToFile($directory . '/time')
             ->line(date('Y-m-d H:i:s'))
             ->run();
    }

    private function gitCommit($gitRevision)
    {
        $cmd = new Command($this->getConfig('GIT_PATH'));
        $cmd = $cmd->arg('rev-parse')
            ->option('--short')
            ->arg($gitRevision);

        $process = $cmd->executeWithoutException();
        return rtrim($process->getOutput());
    }

    private function gitRevisionType($gitRevision)
    {
        $types = [
            'refs/heads/' => 'branch',
            'refs/tags/'  => 'tag'
        ];

        foreach ($types as $ref => $type) {
            $cmd = new Command($this->getConfig('GIT_PATH'));
            $cmd = $cmd->arg('show-ref')
                ->option('--verify')
                ->option('--quiet')
                ->arg($ref . $gitRevision);

            $process = $cmd->executeWithoutException();

            if ($process->isSuccessful()) {
                return $type;
            }
        }

        return 'commit';
    }

    private function rsync($workDir, $remoteUser, $remoteHost, $remotePort, $remotePath, $dryRun = false)
    {
        $cmd = $this->taskRsync()
            ->fromPath($workDir)
            ->toHost($remoteHost)
            ->toUser($remoteUser)
            ->toPath($remotePath)
            ->option('rsh', 'ssh -p ' . $remotePort)
            ->verbose()
            ->recursive()
            ->delete()
            ->checksum()
            ->compress()
            ->itemizeChanges()
            ->excludeVcs()
            ->progress()
            ->option('copy-links')
            ->option('perms')
            ->option('chmod', 'Du=rwx,Dgo=rx,Fu=rw,Fgo=r')
            ->stats();

        if (file_exists($workDir . '.rsyncignore')) {
            $cmd->excludeFrom($workDir . '.rsyncignore');
        }

        if (true === $dryRun) {
            $cmd->dryRun();
        }
        return $cmd->run();
    }

    public function mediaDump($from_env, $opts = ['delete' => false])
    {
        $this->envRemote = $from_env;
        $config          = $this->getConfigRemote();
        $localPath       = self::trailingslashit(__DIR__ . $this->pathMedia);
        $remotePath      = self::trailingslashit(self::untrailingslashit($config['REMOTE_PATH']) . $this->pathMedia);
        $delete          = true === $opts['delete'];

        if (!is_dir($localPath)) {
            mkdir($localPath, 0777);
        }

        // 1. Dry Run
        $this->rsyncMedia($config['REMOTE_HOSTNAME'], $config['REMOTE_USERNAME'], $remotePath, null, null, $localPath, $delete, true);

        if ($this->io()->confirm('Do you want to run ?', false)) {
            // 2. Run
            $this->rsyncMedia($config['REMOTE_HOSTNAME'], $config['REMOTE_USERNAME'], $remotePath, null, null, $localPath, $delete, false);
        }
    }

    public function mediaPush($to_env, $opts = ['delete' => false])
    {
        $this->envRemote = $to_env;
        $config          = $this->getConfigRemote();
        $localPath       = self::trailingslashit(__DIR__ . $this->pathMedia);
        $remotePath      = self::trailingslashit(self::untrailingslashit($config['REMOTE_PATH']) . $this->pathMedia);
        $delete          = true === $opts['delete'];

        // 1. Dry Run
        $this->rsyncMedia(null, null, $localPath, $config['REMOTE_HOSTNAME'], $config['REMOTE_USERNAME'], $remotePath, $delete, true);

        if ($this->io()->confirm('Do you want to run ?', false)) {
            // 2. Run
            $this->rsyncMedia(null, null, $localPath, $config['REMOTE_HOSTNAME'], $config['REMOTE_USERNAME'], $remotePath, $delete, false);
        }
    }

    private function rsyncMedia($fromHost, $fromUser, $fromPath, $toHost, $toUser, $toPath, $remotePort = 22, $delete = false, $dryRun = false)
    {
        $cmd = $this->taskRsync()
            ->fromHost($fromHost)
            ->fromUser($fromUser)
            ->fromPath($fromPath)
            ->toHost($toHost)
            ->toUser($toUser)
            ->toPath($toPath)
            ->option('rsh', 'ssh -p ' . $remotePort)
            ->verbose()
            ->recursive()
            ->checksum()
            ->compress()
            ->itemizeChanges()
            ->progress()
            ->exclude('.gitkeep')
            ->option('perms')
            ->option('chmod', 'Dugo=rwx,Fugo=rwx')
            ->stats();

        if (true === $delete) {
            $cmd->delete();
        }

        if (true === $dryRun) {
            $cmd->dryRun();
        }

        return $cmd->run();
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

    private function getPropertiesRemote()
    {
        if (!isset($this->propertiesRemote)) {
            $this->propertiesRemote = include $this->filePropertiesRemote;
            $this->propertiesRemote = array_merge($this->getProperties('config'), $this->propertiesRemote);
        }
        return $this->propertiesRemote;
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

    private function loadConfigRemote($only_missing = false)
    {
        static $loaded;
        if ($loaded) {
            return;
        } else {
            $this->fileVarsRemote = sprintf($this->fileVarsRemote, $this->envRemote);
            $this->configRemote(['only-missing' => $only_missing]);
            foreach ($this->configVariablesRemote as $key => $value) {
                $this->configVariablesRemote[$key . '_PQ'] = preg_quote($value);
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

    private function getConfigRemote($key = null)
    {
        $this->loadConfigRemote(true);
        if (isset($key)) {
            return $this->configVariablesRemote[$key];
        } else {
            return $this->configVariablesRemote;
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

        if (isset($this->configVariablesRemote) && !empty($this->configVariablesRemote)) {
            $config = $this->getConfigRemote();
        } else {
            $config = $this->getConfig();
        }

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

    private static function getTimeZones()
    {
        $groups = [];

        foreach (timezone_identifiers_list() as $timezone) {
            $parts   = explode('/', $timezone);
            $group   = $parts[0];
            $zone    = isset($parts[1]) ? $parts[1] : $parts[0];

            if (!isset($groups[$group])) {
                $groups[$group] = [];
            }

            $groups[$group][$zone] = $timezone;
        }

        return $groups;
    }

    private static function trailingslashit($string)
    {
        return self::untrailingslashit($string) . '/';
    }

    private static function untrailingslashit($string)
    {
        return rtrim($string, '/\\');
    }

    private function remoteWorkPath($dir, $path)
    {
        return str_replace(self::trailingslashit(__DIR__), $dir, $path);
    }
}
