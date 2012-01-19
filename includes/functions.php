<?php


function assert_example_works($expression, $continue_if_true = false) {
    if ($expression) {
        if ($continue_if_true) {
            return;
        } else {
            echo 'It works!';
            exit(0);
        }
    } else {
        echo 'It DOES NOT work!';
        exit(0);
    }
}

/**
 * @param Zend\Db\Adapter $adapter
 */
function refresh_data($adapter) {

    $datas = include __DIR__ . '/../setup/data/data.php';

    try {
        foreach ($datas as $tableName => $tableData) {

            $adapter->query('DELETE FROM ' . $tableName, $adapter::QUERY_MODE_EXECUTE);

            foreach ($tableData as $rowName => $rowData) {

                $keys = array_keys($rowData);
                $values = array_values($rowData);
                array_walk(
                    $values,
                    function (&$value) {
                        $value = ($value == null) ? 'NULL' : '\'' . $value . '\'';
                    }
                );

                $insertSql = 'INSERT INTO ' . $tableName
                    . ' (' . implode(', ', $keys) . ') '
                    . ' VALUES (' .
                        implode(', ',
                            $values
                        )
                    . ');';

                $adapter->query(
                    $insertSql,
                    $adapter::QUERY_MODE_EXECUTE
                );
            }
        }
    } catch (\Exception $e) {
        echo $e->getMessage();
        exit(1);
    }

}