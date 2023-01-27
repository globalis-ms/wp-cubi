<?php

namespace Globalis\WP\Cubi;

class AdminBar
{
    const CSS_PATH        = 'assets/wp-cubi-admin-bar.20180711161137.min.css';

    const NODE            = 'website-env';

    const FILE_GIT_COMMIT  = ROOT_DIR . '/deploy/git_commit';
    const FILE_GIT_TAG     = ROOT_DIR . '/deploy/git_tag';
    const FILE_DEPLOY_TIME = ROOT_DIR . '/deploy/time';

    private $git_commit;
    private $git_branch;
    private $git_tag;

    private $wp_admin_bar;

    public function __construct()
    {
        add_action('admin_enqueue_scripts', [$this, 'enqueueStyle'], 10);
        add_action('wp_enqueue_scripts', [$this, 'enqueueStyle'], 10);
        add_action('admin_bar_menu', [$this, 'addInfoBox'], 10, 1);
    }

    public function enqueueStyle()
    {
        if (is_user_logged_in() && is_admin_bar_showing()) {
            wp_enqueue_style('wp-cubi/admin-bar', plugins_url(self::CSS_PATH, dirname(__FILE__)), [], null);
        }
    }

    public function addInfoBox($wp_admin_bar)
    {
        $this->wp_admin_bar = $wp_admin_bar;

        $this->addMenu(self::NODE, '[' . $this->getGitVersion() . '] ' . self::envShortname(WP_ENV), 'wp-cubi-admin-environment wp-cubi-admin-environment-' . WP_ENV, admin_url('/'));

        if ($this->checkUserCap()) {
            $this->addNode('website-env-box-server', 'Server', $this->getDataServer());
            $this->addNode('website-env-box-db', 'Database', $this->getDataDatabase());
            $this->addNode('website-env-box-git', 'Git', $this->getDataGit());

            if ('development' !== WP_ENV && file_exists(self::FILE_DEPLOY_TIME)) {
                $this->addNode('website-env-box-deploy', 'Deploy', $this->getDataDeploy());
            }

            $this->addNode('website-env-box-wp', 'WordPress', $this->getDataWordpress());
            $this->addNode('website-env-box-php', 'PHP', $this->getDataPhp());
            $this->addNode('website-env-box-mysql', 'MYSQL', $this->getDataMySql());
            $this->addNode('website-env-box-seo', 'SEO', $this->getDataSeo());

            if ($public_urls = self::getPublicUrls()) {
                $this->addNode('website-env-box-hr', '', '&nbsp;');
                $this->addNode('website-env-box-switch', '', '<span class="wp-cubi-admin-box">Switch to environment</span>');

                foreach ($public_urls as $env => $url) {
                    $this->addNode('website-env-box-switch-env-' . strtolower($env), '', '<a href="' . $url . '"><span class="wp-cubi-admin-box">' . ucwords($env) . '</span></a>', 'website-env-box-switch');
                }
            }
        }

        unset($this->wp_admin_bar);
    }

    protected function addMenu($id, $title, $class, $url, $parent = false)
    {
        $this->wp_admin_bar->add_menu([
            'parent' => $parent,
            'id'     => $id,
            'title'  => $title,
            'meta'   => ['class' => $class],
            'href'   => admin_url('/'),
        ]);
    }

    protected function addNode($id, $title, $data, $parent = self::NODE)
    {
        $this->wp_admin_bar->add_node([
            'parent' => $parent,
            'id'     => $id,
            'title'  => self::formatTitle($title) . $data,
        ]);
    }

    protected function checkUserCap()
    {
        return apply_filters('wp-cubi/admin-bar-full', current_user_can('manage_options'));
    }

    protected static function formatTitle($title)
    {
        return empty($title) ? '' : '<span class="wp-cubi-admin-box">' . $title . '</span>';
    }

    protected static function formatCode($string)
    {
        return '<span class="code">' . $string . '</span>';
    }

    protected function getDataServer()
    {
        if (isset($_SERVER['SERVER_ADDR'])) {
            $server_ip = $_SERVER['SERVER_ADDR'];
        } else {
            $server_ip = '127.0.0.1';
        }

        if ('127.0.0.1' === $server_ip) {
            $server_name = 'localhost';
        } else {
            $server_name = gethostbyaddr($server_ip);
        }

        return $server_ip === $server_name ? self::formatCode($server_ip) : self::formatCode($server_name) . ' (' . self::formatCode($server_ip) . ')';
    }

    protected function getDataDatabase()
    {
        return self::formatCode(DB_NAME) . ' on ' . self::formatCode(DB_HOST);
    }

    protected function getDataGit()
    {
        $gitCommit = $this->getGitCommit();

        if ('development' === WP_ENV) {
            return 'commit ' . self::formatCode($gitCommit) . ' on branch ' . self::formatCode($this->getGitBranch());
        } elseif ($git_tag = $this->getGitTag()) {
            return 'commit ' . self::formatCode($gitCommit) . ' on version ' . self::formatCode($git_tag);
        } else {
            return 'commit ' . self::formatCode($gitCommit);
        }
    }

    protected function getDataDeploy($format = 'Y-m-d H:i:s')
    {
        $date     = substr(file_get_contents(self::FILE_DEPLOY_TIME), 0, strlen(date($format)));
        $datetime = \DateTime::createFromFormat($format, $date, new \DateTimeZone('UTC'));
        $datetime->setTimeZone(new \DateTimeZone(get_option('timezone_string')));
        return self::formatCode($datetime->format($format));
    }

    protected function getDataWordpress()
    {
        return 'version ' . self::formatCode(get_bloginfo('version', 'display'));
    }

    protected function getDataPhp()
    {
        return 'version ' . self::formatCode(phpversion());
    }

    protected function getDataMySql()
    {
        global $wpdb;
        return 'version ' . self::formatCode($wpdb->db_version());
    }

    protected function getDataSeo()
    {
        return self::formatCode(WP_CUBI_SITE_PUBLIC ? 'index' : 'noindex');
    }

    protected static function envShortname($env)
    {
        $envs = [
            'development' => 'dev.',
            'recette'     => 'recette',
            'staging'     => 'staging',
            'production'  => 'prod.',
        ];
        if (isset($envs[$env])) {
            return $envs[$env];
        } else {
            return strtolower(substr($env, 0, 7)) . '.';
        }
    }

    protected static function getPublicUrls()
    {
        if (!defined('WP_CUBI_PUBLIC_URLS') || !is_array(WP_CUBI_PUBLIC_URLS) || empty(WP_CUBI_PUBLIC_URLS)) {
            return false;
        }

        $urls        = [];
        $envs        = WP_CUBI_PUBLIC_URLS;
        $current_url = \Globalis\WP\Cubi\get_current_url();

        if (isset($envs[WP_ENV])) {
            unset($envs[WP_ENV]);
        }

        if (empty($envs)) {
            return false;
        }

        foreach ($envs as $env_name => $env_url) {
            $urls[$env_name] = str_replace(trailingslashit(WP_HOME), trailingslashit($env_url), $current_url);
        }

        return $urls;
    }

    protected function getGitVersion()
    {
        if ($git_tag = $this->getGitTag()) {
            return $git_tag;
        } else {
            return $this->getGitCommit();
        }
    }

    protected function getGitCommit($default = 'unknown')
    {
        if (!isset($this->git_commit)) {
            $this->git_commit = false;

            if ('development' === WP_ENV) {
                $this->git_commit = exec('git rev-parse --short HEAD');
            } elseif (file_exists(self::FILE_GIT_COMMIT)) {
                $this->git_commit = file_get_contents(self::FILE_GIT_COMMIT);
            }
        }

        return empty($this->git_commit) ? $default : $this->git_commit;
    }

    protected function getGitBranch($default = 'unknown')
    {
        if (!isset($this->git_branch)) {
            $this->git_branch = false;

            if ('development' === WP_ENV) {
                $this->git_branch = exec('git rev-parse --abbrev-ref HEAD');
            }
        }

        return empty($this->git_branch) ? $default : $this->git_branch;
    }

    protected function getGitTag()
    {
        if (!isset($this->git_tag)) {
            $this->git_tag = false;

            if ('development' !== WP_ENV && file_exists(self::FILE_GIT_TAG)) {
                $this->git_tag = file_get_contents(self::FILE_GIT_TAG);
            }
        }

        return $this->git_tag;
    }
}
