<?php
/**
 * ModelNews.php
 * Модель для новостей
 *
 * @author      Pereskokov Yurii
 * @copyright   2015 Pereskokov Yurii
 * @license     Mediasite
 * @link        http://www.mediasite.ru/
 */
class ModelNews extends MSBaseTape
{
    /** @var string */
    public $itemsTableName = '{news}';

    /** @return array */
    public function findAll()
    {
        return MSCore::db()->getAll('
            SELECT *
            FROM
            ' . PRFX . 'news
        ');
    }

    /**
     * @param int $limit
     * @param int $offset
     *
     * @return array
     */
    public function findLimit($limit, $offset = 0)
    {
        return MSCore::db()->getAll('
            SELECT *
            FROM
            ' . PRFX . 'news
            LIMIT ' . $offset . ',' . $limit
        );
    }
}