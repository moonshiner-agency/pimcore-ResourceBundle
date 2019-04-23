<?php

namespace Moonshiner\ResourceBundle\Tests\Concerns;

use Doctrine\DBAL\DriverManager;

trait InteractsWithDatabase
{
    public static $pimcoreSetupDone = false;
    public static $classesBuilded = false;

    public static function setupPimcore()
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
            $schemaManager->dropDatabase('test');
        }

        $schemaManager->createDatabase('test');

        if (!$connection->isConnected()) {
            $connection->connect();
        }

        $installer = new \Pimcore\Bundle\InstallBundle\Installer(
            \Pimcore::getContainer()->get('monolog.logger.pimcore'),
            \Pimcore::getContainer()->get('event_dispatcher')
        );

        $installer->setupDatabase([
            'username' => 'admin',
            'password' => microtime(),
        ]);

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
        if ( ! static::$classesBuilded) {
            $this->console('pimcore:deployment:classes-rebuild');
           static::$classesBuilded = true;
        }
        return $this;
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
