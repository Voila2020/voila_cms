<?php

namespace crocodicstudio\crudbooster\controllers;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class BackupRestoreDatabaseController extends \crocodicstudio\crudbooster\controllers\CBController
{
    public function getIndex()
    {
        $admin_path = config('crudbooster.ADMIN_PATH');
        return view('crudbooster::backup_restore_db', compact('admin_path'));
    }

    public function getMakeBackup()
    {
        try {
            $host = config('database.connections.mysql.host');
            $database = config('database.connections.mysql.database');
            $username = config('database.connections.mysql.username');
            $password = config('database.connections.mysql.password');
            $tables = "*";
            #Connect to the database
            $connection = mysqli_connect($host, $username, $password, $database);

            /********************* Tables *********************/
            if ($tables == '*') {
                # assign empty array into $tables
                $tables = array();
                # query to get all tables in database
                $result = mysqli_query($connection, 'SHOW TABLE STATUS');
                # fetch all records of qieru
                while ($row = mysqli_fetch_row($result)) {
                    # assign type of row after uppercase
                    $type = strtoupper($row[17]);
                    if ($type == "VIEW") {
                        # append into tables array View that's key "table name"
                        $tables[$row[0]] = "View";
                    } else {
                        #append into tables array Table that's key "table name"
                        $tables[$row[0]] = "Table";
                    }
                }
            } else {
                #check tables not array then set into $tables explode tables of delimiter ","
                $tables = is_array($tables) ? $tables : explode(',', $tables);
            }
            #begin file data base
            $sql = "/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
                    /*!40101 SET NAMES utf8 */;
                    /*!50503 SET NAMES utf8mb4 */;
                    /*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
                    /*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
                ";
            #cycle through
            # loop all tables into key as $table and value as $type
            # Loop through each table and create a backup
            $sql .= "\n\n";
            $sql .= "SET FOREIGN_KEY_CHECKS = 0;";
            $sql .= "\n\n";
            # Backup Tables
            $sql .= $this->backupTables($connection, $tables);
            # Backup Views
            $sql .= $this->backupViews($connection, $tables, $database);
            # Backup Functions
            $sql .= $this->backupFunctions($connection, $database);
            # Backup Views
            $sql .= $this->backupViews2($connection);
            # Backup Triggers
            $sql .= $this->backupTriggers($connection);
            # Backup Procedures
            $sql .= $this->backupProcedures($connection, $database);
            # Set foreign key on
            $sql .= "SET foreign_key_checks = 1;";
            $sql .= "\n";

            # append to file end of database file
            // $sql .= "/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
            //             /*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
            //             /*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;";

            # save the backup to a file

            $filename = 'Backup-' . date('Y-m-d-H-i-s') . '.sql';
            if (!File::isDirectory(storage_path('app/backups'))) {
                File::makeDirectory(storage_path('app/backups'), 0755, true);
            }
            $path = storage_path('app/backups/' . $filename);
            file_put_contents($path, $sql);

            # close the database connection
            mysqli_close($connection);
            session()->flash('message', cbLang('success_add_backup'));
            session()->flash('message_type', 'success');
            return response()->json(['message' => cbLang('success_add_backup'), 'message_type' => 'success']);
        } catch (Exception $ex) {
            return response()->json(['message' => cbLang('error_add_backup')]);
        }
    }

    public function backupTables($connection, $tables)
    {
        $sql = '';
        foreach ($tables as $table => $type) {
            $num_rows_query = 100;
            # query to get all records in table
            $result = mysqli_query($connection, 'SELECT * FROM ' . $table);
            $num_fields = (($___mysqli_tmp = mysqli_num_fields($result)) ? $___mysqli_tmp : false);

            if ($type == "Table") {
                # query to get structre of table (structure fileds)
                $sql .= 'DROP TABLE IF EXISTS `' . $table . '`;';
                $row2 = mysqli_fetch_row(mysqli_query($connection, 'SHOW CREATE TABLE `' . $table . '`'));

                $sql .= "\n\n" . $row2[1] . ";\n\n";
                $sql .= "\n";
                $sql .= "/*!40000 ALTER TABLE `$table` DISABLE KEYS */;";
                $sql .= "\n";
                $counter = 0;
                $temp_insert = "";
                # loop all structure fields and split each 100 records into one query insert
                foreach ($result as $row) {
                    $record = array();
                    foreach ($row as $key => $value) {
                        if ($value == null) {
                            $value = 'NULL';
                        } else {
                            $value = addslashes($value);
                            $value = str_replace("\n", "\\n", $value);
                            $value = "'" . $value . "'";
                        }
                        array_push($record, $value);
                    }
                    $arr_values_str = implode(",", $record);
                    $temp_insert .= "($arr_values_str),\n";
                    $counter++;

                    $sql .= 'INSERT INTO `' . $table . '` VALUES ' . "\n";
                    $temp_insert = rtrim($temp_insert);
                    $temp_insert = rtrim($temp_insert, ",");
                    $sql .= $temp_insert;
                    $sql .= ";\n";
                    $temp_insert = "";

                }
                $sql .= "\n\n\n";
            }

        }
        return $sql;
    }

    public function backupViews($connection, $tables, $database)
    {
        $sql = '';
        foreach ($tables as $table => $type) {
            $num_rows_query = 100;
            $result = mysqli_query($connection, 'SELECT * FROM ' . $table);
            if ($type == "View") {
                $sql .= 'DROP VIEW IF EXISTS `' . $table . '`;';
                $sql .= "\n";
                # get information schema of view
                $schema_view = mysqli_query($connection, "SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE   TABLE_NAME='$table' AND TABLE_SCHEMA='$database'");
                $create_table = "CREATE TABLE IF NOT EXISTS `$table`\n"
                    . "(\n";
                $fields_view = array();
                while ($row = mysqli_fetch_row($schema_view)) {
                    $fields_view[] = "`" . $row[3] . "`" . " " . $row[15] . " " . ($row[6] == "YES" ? "NULL" : "NOT NULL") . " " . ($row[14] != "" ? "COLLATE '" . $row[14] . "'" : "");
                }
                $create_table .= implode(",\n", $fields_view);
                $create_table .= "\n) ENGINE=MyISAM;";
                $sql .= $create_table . "\n\n\n";
            }
        }
        return $sql;
    }

    public function backupFunctions($connection, $database)
    {
        $sql = '';
        $functions_result = mysqli_query($connection, "show function status");

        $functions = array();
        while ($row = mysqli_fetch_row($functions_result)) {
            if ($row[0] == $database) {
                $functions[] = $row[1];
            }
        }
        foreach ($functions as $function) {
            # append to file schema to create function
            $sql .= "-- *******************Function: $function************************** " . "\n\n";
            $sql .= 'DROP FUNCTION IF EXISTS `' . $function . '`;';
            $row2 = mysqli_fetch_row(mysqli_query($connection, 'SHOW CREATE FUNCTION `' . $function . "`"));
            $sql .= "\n\n";
            $sql .= "\n\n" . rtrim($row2[2], ';') . ";" . "\n\n";
            $sql .= "\n\n";
        }
        return $sql;
    }

    public function backupViews2($connection)
    {
        $sql = '';
        $views_result = mysqli_query($connection, 'SHOW TABLE STATUS WHERE Engine IS NULL');
        $views = array();
        while ($row = mysqli_fetch_row($views_result)) {
            $views[] = $row[0];
        }

        foreach ($views as $view) {
            # append to file schema to create function
            $sql .= "-- *******************VIEW: $view************************** " . "\n\n";
            $sql .= 'DROP VIEW IF EXISTS `' . $view . '`;';
            $sql .= 'DROP TABLE IF EXISTS `' . $view . '`;';
            $row2 = mysqli_fetch_row(mysqli_query($connection, 'SHOW CREATE VIEW `' . $view . "`"));
            $sql .= "\n\n" . $row2[1] . ";\n\n";
        }

        return $sql;
    }

    public function backupTriggers($connection)
    {
        $sql = '';
        $query = "SHOW TRIGGERS;";
        $result = mysqli_query($connection, $query);
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $trigger_name = $row["Trigger"];
            $trigger_table = $row["Table"];
            $trigger_event = $row["Event"];
            $trigger_timing = $row["Timing"];
            $trigger_statement = $row["Statement"];

            // Write trigger SQL to file
            $sql .= "-- *******************TRIGGER: $trigger_name************************** " . "\n\n";
            $sql .= 'DROP TRIGGER IF EXISTS ' . $trigger_name . ";\n";
            $sql .= "CREATE TRIGGER $trigger_name $trigger_timing $trigger_event ON $trigger_table\n";
            $sql .= "FOR EACH ROW\n";
            if (substr($trigger_statement, -1) == ";") {
                $sql .= $trigger_statement . "\n\n\n\n";
            } else {
                $sql .= $trigger_statement . ";\n\n\n\n";

            }

        }
        return $sql;
    }

    public function backupProcedures($connection, $database)
    {
        $sql = '';
        $query = "SHOW PROCEDURE STATUS WHERE Db = '$database';";
        $result = mysqli_query($connection, $query);

        while ($row = mysqli_fetch_assoc($result)) {
            $procedureName = $row['Name'];

            // Drop the stored procedure
            $sql .= "DROP PROCEDURE IF EXISTS `$procedureName`;\n";
            // Backup the stored procedure to a file
            $q = "SHOW CREATE PROCEDURE `$procedureName`;\n";
            $result2 = mysqli_query($connection, $q);
            $row2 = mysqli_fetch_assoc($result2);
            $procedureDefinition = $row2['Create Procedure'];
            $procedureDefinition = str_replace("CREATE PROCEDURE", "CREATE PROCEDURE IF NOT EXISTS", $procedureDefinition);
            $procedureDefinition .= ';\n';
            $sql .= $procedureDefinition;
        }
        return $sql;
    }

    public function getRestoreBackup($fileName)
    {
        try {
            $backupPath = storage_path('app/backups/' . $fileName);
            if (file_exists($backupPath)) {
                $sql = file_get_contents($backupPath);
                $sql = mb_convert_encoding($sql, 'UTF-8', 'ISO-8859-1');
                DB::unprepared($sql);
                DB::commit();
            }
            session()->flash('message', cbLang('success_restore_backup'));
            session()->flash('message_type', 'success');
            return response()->json(['message' => 'done']);
        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()]);

        }
        return $sql;
    }

    public function getDeleteBackup($fileName)
    {
        try {
            $backupPath = storage_path('app/backups/' . $fileName);
            if (file_exists($backupPath)) {
                File::delete($backupPath);
            }
            return response()->json(['message' => 'done']);
        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()]);
        }
    }
}
