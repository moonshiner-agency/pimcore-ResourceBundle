<?php

namespace Moonshiner\BrigthenBundle\Concerns;

use Doctrine\DBAL\DriverManager;

trait InteractsWithDatabase
{
    public static $pimcoreSetupDone = false;
    public static $classesBuilded = false;

    public static function setupPimcore( $db = 'test')
    {
        if (static::$pimcoreSetupDone) {
            return;
        }

        $connection = \Pimcore::getContainer()->get('database_connection');
        $dbName = $connection->getParams()['dbname'];
        $params = $connection->getParams();
        $config = $connection->getConfiguration();

        unset($params['url']);
        unset($params['dbname']);

        // use a dedicated setup connection as the framework connection is bound to the DB and will
        // fail if the DB doesn't exist
        $setupConnection = DriverManager::getConnection($params, $config);
        $schemaManager = $setupConnection->getSchemaManager();
        $databases = $schemaManager->listDatabases();
        if (in_array($dbName, $databases)) {

            // you can use this to get the name of the database $connection->quoteIdentifier($dbName)
            // But i preffer to hardcore the name of the database here in order to avoid purging the dev or prod database
            $schemaManager->dropDatabase($db);
        }

        $schemaManager->createDatabase($db);

        if (!$connection->isConnected()) {
            $connection->connect();
        }

        $installer = new \Pimcore\Bundle\InstallBundle\Installer(
            \Pimcore::getContainer()->get('monolog.logger.pimcore'),
            \Pimcore::getContainer()->get('event_dispatcher')
        );

        $installer->setupDatabase([
            'username' => 'admin',
            'password' => 'secret',
        ]);

        self::insertDatabaseContents();

        static::$classesBuilded = false;
        static::$pimcoreSetupDone = true;
    }

    public static function refresh()
    {
        $connection = \Pimcore::getContainer()->get('database_connection');
        $schemaManager = $connection->getSchemaManager();
        $tables = $schemaManager->listTables();
        $query = '';
        foreach($tables as $table) {
            $name = $table->getName();
            $query .= 'TRUNCATE ' . $name . ';';
        }
        $connection->executeQuery('SET FOREIGN_KEY_CHECKS = 0;', array(), array());
        $connection->executeQuery($query, array(), array());
        static::$classesBuilded = false;
    }

     /**
     * Build the class definitions
     *
     * @return $this
     */
    public function classesRebuild()
    {
        $this->console('pimcore:deployment:classes-rebuild', ['-c' =>true ]);
        $this->console('pimcore:deployment:classes-rebuild');

        return $this;
    }

    public static function insertDatabaseContents()
    {
        try {
            $db = \Pimcore\Db::get();
            $db->insert('assets', [
                'id' => 1,
                'parentId' => 0,
                'type' => 'folder',
                'filename' => '',
                'path' => '/',
                'creationDate' => time(),
                'modificationDate' => time(),
                'userOwner' => 1,
                'userModification' => 1
            ]);
            $db->insert('documents', [
                'id' => 1,
                'parentId' => 0,
                'type' => 'page',
                'key' => '',
                'path' => '/',
                'index' => 999999,
                'published' => 1,
                'creationDate' => time(),
                'modificationDate' => time(),
                'userOwner' => 1,
                'userModification' => 1
            ]);
            $db->insert('documents_page', [
                'id' => 1,
                'controller' => 'default',
                'action' => 'default',
                'template' => '',
                'title' => '',
                'description' => ''
            ]);
            $db->insert('objects', [
                'o_id' => 1,
                'o_parentId' => 0,
                'o_type' => 'folder',
                'o_key' => '',
                'o_path' => '/',
                'o_index' => 999999,
                'o_published' => 1,
                'o_creationDate' => time(),
                'o_modificationDate' => time(),
                'o_userOwner' => 1,
                'o_userModification' => 1
            ]);

            $db->insert('users', [
                'parentId' => 0,
                'name' => 'system',
                'admin' => 1,
                'active' => 1
            ]);
            $db->update('users', ['id' => 0], ['name' => 'system']);

            $userPermissions = [
                ['key' => 'application_logging'],
                ['key' => 'assets'],
                ['key' => 'classes'],
                ['key' => 'clear_cache'],
                ['key' => 'clear_fullpage_cache'],
                ['key' => 'clear_temp_files'],
                ['key' => 'dashboards'],
                ['key' => 'document_types'],
                ['key' => 'documents'],
                ['key' => 'emails'],
                ['key' => 'gdpr_data_extractor'],
                ['key' => 'glossary'],
                ['key' => 'http_errors'],
                ['key' => 'notes_events'],
                ['key' => 'objects'],
                ['key' => 'piwik_settings'],
                ['key' => 'piwik_reports'],
                ['key' => 'plugins'],
                ['key' => 'predefined_properties'],
                ['key' => 'asset_metadata'],
                ['key' => 'qr_codes'],
                ['key' => 'recyclebin'],
                ['key' => 'redirects'],
                ['key' => 'reports'],
                ['key' => 'reports_config'],
                ['key' => 'robots.txt'],
                ['key' => 'routes'],
                ['key' => 'seemode'],
                ['key' => 'seo_document_editor'],
                ['key' => 'share_configurations'],
                ['key' => 'system_settings'],
                ['key' => 'tag_snippet_management'],
                ['key' => 'tags_configuration'],
                ['key' => 'tags_assignment'],
                ['key' => 'tags_search'],
                ['key' => 'targeting'],
                ['key' => 'thumbnails'],
                ['key' => 'translations'],
                ['key' => 'users'],
                ['key' => 'website_settings'],
                ['key' => 'admin_translations'],
                ['key' => 'web2print_settings'],
                ['key' => 'workflow_details'],
                ['key' => 'notifications'],
                ['key' => 'notifications_send']
            ];

            foreach ($userPermissions as $up) {
                $db->insert('users_permission_definitions', $up);
            }
        } catch (\Throwable $th) {  }
    }











    // WILL BE NICE TO HAVE

    /**
     * Assert that a given where condition exists in the database.
     *
     * @param  string  $table
     * @param  array  $data
     * @param  string  $connection
     * @return $this
     */
    protected function assertDatabaseHas($table, array $data, $connection = null)
    {
        // $this->assertThat(
        //     $table, new HasInDatabase($this->getConnection($connection), $data)
        // );

        // return $this;
    }

    /**
     * Assert that a given where condition does not exist in the database.
     *
     * @param  string  $table
     * @param  array  $data
     * @param  string  $connection
     * @return $this
     */
    protected function assertDatabaseMissing($table, array $data, $connection = null)
    {
        // $constraint = new ReverseConstraint(
        //     new HasInDatabase($this->getConnection($connection), $data)
        // );

        // $this->assertThat($table, $constraint);

        // return $this;
    }



    /**
     * Get the database connection.
     *
     * @param  string|null  $connection
     * @return \Illuminate\Database\Connection
     */
    protected function getConnection($connection = null)
    {
        // $connection = \Pimcore::getContainer()->get('database_connection');
        // $database = $this->app->make('db');

        // $connection = $connection ?: $database->getDefaultConnection();

        // return $database->connection($connection);
    }

    /**
     * Seed a given database connection.
     *
     * @param  string  $class
     * @return $this
     */
    public function seed($class = 'DatabaseSeeder')
    {
        // $this->artisan('db:seed', ['--class' => $class]);

        // return $this;
    }
}
