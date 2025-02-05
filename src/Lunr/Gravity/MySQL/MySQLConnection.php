<?php

/**
 * MySQL database connection class.
 *
 * SPDX-FileCopyrightText: Copyright 2012 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Gravity\MySQL;

use Lunr\Core\Configuration;
use Lunr\Gravity\DatabaseConnection;
use Lunr\Gravity\Exceptions\ConnectionException;
use Lunr\Gravity\Exceptions\DefragmentationException;
use MySQLi;
use Psr\Log\LoggerInterface;

/**
 * MySQL/MariaDB database access class.
 */
class MySQLConnection extends DatabaseConnection
{

    /**
     * Hostname of the database server (read/write access)
     * @var string|null
     */
    protected ?string $rw_host;

    /**
     * Hostname of the database server (readonly access)
     * @var string|null
     */
    protected ?string $ro_host;

    /**
     * Username of the user used to connect to the database
     * @var string|null
     */
    protected ?string $user;

    /**
     * Password of the user used to connect to the database
     * @var string|null
     */
    protected ?string $pwd;

    /**
     * Database to connect to.
     * @var string|null
     */
    protected ?string $db;

    /**
     * Port to connect to the database server.
     * @var int
     */
    protected int $port;

    /**
     * Path to the UNIX socket for localhost connection
     * @var string
     */
    protected string $socket;

    /**
     * Instance of the MySQLi class
     * @var MySQLi
     */
    protected $mysqli;

    /**
     * SQL hint to send along with the query.
     * @var string
     */
    protected string $query_hint;

    /**
     * The path name to the key file.
     * @var string|null
     */
    protected ?string $ssl_key;

    /**
     * The path name to the certificate file.
     * @var string|null
     */
    protected ?string $ssl_cert;

    /**
     * The path name to the certificate authority file.
     * @var string|null
     */
    protected ?string $ca_cert;

    /**
     * The pathname to a directory that contains trusted SSL CA certificates in PEM format.
     * @var string|null
     */
    protected ?string $ca_path;

    /**
     * A list of allowable ciphers to use for SSL encryption.
     * @var string|null
     */
    protected ?string $cipher;

    /**
     * Mysqli options.
     * @var array
     */
    protected array $options;

    /**
     * Instance of the MySQLQueryEscaper
     * @var MySQLQueryEscaper
     */
    private readonly MySQLQueryEscaper $escaper;

    /**
     * Limit how often we automatically reconnect after failing to set a charset.
     * @var int
     */
    protected const RECONNECT_LIMIT = 4;

    /**
     * Constructor.
     *
     * @param Configuration   $configuration Shared instance of the configuration class
     * @param LoggerInterface $logger        Shared instance of a logger class
     * @param MySQLi          $mysqli        Instance of the mysqli class
     */
    public function __construct(Configuration $configuration, LoggerInterface $logger, MySQLi $mysqli)
    {
        parent::__construct($configuration, $logger);

        $this->mysqli =& $mysqli;

        $this->query_hint                                 = '';
        $this->options[ MYSQLI_OPT_INT_AND_FLOAT_NATIVE ] = TRUE;

        $this->set_configuration();

        mysqli_report($configuration['db']['error_reporting'] ?? MYSQLI_REPORT_ERROR);
    }

    /**
     * Destructor.
     */
    public function __destruct()
    {
        if ($this->connected === TRUE)
        {
            $this->rollback();
            $this->disconnect();
        }

        unset($this->mysqli);
        unset($this->rw_host);
        unset($this->ro_host);
        unset($this->user);
        unset($this->pwd);
        unset($this->db);
        unset($this->port);
        unset($this->socket);
        unset($this->query_hint);
        unset($this->ssl_key);
        unset($this->ssl_cert);
        unset($this->ca_cert);
        unset($this->ca_path);
        unset($this->cipher);

        parent::__destruct();
    }

    /**
     * Set the configuration values.
     *
     * @return void
     */
    private function set_configuration(): void
    {
        $this->rw_host  = $this->configuration['db']['rw_host'];
        $this->user     = $this->configuration['db']['username'];
        $this->pwd      = $this->configuration['db']['password'];
        $this->db       = $this->configuration['db']['database'];
        $this->ssl_key  = $this->configuration['db']['ssl_key'];
        $this->ssl_cert = $this->configuration['db']['ssl_cert'];
        $this->ca_cert  = $this->configuration['db']['ca_cert'];
        $this->ca_path  = $this->configuration['db']['ca_path'];
        $this->cipher   = $this->configuration['db']['cipher'];

        if ($this->configuration['db']['ro_host'] != NULL)
        {
            $this->ro_host = $this->configuration['db']['ro_host'];
        }
        else
        {
            $this->ro_host = $this->rw_host;
        }

        if ($this->configuration['db']['port'] != NULL)
        {
            $this->port = $this->configuration['db']['port'];
        }
        else
        {
            $this->port = (int) (ini_get('mysqli.default_port') ?: 3306 );
        }

        if ($this->configuration['db']['socket'] != NULL)
        {
            $this->socket = $this->configuration['db']['socket'];
        }
        else
        {
            $this->socket = ini_get('mysqli.default_socket');
        }
    }

    /**
     * Establishes a connection to the defined mysql-server.
     *
     * @param int $reconnect_count How often we already tried to connect.
     *
     * @return void
     */
    public function connect(int $reconnect_count = 0): void
    {
        if ($this->connected === TRUE)
        {
            return;
        }

        if ($this->configuration['db']['driver'] != 'mysql')
        {
            throw new ConnectionException('Cannot connect to a non-mysql database connection!');
        }

        if ($reconnect_count > static::RECONNECT_LIMIT)
        {
            throw new ConnectionException('Could not establish connection to the database! Exceeded reconnect count!');
        }

        $host = ($this->readonly === TRUE) ? $this->ro_host : $this->rw_host;

        if (isset($this->ssl_key, $this->ssl_cert, $this->ca_cert))
        {
            $this->mysqli->ssl_set($this->ssl_key, $this->ssl_cert, $this->ca_cert, $this->ca_path, $this->cipher);
        }

        foreach ($this->options as $key => $value)
        {
            $this->mysqli->options($key, $value);
        }

        $this->mysqli->connect($host, $this->user, $this->pwd, $this->db, $this->port, $this->socket);

        if ($this->mysqli->errno === 0)
        {
            $this->connected = TRUE;

            if ($this->mysqli->set_charset('utf8mb4') === FALSE)
            {
                // manual re-connect
                $this->disconnect();
                $this->connect(++$reconnect_count);
            }
        }

        if ($this->connected === FALSE)
        {
            throw new ConnectionException('Could not establish connection to the database!');
        }
    }

    /**
     * Disconnects from mysql-server.
     *
     * @return void
     */
    public function disconnect(): void
    {
        if ($this->connected !== TRUE)
        {
            return;
        }

        $this->mysqli->close();
        $this->connected = FALSE;
    }

    /**
     * Change the default database for the current connection.
     *
     * @param string $db New default database
     *
     * @return bool True on success, False on Failure
     */
    public function change_database(string $db): bool
    {
        $this->connect();

        return $this->mysqli->select_db($db);
    }

    /**
     * Get the name of the database we're currently connected to.
     *
     * @return string Database name
     */
    public function get_database(): string
    {
        return $this->db;
    }

    /**
     * Set option for the current connection.
     *
     * @param int   $key   Mysqli option key.
     * @param mixed $value Mysqli option value.
     *
     * @return bool True on success, False on Failure
     */
    public function set_option(int $key, mixed $value): bool
    {
        if (is_null($value) === TRUE)
        {
            return FALSE;
        }

        $this->options[$key] = $value;

        return TRUE;
    }

    /**
     * Return a new instance of a QueryBuilder object.
     *
     * @param bool $simple Whether to return a simple query builder or an advanced one.
     *
     * @return object New DatabaseDMLQueryBuilder object instance
     */
    public function get_new_dml_query_builder_object(bool $simple = TRUE): object
    {
        $querybuilder = new MySQLDMLQueryBuilder();
        if ($simple === TRUE)
        {
            return new MySQLSimpleDMLQueryBuilder($querybuilder, $this->get_query_escaper_object());
        }
        else
        {
            return $querybuilder;
        }
    }

    /**
     * Return a new instance of a QueryEscaper object.
     *
     * @return MySQLQueryEscaper New MySQLQueryEscaper object instance
     */
    public function get_query_escaper_object(): MySQLQueryEscaper
    {
        if (isset($this->escaper) === FALSE)
        {
            $this->escaper = new MySQLQueryEscaper($this);
        }

        return $this->escaper;
    }

    /**
     * Escape a string to be used in a SQL query.
     *
     * @param string $string The string to escape
     *
     * @return string The escaped string
     */
    public function escape_string(string $string): string
    {
        $this->connect();

        return $this->mysqli->escape_string($string);
    }

    /**
     * When running the query on a replication setup, hint to run the next query on the master server.
     *
     * @param string $style What hint style to use.
     *
     * @return static $self Self reference
     */
    public function run_on_master($style = 'maxscale'): static
    {
        switch ($style)
        {
            case 'maxscale':
                $this->query_hint = '/* maxscale route to master */';
                break;
            default:
                break;
        }

        return $this;
    }

    /**
     * When running the query on a replication setup, hint to run the next query on the slave server.
     *
     * @param string $style What hint style to use.
     *
     * @return static $self Self reference
     */
    public function run_on_slave(string $style = 'maxscale'): static
    {
        switch ($style)
        {
            case 'maxscale':
                $this->query_hint = '/* maxscale route to slave */';
                break;
            default:
                break;
        }

        return $this;
    }

    /**
     * Run a SQL query.
     *
     * @param string $sql_query The SQL query to run on the database
     *
     * @return MySQLQueryResult $result Query Result
     */
    public function query($sql_query): MySQLQueryResult
    {
        $this->connect();

        $sql_query        = $this->query_hint . $sql_query;
        $this->query_hint = '';

        $this->logger->debug('query: {query}', [ 'query' => $sql_query ]);

        $query_start = microtime(TRUE);
        $result      = $this->mysqli->query($sql_query);
        $query_end   = microtime(TRUE);

        $this->logger->debug('Query executed in ' . ($query_end - $query_start) . ' seconds');

        return new MySQLQueryResult($sql_query, $result, $this->mysqli);
    }

    /**
     * Run an asynchronous SQL query.
     *
     * @param string $sql_query The SQL query to run on the database
     *
     * @return MySQLAsyncQueryResult $result Query Result
     */
    public function async_query(string $sql_query): MySQLAsyncQueryResult
    {
        $this->connect();

        $sql_query        = $this->query_hint . $sql_query;
        $this->query_hint = '';

        $this->logger->debug('query: {query}', [ 'query' => $sql_query ]);

        $this->mysqli->query($sql_query, MYSQLI_ASYNC);

        return new MySQLAsyncQueryResult($sql_query, $this->mysqli);
    }

    /**
     * Begins a transaction.
     *
     * @return bool
     */
    public function begin_transaction(): bool
    {
        $this->connect();

        return $this->mysqli->autocommit(FALSE);
    }

    /**
     * Commits a transaction.
     *
     * @return bool
     */
    public function commit(): bool
    {
        $this->connect();

        return $this->mysqli->commit();
    }

    /**
     * Rolls back a transaction.
     *
     * @return bool
     */
    public function rollback(): bool
    {
        $this->connect();

        return $this->mysqli->rollback();
    }

    /**
     * Ends a transaction.
     *
     * @return bool
     */
    public function end_transaction(): bool
    {
        $this->connect();

        return $this->mysqli->autocommit(TRUE);
    }

    /**
     * Run OPTIMIZE TABLE on a table.
     *
     * @param string $table The table name to defragment.
     *
     * @return void
     */
    public function defragment(string $table): void
    {
        $escaper = $this->get_query_escaper_object();

        $query = $this->query('OPTIMIZE TABLE ' . $escaper->table($table));

        if ($query->has_failed() === TRUE)
        {
            $context = [ 'query' => $query->query(), 'error' => $query->error_message() ];
            $this->logger->error('{query}; failed with error: {error}', $context);

            throw new DefragmentationException($query, "Failed to optimize table: $table");
        }
    }

}

?>
